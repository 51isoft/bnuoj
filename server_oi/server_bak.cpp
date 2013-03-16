#include "server_conn.h"

struct Judger_data {
    int runid;
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
    char resu[1000]={0};
    char ts[10][50];
    fscanf(target_file,"%s %d\n%s %d\n%s %d\n%s %d\n%s ",ts[0],&type,ts[1],&ri,ts[2],&mu,ts[3],&tu,ts[4]);
    fgets(resu,1000,target_file);
    char update[2000]={0};
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
        char tempce[500]={0};
        char ce_info_data[MAX_DATA_SIZE]={0};
        char ceupdate[MAX_DATA_SIZE]={0};
        fgets(tempce,500,target_file);
        while (1) {
            fgets(tempce,500,target_file);
            if (strcmp(tempce,"__COMPILE-INFO-END-LABLE__")==0||strcmp(tempce,"__COMPILE-INFO-END-LABLE__\n")==0) break;
            strcat(ce_info_data,tempce);
        }
        std::string str1;
        str1=ce_info_data;
        int lastpos=0;
        while (str1.find("\"",lastpos,1)!=std::string::npos) {
            lastpos=str1.find("\"",lastpos,1);
            str1.replace(lastpos,1,"\\\"");
            lastpos+=2;
        }
        strcpy(ce_info_data,str1.c_str());
        sprintf(ceupdate,"UPDATE status set ce_info=\"%s\" WHERE runid=%d",ce_info_data,ri);
        mysql_query(mysql,ceupdate);
        sprintf(update,"UPDATE problem set total_ce=total_ce+1 WHERE pid=%d",temp_pid);
    }
    mysql_query(mysql,update);
    char templog[2000]={0};
    sprintf(templog,"Received a result, user: %s, runid: %d result:%s\n",temp_username,temp_runid,resu);
    writelog(templog);
    fclose(target_file);
    mysql_close(mysql);
    free(mysql);
}

