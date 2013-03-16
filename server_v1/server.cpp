#include "server_conn.h"

struct Judger_data {
    int runid;
    string vname;
    int type;
    int cha_id;
    Judger_data * next;
};

struct ARG {
    int num;
    int fd;
};

bool portreuse[70000]={false};
Judger_data *head,*tail;
bool thread_busy[MAX_JUDGER_NUMBER];
bool used[MAX_JUDGER_NUMBER];
Judger_data dorunid[MAX_JUDGER_NUMBER];
string jvname[MAX_JUDGER_NUMBER];
int temp_num;
int queuesize;
pthread_mutex_t mutex_link=PTHREAD_MUTEX_INITIALIZER;

void writelog(char * templog)
{
    FILE *fp=fopen(logfile,"a");
    if (fp!=NULL) {
        fprintf(fp,"%s",templog);
        fclose(fp);
    }
}

void writelog(const char * templog)
{
    writelog((char * )templog);
}

void writelog(string templog)
{
    writelog(templog.c_str());
}

void result_dealer(char filename[200],int temp_pid,int temp_runid,int temp_cid,char temp_username[])
{
    MYSQL * mysql;
    MYSQL_RES *res;
    MYSQL_ROW row;
    mysql=(MYSQL *)malloc(sizeof(MYSQL));
    mysql_init(mysql);
    if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
        perror("cannot connect mysql!\n");
        exit(1);
    }
    mysql_query(mysql,"set names utf8");
    FILE *target_file=fopen(filename,"r");
    int type,ri,mu,tu;
    char resu[100]={0};
    char ts[10][50];
    fscanf(target_file,"%s %d\n%s %d\n%s %d\n%s %d\n%s ",ts[0],&type,ts[1],&ri,ts[2],&mu,ts[3],&tu,ts[4]);
    fgets(resu,100,target_file);
    char update[200]={0};
    sprintf(update,"SELECT username,contest_belong,pid FROM status WHERE runid=%d",ri);
    mysql_query(mysql,update);
    res=mysql_use_result(mysql);
    row=mysql_fetch_row(res);
    temp_runid=ri;
    strcpy(temp_username,row[0]);
    temp_cid=atoi(row[1]);
    temp_pid=atoi(row[2]);
    mysql_free_result(res);
    resu[strlen(resu)-1]=0;
    sprintf(update,"UPDATE status set result='%s', memory_used=%d, time_used=%d WHERE runid=%d",resu,mu,tu,ri);
    mysql_query(mysql,update);
    if (strcmp(resu,"Accepted")==0) {
        sprintf(update,"SELECT count(*) FROM status WHERE username='%s' AND pid='%d' AND result='Accepted'",temp_username,temp_pid);
        mysql_query(mysql,update);
        res=mysql_use_result(mysql);
        row=mysql_fetch_row(res);
        if (atoi(row[0])==1) {
            mysql_free_result(res);
            sprintf(update,"UPDATE user set total_ac=total_ac+1 WHERE username='%s'",temp_username);
            mysql_query(mysql,update);
            res=mysql_use_result(mysql);
        }
        mysql_free_result(res);
        sprintf(update,"UPDATE problem set total_ac=total_ac+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Wrong Answer")==0) {
        sprintf(update,"UPDATE problem set total_wa=total_wa+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Runtime Error")==0) {
        sprintf(update,"UPDATE problem set total_re=total_re+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Presentation Error")==0) {
        sprintf(update,"UPDATE problem set total_pe=total_pe+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Time Limit Exceed")==0) {
        sprintf(update,"UPDATE problem set total_tle=total_tle+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Memory Limit Exceed")==0) {
        sprintf(update,"UPDATE problem set total_mle=total_mle+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Output Limit Exceed")==0) {
        sprintf(update,"UPDATE problem set total_ole=total_ole+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Restricted Function")==0) {
        sprintf(update,"UPDATE problem set total_rf=total_rf+1 WHERE pid=%d",temp_pid);
    }
    else if (strcmp(resu,"Compile Error")==0) {
        sprintf(update,"UPDATE problem set total_ce=total_ce+1 WHERE pid=%d",temp_pid);
    }
    mysql_query(mysql,update);
    char tempce[50000]={0};
    char ce_info_data[MAX_DATA_SIZE]={0};
    char ceupdate[MAX_DATA_SIZE]={0};
    while (strcmp(tempce,"__COMPILE-INFO-BEGIN-LABLE__")!=0&&strcmp(tempce,"__COMPILE-INFO-BEGIN-LABLE__\n")!=0&&strcmp(tempce,"__COMPILE-INFO-BEGIN-LABLE__\r\n")!=0) fgets(tempce,50000,target_file);
    while (1) {
        fgets(tempce,50000,target_file);
        if (strcmp(tempce,"__COMPILE-INFO-END-LABLE__")==0||strcmp(tempce,"__COMPILE-INFO-END-LABLE__\n")==0||strcmp(tempce,"__COMPILE-INFO-END-LABLE__\r\n")==0) break;
        strcat(ce_info_data,tempce);
    }
    std::string str1;
    str1=ce_info_data;
    int lastpos=0;

    while (str1.find("\\",lastpos,1)!=std::string::npos) {
        lastpos=str1.find("\\",lastpos,1);
        str1.replace(lastpos,1,"\\\\");
        lastpos+=2;
    }
    
    lastpos=0;
    while (str1.find("\"",lastpos,1)!=std::string::npos) {
        lastpos=str1.find("\"",lastpos,1);
        str1.replace(lastpos,1,"\\\"");
        lastpos+=2;
    }
    strcpy(ce_info_data,str1.c_str());
    sprintf(ceupdate,"UPDATE status set ce_info=\"%s\" WHERE runid=%d",ce_info_data,ri);
    mysql_query(mysql,ceupdate);
    char templog[200]={0};
    sprintf(templog,"Received a result, user: %s, runid: %d result:%s\n",temp_username,temp_runid,resu);
    writelog(templog);
    fclose(target_file);
    mysql_close(mysql);
    free(mysql);
}