void judger_thread(ARG* arg)
{
    MYSQL_RES *res;
    MYSQL_ROW row;
    MYSQL * mysql;
    char buffer[MAX_DATA_SIZE]={0};
    while (1)
    {
        usleep(5000);
        if (thread_busy[arg->num]) {
            mysql=(MYSQL *)malloc(sizeof(MYSQL));
            mysql_init(mysql);
            if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
                perror("cannot connect mysql!\n");
                exit(1);
            }
            mysql_query(mysql,"set names utf8");
            queuesize--;
            pthread_mutex_lock(&mutex_link);
            if (head==NULL) {
                thread_busy[arg->num]=false;
                pthread_mutex_unlock(&mutex_link);
                continue;
            }
            Judger_data *temp=head;
            head=head->next;
            if (temp==tail) tail=NULL;
            pthread_mutex_unlock(&mutex_link);
            char query[200]={0};
            sprintf(query,"SELECT source,runid,language,pid FROM status WHERE runid=%d",temp->runid);
            int temp_t=mysql_real_query(mysql,query,strlen(query));
            if (temp_t) {
                perror("mysql query error!");
                exit(1);
            }
            res=mysql_use_result(mysql);
            row=mysql_fetch_row(res);
            char filename[50]={0};
            sprintf(filename,"raw_files/%d.bott",temp->runid);
            FILE* datafile=fopen(filename,"w");
            fprintf(datafile,"<type> 2\n");
            fprintf(datafile,"__SOURCE-CODE-BEGIN-LABLE__\n");
            fprintf(datafile,"%s\n",row[0]);
            fprintf(datafile,"__SOURCE-CODE-END-LABLE__\n");
            fprintf(datafile,"<runid> %s\n<language> %s\n<pid> %s\n",row[1],row[2],row[3]);
            int temp_pid=atoi(row[3]);
            mysql_free_result(res);
            sprintf(query,"SELECT number_of_testcase,time_limit,case_time_limit,memory_limit,special_judge_status FROM problem WHERE pid=%d",temp_pid);
            temp_t=mysql_real_query(mysql,query,strlen(query));
            if (temp_t) {
                perror("mysql query error!");
                exit(1);
            }
            res=mysql_use_result(mysql);
            row=mysql_fetch_row(res);
            fprintf(datafile,"<testcases> %s\n<time_limit> %s\n<case_limit> %s\n<memory_limit> %s\n<special> %s\n",row[0],row[1],row[2],row[3],row[4]);
            mysql_free_result(res);
            sprintf(query,"UPDATE status set result='Judging' WHERE runid=%d",temp->runid);
            mysql_real_query(mysql,query,strlen(query));
            fclose(datafile);
            int source=open(filename,O_RDONLY);
            while((temp_t=read(source,buffer,sizeof(buffer)))>0)
                write(arg->fd,buffer,temp_t);
            close(source);
            sprintf(filename,"results/%dres.bott",temp->runid);
            FILE *target_file=fopen(filename,"w");
            bool got_things=false;
            memset(buffer,0,sizeof(buffer));
            while (!got_things)
            {
                while ((temp_t=recv(arg->fd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT))>0)
                {
                    got_things=true;
                    fputs(buffer,target_file);
                }
                if (temp_t==0) {
                    fclose(target_file);
                    char templog[200]={0};
                    sprintf(templog,"Lost connection with judger %d.\n",arg->num);
                    writelog(templog);
                    sprintf(templog,"Runid: %d requeued.\n",temp->runid);
                    writelog(templog);
                    sprintf(query,"UPDATE status set result='Waiting' WHERE runid=%d",temp->runid);
                    mysql_real_query(mysql,query,strlen(query));
                    temp->next=head;
                    if (tail==NULL) tail=temp;
                    head=temp;
                    mysql_close(mysql);
                    free(mysql);
                    return;
                }
                usleep(5000);
            }
            fclose(target_file);
            int temp_runid;
            char temp1[50],temp2[50],temp3[50];
            sscanf(buffer,"%s%s%s%d",temp1,temp2,temp3,&temp_runid);
            sprintf(filename,"results/%dres.bott",temp_runid);
            target_file=fopen(filename,"w");
            fputs(buffer,target_file);
            while (recv(arg->fd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT)>0)
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
            thread_busy[arg->num]=false;
            free(temp);
            mysql_close(mysql);
            free(mysql);
        }
        else {
            int alive=recv(arg->fd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT);
            if (alive==0) {
                char templog[200]={0};
                sprintf(templog,"Lost connection with judger %d.\n",arg->num);
                writelog(templog);
                return;
            }
            if (alive>0) {
                mysql=(MYSQL *)malloc(sizeof(MYSQL));
                mysql_init(mysql);
                if (!mysql_real_connect(mysql,NULL,db_user,db_pass,db_table,MYSQL_PORT,NULL,0)) {
                    perror("cannot connect mysql!\n");
                    exit(1);
                }
                mysql_query(mysql,"set names utf8");
                int temp_runid;
                char temp1[50],temp2[50],temp3[50];
                sscanf(buffer,"%s%s%s%d",temp1,temp2,temp3,&temp_runid);
                char filename[200]={0};
                sprintf(filename,"results/%dres.bott",temp_runid);
                FILE *target_file=fopen(filename,"w");
                fputs(buffer,target_file);
                while (recv(arg->fd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT)>0)
                    fputs(buffer,target_file);
                fclose(target_file);
                char query[200]={0};
                sprintf(query,"SELECT pid,contest_belong,username FROM status WHERE runid=%d",temp_runid);
                int temp_t=mysql_real_query(mysql,query,strlen(query));
                if (temp_t) {
                    perror("mysql query error!");
                    exit(1);
                }
                res=mysql_use_result(mysql);
                row=mysql_fetch_row(res);
                int temp_pid=atoi(row[0]);
                int temp_cid=atoi(row[1]);
                char temp_username[256]={0};
                strcpy(temp_username,row[2]);
                mysql_free_result(res);
                result_dealer(filename,temp_pid,temp_runid,temp_cid,temp_username);
                mysql_close(mysql);
                free(mysql);
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
    //printf("current fd:%d\n",fd);
    if (strcmp(connect_type,submit_string)==0) {
        int runid;
        sscanf(buffer,"%s%d",connect_type,&runid);
        char templog[200]={0};
        sprintf(templog,"received a submit, runid: %d\n",runid);
        writelog(templog);
        Judger_data *temp;
        temp=new Judger_data;
        temp->runid=runid;
        temp->next=NULL;
        pthread_mutex_lock(&mutex_link);
        if (tail!=NULL) tail->next=temp;
        tail=temp;
        if (head==NULL) head=temp;
        pthread_mutex_unlock(&mutex_link);
        queuesize++;
    }
    else if (strcmp(connect_type,judger_string)==0) {
        char templog[200]={0};
        sprintf(templog,"judger %d connected. \n",tnum);
        writelog(templog);
        thread_busy[tnum]=false;
        judger_thread((ARG*) arg);
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
        sprintf(query,"SELECT runid FROM status WHERE result='Rejudging' and contest_belong=%d and pid=%d order by runid",recid,repid);
        mysql_query(mysql,query);
        res=mysql_use_result(mysql);
        int rejudge_num=0;
        pthread_mutex_lock(&mutex_link);
        while ((row=mysql_fetch_row(res)))
        {
            rejudge_num++;
            Judger_data *temp;
            temp=new Judger_data;
            temp->runid=atoi(row[0]);
            temp->next=NULL;
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
        usleep(5000);
        pthread_mutex_lock(&mutex_link);
        if (head==NULL) {
            pthread_mutex_unlock(&mutex_link);
            //printf("Empty queue.\n");
            continue;
        }
        pthread_mutex_unlock(&mutex_link);
        //printf("Not empty queue.\n");
        for (int i=0;i<MAX_JUDGER_NUMBER;i++) {
            if (!thread_busy[i]&&used[i]) {
                thread_busy[i]=true;
                break;
            }
        }
    }
}

int main(int argc, char * argv[])
{
    init();
    mkdir("raw_files",0777);
    mkdir("results",0777);
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
        sprintf(query,"SELECT runid FROM status WHERE result='Waiting' or result='Judging' order by runid");
    }
    else {
        sprintf(query,"SELECT runid FROM status WHERE result='Waiting' order by runid");
    }
    int temp_t=mysql_real_query(mysql,query,strlen(query));
    if (temp_t) {
        perror("cannot query mysql!\n");
        exit(1);
    }
    res=mysql_use_result(mysql);
    while ((row=mysql_fetch_row(res)))
    {
        Judger_data *temp;
        temp=new Judger_data;
        temp->runid=atoi(row[0]);
        temp->next=NULL;
        if (tail!=NULL) tail->next=temp;
        tail=temp;
        if (head==NULL) head=temp;
    }
    mysql_free_result(res);
    mysql_close(mysql);
    free(mysql);
    /*struct rlimit file_limit;
    getrlimit(RLIMIT_NOFILE,&file_limit);
    printf("%d %d\n",(int)file_limit.rlim_cur,(int)file_limit.rlim_max);
    file_limit.rlim_cur=file_limit.rlim_max=10240;
    setrlimit(RLIMIT_NOFILE,&file_limit);
    getrlimit(RLIMIT_NOFILE,&file_limit);
    printf("%d %d\n",(int)file_limit.rlim_cur,(int)file_limit.rlim_max);*/
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
        /*if (portreuse[remote_addr.sin_port]) printf("port reused.\n");
        else {
            printf("port newly used.\n");
            portreuse[remote_addr.sin_port]=true;
        }*/
        arg=new ARG;
        arg->fd=client_fd;
        for (temp_num=0;temp_num<MAX_JUDGER_NUMBER;temp_num++) if (!used[temp_num]) break;
        arg->num=temp_num;
        used[temp_num]=true;
        pthread_create(&tid,NULL,function,(void *)arg);
        /*if (head==NULL) continue;
        for (int i=0;i<MAX_JUDGER_NUMBER;i++) {
            if (!thread_busy[i]&&used[i]) {
                thread_busy[i]=true;
                break;
            }
        }*/
    }
    close(sockfd);
    exit(0);
}