bool dealneed_judge(ARG * arg,Judger_data *temp) {
    MYSQL_RES *res;
    MYSQL_ROW row;
    MYSQL * mysql;
    char buffer[MAX_DATA_SIZE]={0};
    int tnum=arg->num;
    int tfd=arg->fd;

    char templog[20000]={0};

    sprintf(templog,"Run fetched to no.%d\n",tnum);
    writelog(templog);
    mysql=(MYSQL *)malloc(sizeof(MYSQL));
    mysql_init(mysql);
    if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
        perror("cannot connect mysql!\n");
        exit(1);
    }
    mysql_query(mysql,"set names utf8");
    queuesize--;
    char query[200]={0};
    sprintf(query,"SELECT status.source,status.runid,status.language,status.pid,problem.ignore_noc FROM status,problem WHERE status.pid=problem.pid and runid=%d",temp->runid);
    //writelog(query);writelog("\n");
    int temp_t=mysql_real_query(mysql,query,strlen(query));
    if (temp_t) {
        perror("mysql query error!");
        exit(1);
    }
    res=mysql_use_result(mysql);
    row=mysql_fetch_row(res);
    if (temp->type==NEED_JUDGE&&strcmp(row[4],"1")==0) temp->type=DO_TESTALL;
    char filename[200]={0};
    sprintf(filename,"raw_files/%d.bott",temp->runid);
    writelog("FILENAME: ");writelog(filename);writelog("\n");
    FILE* datafile=fopen(filename,"w");
    if (datafile==NULL) {
        char templog[200]={0};
        sprintf(templog,"CANNOT OPEN FILE %s!!!\n",filename);
        writelog(templog);
    }
    fprintf(datafile,"<type> %d\n",temp->type);
    fprintf(datafile,"__SOURCE-CODE-BEGIN-LABLE__\n");
    fprintf(datafile,"%s\n",row[0]);
    fprintf(datafile,"__SOURCE-CODE-END-LABLE__\n");
    fprintf(datafile,"<runid> %s\n<language> %s\n<pid> %s\n",row[1],row[2],row[3]);
    int temp_pid=atoi(row[3]);
    mysql_free_result(res);
    sprintf(query,"SELECT number_of_testcase,time_limit,case_time_limit,memory_limit,special_judge_status,vname,vid FROM problem WHERE pid=%d",temp_pid);
    temp_t=mysql_real_query(mysql,query,strlen(query));
    if (temp_t) {
        perror("mysql query error!");
        exit(1);
    }
    res=mysql_use_result(mysql);
    row=mysql_fetch_row(res);
    fprintf(datafile,"<testcases> %s\n<time_limit> %s\n<case_limit> %s\n<memory_limit> %s\n<special> %s\n<vname> %s\n<vid> %s\n",row[0],row[1],row[2],row[3],row[4],row[5],row[6]);
    mysql_free_result(res);
    sprintf(query,"UPDATE status set result='Judging' WHERE runid=%d",temp->runid);
    mysql_real_query(mysql,query,strlen(query));
    fclose(datafile);
    int source=open(filename,O_RDONLY);
    while((temp_t=read(source,buffer,sizeof(buffer)))>0)
        write(tfd,buffer,temp_t);
    close(source);
    sprintf(filename,"results/%dres.bott",temp->runid);
    writelog("filename: ");
    writelog(filename);
    writelog("\n");
    FILE *target_file=fopen(filename,"w");
    bool got_things=false;
    memset(buffer,0,sizeof(buffer));
    while (!got_things)
    {
        while ((temp_t=recv(tfd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT))>0)
        {
            got_things=true;
            fputs(buffer,target_file);
        }
        if (temp_t==0) {
            fclose(target_file);
            char templog[200]={0};
            sprintf(templog,"Lost connection with judger %d.\n",tnum);
            writelog(templog);
            sprintf(templog,"Runid: %d requeued.\n",temp->runid);
            writelog(templog);
            sprintf(query,"UPDATE status set result='Judge Error & Requeued' WHERE runid=%d",temp->runid);
            mysql_real_query(mysql,query,strlen(query));
            temp=new Judger_data;
            temp->runid=dorunid[tnum].runid;
            temp->vname=dorunid[tnum].vname;
            temp->type=dorunid[tnum].type;
            temp->next=head;
            if (tail==NULL) tail=temp;
            head=temp;
            mysql_close(mysql);
            free(mysql);
            return true;
        }
        usleep(5000);
        if (buffer[0]!='<') got_things=false;
    }
    fclose(target_file);
    int temp_runid;
    char temp1[50],temp2[50],temp3[50];
    sscanf(buffer,"%s%s%s%d",temp1,temp2,temp3,&temp_runid);
    sprintf(filename,"results/%dres.bott",temp_runid);
    target_file=fopen(filename,"w");
    fputs(buffer,target_file);
    while (recv(tfd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT)>0)
        fputs(buffer,target_file);
    fclose(target_file);
    sprintf(query,"SELECT pid,contest_belong,username FROM status WHERE runid=%d",temp->runid);
    temp_t=mysql_real_query(mysql,query,strlen(query));
    if (temp_t) {
        perror("mysql query error!");
        exit(1);
    }
    res=mysql_use_result(mysql);
    row=mysql_fetch_row(res);
    temp_pid=atoi(row[0]);
    int temp_cid=atoi(row[1]);
    char temp_username[256]={0};
    strcpy(temp_username,row[2]);
    mysql_free_result(res);
    result_dealer(filename,temp_pid,temp->runid,temp_cid,temp_username);
    dorunid[tnum].runid=-1;
    thread_busy[tnum]=false;
    sprintf(templog,"%d Judge Run Finished\n",tnum);
    writelog(templog);
    mysql_close(mysql);
    free(mysql);

    return false;
}

void cha_result_dealer(char filename[200],int temp_chaid)
{
    MYSQL * mysql;
    mysql=(MYSQL *)malloc(sizeof(MYSQL));
    mysql_init(mysql);
    if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
        perror("cannot connect mysql!\n");
        exit(1);
    }
    mysql_query(mysql,"set names utf8");
    FILE *target_file=fopen(filename,"r");
    int type,ci;
    char resu[200]={0};
    char ts[10][50];
    fscanf(target_file,"%s %d\n%s %d\n%s ",ts[0],&type,ts[1],&ci,ts[2]);
    fgets(resu,200,target_file);
    resu[strlen(resu)-1]=0;
    char update[300]={0};
    sprintf(update,"UPDATE challenge set cha_result='%s' WHERE cha_id=%d",resu,ci);
    mysql_query(mysql,update);
    if (strcmp(resu,"Challenge Success")==0) {
        char query[300]={0};
        sprintf(query,"SELECT runid FROM challenge WHERE cha_id=%d",ci);
        int temp_t=mysql_real_query(mysql,query,strlen(query));
        MYSQL_RES *res=mysql_use_result(mysql);
        MYSQL_ROW row=mysql_fetch_row(res);
        int temp_runid=atoi(row[0]);
        mysql_free_result(res);
        sprintf(query,"Update status set result='Challenged' WHERE runid=%d",temp_runid);
        mysql_query(mysql,query);
    }

    char tempcd[50000]={0};
    char cd_info_data[MAX_DATA_SIZE]={0};
    char cdupdate[MAX_DATA_SIZE]={0};
    while (strcmp(tempcd,"__CHALLENGE-DETAIL-BEGIN-LABLE__")!=0&&strcmp(tempcd,"__CHALLENGE-DETAIL-BEGIN-LABLE__\n")!=0&&strcmp(tempcd,"__CHALLENGE-DETAIL-BEGIN-LABLE__\r\n")!=0) fgets(tempcd,50000,target_file);
    while (1) {
        fgets(tempcd,50000,target_file);
        if (strcmp(tempcd,"__CHALLENGE-DETAIL-END-LABLE__")==0||strcmp(tempcd,"__CHALLENGE-DETAIL-END-LABLE__\n")==0||strcmp(tempcd,"__CHALLENGE-DETAIL-END-LABLE__\r\n")==0) break;
        strcat(cd_info_data,tempcd);
    }
    std::string str1;
    str1=cd_info_data;
    int lastpos=0;

    while (str1.find("\"",lastpos,1)!=std::string::npos) {
        lastpos=str1.find("\"",lastpos,1);
        str1.replace(lastpos,1,"\\\"");
        lastpos+=2;
    }
    strcpy(cd_info_data,str1.c_str());
    sprintf(cdupdate,"UPDATE challenge set cha_detail=\"%s\" WHERE cha_id=%d",cd_info_data,ci);
    mysql_query(mysql,cdupdate);
    char templog[2000]={0};
    sprintf(templog,"Challenge result: cha_id: %d result: %s\n",ci,resu);
    writelog(templog);
    fclose(target_file);
    mysql_close(mysql);
    free(mysql);
}

bool dealdo_challenge(ARG * arg,Judger_data *temp) {
    MYSQL_RES *res;
    MYSQL_ROW row;
    MYSQL * mysql;
    char buffer[MAX_DATA_SIZE]={0};
    int tnum=arg->num;
    int tfd=arg->fd;
    char templog[20000]={0};

    sprintf(templog,"Challenge fetched to no.%d\n",tnum);
    writelog(templog);
    mysql=(MYSQL *)malloc(sizeof(MYSQL));
    mysql_init(mysql);
    if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
        perror("cannot connect mysql!\n");
        exit(1);
    }
    mysql_query(mysql,"set names utf8");
    queuesize--;
    char query[500]={0};
    sprintf(query,"SELECT source,cha_id,language,pid,data_type,data_lang,data_detail FROM status,challenge WHERE status.runid=challenge.runid and cha_id=%d",temp->cha_id);
    //writelog(query);writelog("\n");
    int temp_t=mysql_real_query(mysql,query,strlen(query));
    if (temp_t) {
        perror("mysql query error!");
        exit(1);
    }
    res=mysql_use_result(mysql);
    row=mysql_fetch_row(res);
    char filename[200]={0};
    sprintf(filename,"cha_raw_files/%d.bott",temp->cha_id);
    writelog("FILENAME: ");writelog(filename);writelog("\n");
    FILE* datafile=fopen(filename,"w");
    if (datafile==NULL) {
        char templog[200]={0};
        sprintf(templog,"CANNOT OPEN FILE %s!!!\n",filename);
        writelog(templog);
    }
    fprintf(datafile,"<type> %d\n",temp->type);
    fprintf(datafile,"__SOURCE-CODE-BEGIN-LABLE__\n");
    fprintf(datafile,"%s\n",row[0]);
    fprintf(datafile,"__SOURCE-CODE-END-LABLE__\n");
    fprintf(datafile,"<cha_id> %s\n<language> %s\n<pid> %s\n<data_type> %s\n<data_lang> %s\n",row[1],row[2],row[3],row[4],row[5]);
    fprintf(datafile,"__DATA-DETAIL-BEGIN-LABLE__\n");
    fprintf(datafile,"%s\n",row[6]);
    fprintf(datafile,"__DATA-DETAIL-END-LABLE__\n");
    int temp_pid=atoi(row[3]);
    mysql_free_result(res);
    sprintf(query,"SELECT case_time_limit,memory_limit,special_judge_status FROM problem WHERE pid=%d",temp_pid);
    temp_t=mysql_real_query(mysql,query,strlen(query));
    if (temp_t) {
        perror("mysql query error!");
        exit(1);
    }
    res=mysql_use_result(mysql);
    row=mysql_fetch_row(res);
    fprintf(datafile,"<time_limit> %s\n<case_limit> %s\n<memory_limit> %s\n<special> %s\n",row[0],row[0],row[1],row[2]);
    mysql_free_result(res);
    sprintf(query,"UPDATE challenge set cha_result='Testing' WHERE cha_id=%d",temp->cha_id);
    mysql_real_query(mysql,query,strlen(query));
    fclose(datafile);
    int source=open(filename,O_RDONLY);
    while((temp_t=read(source,buffer,sizeof(buffer)))>0)
        write(tfd,buffer,temp_t);
    close(source);
    sprintf(filename,"cha_results/%dres.bott",temp->cha_id);
    writelog("filename: ");writelog(filename);writelog("\n");
    FILE *target_file=fopen(filename,"w");
    bool got_things=false;
    memset(buffer,0,sizeof(buffer));
    while (!got_things)
    {
        while ((temp_t=recv(tfd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT))>0)
        {
            got_things=true;
            fputs(buffer,target_file);
        }
        if (temp_t==0) {
            fclose(target_file);
            char templog[200]={0};
            sprintf(templog,"Lost connection with judger %d.\n",tnum);
            writelog(templog);
            sprintf(templog,"Cha_id: %d requeued.\n",temp->cha_id);
            writelog(templog);
            sprintf(query,"UPDATE challenge set cha_result='Test Error & Requeued' WHERE cha_id=%d",temp->cha_id);
            mysql_real_query(mysql,query,strlen(query));
            temp=new Judger_data;
            temp->cha_id=dorunid[tnum].cha_id;
            temp->vname=dorunid[tnum].vname;
            temp->type=dorunid[tnum].type;
            temp->next=head;
            if (tail==NULL) tail=temp;
            head=temp;
            mysql_close(mysql);
            free(mysql);
            return true;
        }
        usleep(5000);
        if (buffer[0]!='<') got_things=false;
    }
    fclose(target_file);
    int temp_chaid;
    char temp1[50],temp2[50],temp3[50];
    sscanf(buffer,"%s%s%s%d",temp1,temp2,temp3,&temp_chaid);
    sprintf(filename,"cha_results/%dres.bott",temp_chaid);
    target_file=fopen(filename,"w");
    fputs(buffer,target_file);
    while (recv(tfd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT)>0)
        fputs(buffer,target_file);
    fclose(target_file);
    cha_result_dealer(filename,temp->cha_id);
    dorunid[tnum].cha_id=-1;
    thread_busy[tnum]=false;
    sprintf(templog,"%d Challenge Judge Finished\n",tnum);
    writelog(templog);
    mysql_close(mysql);
    free(mysql);

    return false;
}


void judger_thread(ARG* arg)
{
    int tnum=arg->num;
    while (1)
    {
        usleep(5000);
        if (thread_busy[tnum]) {
            Judger_data *temp=&dorunid[tnum];
            if (temp->type==NEED_JUDGE||temp->type==DO_PRETEST||temp->type==DO_TESTALL) {
                if (dealneed_judge(arg,temp)) return;
            }
            else if (temp->type==DO_CHALLENGE) {
                if (dealdo_challenge(arg,temp)) return;
            }
        }
    }
}


void * function (void * arg)
{
    int fd=((ARG *)arg)->fd;
    int tnum=((ARG *)arg)->num;
    char buffer[255]={0};
    thread_busy[tnum]=true;
    struct timeval case_startv,case_nowv;
    struct timezone case_startz,case_nowz;
    gettimeofday(&case_startv,&case_startz);
    int time_passed;
    while (1)
    {
        usleep(10000);
        gettimeofday(&case_nowv,&case_nowz);
        time_passed=(case_nowv.tv_sec-case_startv.tv_sec)*1000+(case_nowv.tv_usec-case_startv.tv_usec)/1000;
        if (recv(fd,buffer,255,MSG_DONTWAIT)>0||time_passed>5000) break;
    }
    char connect_type[50]={0};
    sscanf(buffer,"%s",connect_type);
    printf("current fd:%d\n",fd);
    if (strcmp(connect_type,submit_string)==0) {
        int runid;
        char vname[100];
        sscanf(buffer,"%s%d%s",connect_type,&runid,vname);
        char templog[200]={0};
        sprintf(templog,"received a submit, runid: %s:%d\ncurrent queuesize: %d\n",vname,runid,queuesize);
        writelog(templog);
        Judger_data *temp;
        temp=new Judger_data;
        temp->runid=runid;
        temp->vname=vname;
        temp->next=NULL;
        temp->type=NEED_JUDGE;
        pthread_mutex_lock(&mutex_link);
        if (tail!=NULL) tail->next=temp;
        tail=temp;
        if (head==NULL) head=temp;
        pthread_mutex_unlock(&mutex_link);
        queuesize++;
    }
    else if (strcmp(connect_type,pretest_string)==0) {
        int runid;
        char vname[100];
        sscanf(buffer,"%s%d%s",connect_type,&runid,vname);
        char templog[200]={0};
        sprintf(templog,"received a pretest, runid: %s:%d\ncurrent queuesize: %d\n",vname,runid,queuesize);
        writelog(templog);
        Judger_data *temp;
        temp=new Judger_data;
        temp->runid=runid;
        temp->vname=vname;
        temp->next=NULL;
        temp->type=DO_PRETEST;
        pthread_mutex_lock(&mutex_link);
        if (tail!=NULL) tail->next=temp;
        tail=temp;
        if (head==NULL) head=temp;
        pthread_mutex_unlock(&mutex_link);
        queuesize++;
    }
    else if (strcmp(connect_type,error_string)==0) {
        int runid;
        char vname[100];
        sscanf(buffer,"%s%d%s",connect_type,&runid,vname);
        char templog[200]={0};
        sprintf(templog,"received a error rejudge, runid: %d\ncurrent queuesize: %d\n",runid,queuesize);
        writelog(templog);
        Judger_data *temp;
        temp=new Judger_data;
        temp->runid=runid;
        temp->vname=vname;
        temp->next=NULL;
        temp->type=NEED_JUDGE;
        pthread_mutex_lock(&mutex_link);
        if (tail!=NULL) tail->next=temp;
        tail=temp;
        if (head==NULL) head=temp;
        pthread_mutex_unlock(&mutex_link);
        queuesize++;
    }
    else if (strcmp(connect_type,challenge_string)==0) {
        int cha_id;
        char vname[100];
        sscanf(buffer,"%s%d%s",connect_type,&cha_id,vname);
        char templog[200]={0};
        sprintf(templog,"received a challenge, cha_id: %s:%d\ncurrent queuesize: %d\n",vname,cha_id,queuesize);
        writelog(templog);
        Judger_data *temp;
        temp=new Judger_data;
        temp->cha_id=cha_id;
        temp->vname=vname;
        temp->next=NULL;
        temp->type=DO_CHALLENGE;
        pthread_mutex_lock(&mutex_link);
        if (tail!=NULL) tail->next=temp;
        tail=temp;
        if (head==NULL) head=temp;
        pthread_mutex_unlock(&mutex_link);
        queuesize++;
    }
    else if (strcmp(connect_type,judger_string)==0) {
        char templog[200]={0};
        char vname[100];
        sscanf(buffer,"%s%s",connect_type,vname);
        sprintf(templog,"judger %d : %s connected. \n",tnum,vname);
        jvname[tnum]=vname;
        writelog(templog);
        thread_busy[tnum]=false;
        judger_thread((ARG*) arg);
        writelog("Judger Finished\n");
        jvname[tnum]="";
    }
    else if (strcmp(connect_type,rejudge_string)==0) {
        int repid,recid;
        sscanf(buffer,"%s%d%d",connect_type,&repid,&recid);
        MYSQL_RES *res;
        MYSQL_ROW row;
        MYSQL * mysql;
        mysql=(MYSQL *)malloc(sizeof(MYSQL));
        mysql_init(mysql);
        if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
            perror("cannot connect mysql!\n");
            exit(1);
        }
        mysql_query(mysql,"set names utf8");
        char query[200]={0};
        sprintf(query,"SELECT runid,vname FROM status,problem WHERE result='Rejudging' and contest_belong=%d and status.pid=%d and status.pid=problem.pid order by runid",recid,repid);
//        writelog(query);
        mysql_query(mysql,query);
        res=mysql_use_result(mysql);
        int rejudge_num=0;
        pthread_mutex_lock(&mutex_link);
        while ((row=mysql_fetch_row(res)))
        {
            queuesize++;
            rejudge_num++;
            Judger_data *temp;
            temp=new Judger_data;
            temp->runid=atoi(row[0]);
            temp->vname=row[1];
            temp->next=NULL;
            temp->type=NEED_JUDGE;
            if (tail!=NULL) tail->next=temp;
            tail=temp;
            if (head==NULL) head=temp;
        }
        pthread_mutex_unlock(&mutex_link);
        char templog[200]={0};
        sprintf(templog,"received a rejudge request, pid: %d, cid: %d, num: %d\n",repid,recid,rejudge_num);
        writelog(templog);
        mysql_free_result(res);
        mysql_close(mysql);
        free(mysql);
    }
    else if (strcmp(connect_type,testall_string)==0) {
        int recid;
        sscanf(buffer,"%s%d",connect_type,&recid);
        MYSQL_RES *res;
        MYSQL_ROW row;
        MYSQL * mysql;
        mysql=(MYSQL *)malloc(sizeof(MYSQL));
        mysql_init(mysql);
        if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
            perror("cannot connect mysql!\n");
            exit(1);
        }
        mysql_query(mysql,"set names utf8");
        char query[200]={0};
        sprintf(query,"SELECT runid,vname FROM status,problem WHERE result='Testing' and contest_belong=%d and status.pid=problem.pid order by runid",recid);
        //writelog(query);
        mysql_query(mysql,query);
        res=mysql_use_result(mysql);
        int rejudge_num=0;
        pthread_mutex_lock(&mutex_link);
        while ((row=mysql_fetch_row(res)))
        {
            queuesize++;
            rejudge_num++;
            Judger_data *temp;
            temp=new Judger_data;
            temp->runid=atoi(row[0]);
            temp->vname=row[1];
            temp->next=NULL;
            temp->type=DO_TESTALL;
            if (tail!=NULL) tail->next=temp;
            tail=temp;
            if (head==NULL) head=temp;
        }
        pthread_mutex_unlock(&mutex_link);
        char templog[200]={0};
        sprintf(templog,"received a test all request, cid: %d, num: %d\n",recid,rejudge_num);
        writelog(templog);
        mysql_free_result(res);
        mysql_close(mysql);
        free(mysql);
    }
    else {
        writelog("Illegal connection!\nServer Recieved:\n");
        writelog(buffer);
        writelog("\n");
    }
    close(fd);
    //printf("fd after close:%d\n",fd);
    free(arg);
    pthread_detach(pthread_self());
    used[tnum]=false;
    thread_busy[tnum]=false;
    pthread_exit(NULL);
}

void * fetcher(void * arg)
{
    while (1) {
        usleep(2013);
        pthread_mutex_lock(&mutex_link);
        if (head==NULL) {
            pthread_mutex_unlock(&mutex_link);
            //printf("Empty queue.\n");
            continue;
        }
        Judger_data *tj=head,*last=NULL,*tmp;
        while (tj!=NULL) {
            bool f=false;
            for (int i=0;i<MAX_JUDGER_NUMBER;i++) {
                if (!thread_busy[i]&&used[i]&&jvname[i]==tj->vname) {
                    char templog[200];
                    if (tj->type==DO_CHALLENGE) sprintf(templog,"Fetched Challenge %d\n",tj->cha_id);
                    else if (tj->type==NEED_JUDGE) sprintf(templog,"Fetched Run %d\n",tj->runid);
                    else if (tj->type==DO_PRETEST) sprintf(templog,"Fetched Pretest %d\n",tj->runid);
                    writelog(templog);
                    dorunid[i]=*tj;
                    if (last==NULL) head=tj->next;
                    else last->next=tj->next;
                    if (tj->next==NULL) tail=last;
                    tmp=tj;tj=tj->next;
                    f=true;
                    free(tmp);
                    thread_busy[i]=true;
                    break;
                }
            }
            if (!f) {
                last=tj;
                tj=tj->next;
            }
        }
        pthread_mutex_unlock(&mutex_link);
    }
}

int main(int argc, char * argv[])
{
    init();
    mkdir("raw_files",0777);
    mkdir("results",0777);
    mkdir("cha_raw_files",0777);
    mkdir("cha_results",0777);
    pthread_t tid;
    ARG *arg;
    MYSQL_RES *res;
    MYSQL_ROW row;
    MYSQL * mysql;
    int sockfd, client_fd;
    struct sockaddr_in my_addr;
    struct sockaddr_in remote_addr;
    if ((sockfd = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
        perror("socket() error\n");
        exit(1);
    }
    my_addr.sin_family = AF_INET;
    my_addr.sin_port = htons(server_port);
    my_addr.sin_addr.s_addr = INADDR_ANY;
    bzero(&(my_addr.sin_zero),8);
    if (bind(sockfd, (struct sockaddr *) & my_addr, sizeof (struct sockaddr)) == -1) {
        perror("bind() error\n");
        exit(1);
    }
    if (listen(sockfd, 10) == -1) {
        perror("listen() error\n");
        exit(1);
    }
    socklen_t sin_size = sizeof (struct sockaddr_in);
    mysql=(MYSQL *)malloc(sizeof(MYSQL));
    mysql_init(mysql);
    if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
        perror("cannot connect mysql!\n");
        exit(1);
    }
    mysql_query(mysql,"set names utf8");
    char query[200]={0};
    if (argc>1&&strcmp(argv[1],"sj")==0) {
        sprintf(query,"SELECT runid,vname FROM status,problem WHERE (result='Waiting' or result='Judging' or result='Rejudging') and status.pid=problem.pid order by runid");
    }
    else {
        sprintf(query,"SELECT runid,vname FROM status,problem WHERE result='Waiting' and status.pid=problem.pid order by runid");
    }
    int temp_t=mysql_real_query(mysql,query,strlen(query));
    if (temp_t) {
        perror("cannot query mysql!\n");
        exit(1);
    }
    res=mysql_use_result(mysql);
    while ((row=mysql_fetch_row(res)))
    {
        queuesize++;
        Judger_data *temp;
        temp=new Judger_data;
        temp->runid=atoi(row[0]);
        temp->vname=row[1];
        temp->type=NEED_JUDGE;
        temp->next=NULL;
        if (tail!=NULL) tail->next=temp;
        tail=temp;
        if (head==NULL) head=temp;
    }
    mysql_free_result(res);
    mysql_close(mysql);
    free(mysql);
    pthread_create(&tid,NULL,fetcher,NULL);
    while (1)
    {
        if ((client_fd = accept(sockfd, (struct sockaddr *) & remote_addr, &sin_size)) == -1) {
            perror("accept() error");
            exit(1);
        }
        char templog[200]={0};
        sprintf(templog,"received a connection from %s:%d\n", inet_ntoa(remote_addr.sin_addr), remote_addr.sin_port);
        writelog(templog);
        arg=new ARG;
        arg->fd=client_fd;
        for (temp_num=0;temp_num<MAX_JUDGER_NUMBER;temp_num++) if (!used[temp_num]) break;
        arg->num=temp_num;
        used[temp_num]=true;
        pthread_create(&tid,NULL,function,(void *)arg);
    }
    close(sockfd);
    exit(0);
}
