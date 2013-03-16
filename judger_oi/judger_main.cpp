#include "judger_conn.h"

struct Judger_data {
    char src[MAX_DATA_SIZE];
    int runid;
    int pid;
    int lang;
    int time_limit;
    int case_limit;
    int number_of_cases;
    int memory_limit;
    int special_judge_status;
    Judger_data * next;
};

int command_type;
int sockfd;
struct sockaddr_in server;
int array_got;
Judger_data *head,*tail,*temp;
int queuesize;
bool subjudger_status[MAX_JUDGER_NUMBER];
int subjudger_runid[MAX_JUDGER_NUMBER];
int subjudger_st[MAX_JUDGER_NUMBER];
int subjudger_limit[MAX_JUDGER_NUMBER];
pid_t subjudger_id[MAX_JUDGER_NUMBER];
int num;
int target;
char buffer[MAX_DATA_SIZE];
bool got_things;
char in_file[50]={0};
char bash_command[30]={0};
char out_file[50]={0};
vector<string> caseResult;

void writelog(char* log)
{
    FILE * fp=fopen(logfile,"a");
    if (fp!=NULL) {
        fprintf(fp,"%s",log);
        fclose(fp);
    }
}

void clean_files(int runid)
{
    char clean_command[100]={0};
    sprintf(clean_command,"rm %d*",runid);
    system(clean_command);
    if (fopen("Main.java","r")!=NULL||fopen("Main.class","r")!=NULL) system("rm *.java *.class");
}

void convert()
{
    FILE *server_offer=fopen("temp.bott","r");
    int offer_type;
    char type_str[50];
    fscanf(server_offer,"%s %d\n",type_str,&offer_type);
    if (offer_type==CHECK_STATUS) {
        writelog("Checking status...\n");
        FILE *report_status=fopen("status.bott","w");
        fprintf(report_status,"<type> %d\n<status> %d\n",JUDGER_STATUS_REPORT,queuesize);
        fclose(report_status);
        fclose(server_offer);
        writelog("Reporting status...\n");
        int source=open("status.bott",O_RDONLY);
        memset(buffer,0,sizeof(buffer));
        while((num=read(source,buffer,sizeof(buffer)))>0) write(sockfd,buffer,num);
       // write(sockfd,"__BOTT_FILE_OVER_LABLE__",24);
        close(source);
        writelog("Finished reporting\n");
        return;
    }
    if (offer_type==NEED_JUDGE) {
        temp=new Judger_data;
        if (tail==NULL) head=tail=temp;
        else {
            tail->next=temp;
            tail=temp;
        }
        temp->next=NULL;
        queuesize++;
        writelog("Received a judge request, ");
        fgets(buffer,MAX_DATA_SIZE,server_offer);
        memset(temp->src,0,sizeof(temp->src));
        while (fgets(buffer,MAX_DATA_SIZE,server_offer)&&strcmp(buffer,"__SOURCE-CODE-END-LABLE__\n")!=0)
            strcat(temp->src,buffer);
        char ts[20][50];
        fscanf(server_offer,"%s%d%s%d%s%d%s%d%s%d%s%d%s%d%s%d",ts[0],&temp->runid,ts[1],&temp->lang,ts[2],
            &temp->pid,ts[3],&temp->number_of_cases,ts[4],&temp->time_limit,ts[5],&temp->case_limit,ts[6],
            &temp->memory_limit,ts[7],&temp->special_judge_status);
        fclose(server_offer);
        char templog[1000]={0};
        sprintf(templog,"runid:%d\n",temp->runid);
        writelog(templog);
        return;
    }
    /*if (offer_type==SEND_DATA) {
        printf("Receiving data...\n");
        int pid_judge,number_need_recv;
        fscanf(server_offer,"%d%d",&pid_judge,&number_need_recv);
        char mkdir_command[20]={0};
        sprintf(mkdir_command,"mkdir testdata/%d",pid_judge);
        system(mkdir_command);
        char testdata_path[30]={0};
        FILE *testdata_file;
        for (int i=0;i<number_need_recv;i++)
        {
            printf("Receiving input file %d for pid: %d\n",i,pid_judge);
            sprintf(testdata_path,"testdata/%d/%d.in",pid_judge,i);
            testdata_file=fopen(testdata_path,"w");
            fgets(buffer,MAX_DATA_SIZE,server_offer);
            while (fgets(buffer,MAX_DATA_SIZE,server_offer)&&strcmp(buffer,"__TESTDATA-INPUT-END-LABLE__\n")!=0) fputs(buffer,testdata_file);
            fclose(testdata_file);
            printf("Received\n");
            printf("Receiving output file %d for pid: %d\n",i,pid_judge);
            sprintf(testdata_path,"testdata/%d/%d.out",pid_judge,i);
            testdata_file=fopen(testdata_path,"w");
            fgets(buffer,MAX_DATA_SIZE,server_offer);
            while (fgets(buffer,MAX_DATA_SIZE,server_offer)&&strcmp(buffer,"__TESTDATA-OUTPUT-END-LABLE__\n")!=0) fputs(buffer,testdata_file);
            fclose(testdata_file);
            printf("Received\n");
        }
        fclose(server_offer);
    }*/
}

void run_other_program(int runid, int pid, int testcase)
{
    sprintf(bash_command,"./%d",runid);
    sprintf(in_file,"testdata/%d/%d.in",pid,testcase);
    sprintf(out_file,"%d.ou",runid);
    freopen(in_file,"r",stdin);
    freopen(out_file,"w",stdout);
    setuid(lowprivid);
    ptrace(PTRACE_TRACEME,0,NULL,NULL);
    execl(bash_command,bash_command,NULL);
    exit(runid);
}

void run_python_program(int runid, int pid, int testcase)
{
    sprintf(bash_command,"%d.py",runid);
    sprintf(in_file,"testdata/%d/%d.in",pid,testcase);
    sprintf(out_file,"%d.ou",runid);
    char err_file[20]={0};
    sprintf(err_file,"%d.txt",runid);
    freopen(in_file,"r",stdin);
    freopen(out_file,"w",stdout);
    freopen(err_file,"w",stderr);
    setuid(lowprivid);
    ptrace(PTRACE_TRACEME,0,NULL,NULL);
    execl("/usr/bin/python","python",bash_command,"-W",NULL);
    exit(runid);
}

void run_perl_program(int runid, int pid, int testcase)
{
    sprintf(bash_command,"%d.pl",runid);
    sprintf(in_file,"testdata/%d/%d.in",pid,testcase);
    sprintf(out_file,"%d.ou",runid);
    char err_file[20]={0};
    sprintf(err_file,"%d.txt",runid);
    freopen(in_file,"r",stdin);
    freopen(out_file,"w",stdout);
    freopen(err_file,"w",stderr);
    setuid(lowprivid);
    ptrace(PTRACE_TRACEME,0,NULL,NULL);
    execl("/usr/bin/perl","perl",bash_command,"-W",NULL);
    exit(runid);
}

void run_ruby_program(int runid, int pid, int testcase)
{
    sprintf(bash_command,"%d.rb",runid);
    sprintf(in_file,"testdata/%d/%d.in",pid,testcase);
    sprintf(out_file,"%d.ou",runid);
    char err_file[20]={0};
    sprintf(err_file,"%d.txt",runid);
    freopen(in_file,"r",stdin);
    freopen(out_file,"w",stdout);
    freopen(err_file,"w",stderr);
    setuid(lowprivid);
    ptrace(PTRACE_TRACEME,0,NULL,NULL);
    execl("/usr/bin/ruby","ruby",bash_command,"-W",NULL);
    exit(runid);
}

void run_csharp_program(int runid, int pid, int testcase)
{
    sprintf(bash_command,"%d.exe",runid);
    sprintf(in_file,"testdata/%d/%d.in",pid,testcase);
    sprintf(out_file,"%d.ou",runid);
    char err_file[20]={0};
    sprintf(err_file,"%d.txt",runid);
    freopen(in_file,"r",stdin);
    freopen(out_file,"w",stdout);
    freopen(err_file,"w",stderr);
    setuid(lowprivid);
    ptrace(PTRACE_TRACEME,0,NULL,NULL);
    execl("/usr/bin/mono","mono",bash_command,NULL);
    exit(runid);
}

void run_java_program(int runid, int pid, int testcase)
{
    sprintf(in_file,"testdata/%d/%d.in",pid,testcase);
    sprintf(out_file,"%d.ou",runid);
    freopen(in_file,"r",stdin);
    freopen(out_file,"w",stdout);
    //execl(bash_command,NULL);
    setuid(lowprivid);
    ptrace(PTRACE_TRACEME,0,NULL,NULL);
    execl("/usr/bin/java","java","-Djava.security.manager","-Djava.security.policy=java.policy","-client","Main",NULL);
    exit(runid);
}

void generate_case_result(int result, int runid, int mem_use, int time_use) 
{
    char templog[2000]={0};
    char tmpStr[2000]={0};
    mem_use/=1024;
    switch (result)
    {
        case AC_STATUS:
            sprintf(tmpStr,"AC|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Accepted. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case RE_STATUS:
            sprintf(tmpStr,"RE|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Runtime Error. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case CE_STATUS:
            sprintf(tmpStr,"CE|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Compile Error. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case WA_STATUS:
            sprintf(tmpStr,"WA|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Wrong Answer. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case PE_STATUS:
            sprintf(tmpStr,"PE|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Presentation Error. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case TLE_STATUS:
            sprintf(tmpStr,"TLE|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Time Limit Exceed. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case MLE_STATUS:
            sprintf(tmpStr,"MLE|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Memory Limit Exceed. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case OLE_STATUS:
            sprintf(tmpStr,"OLE|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Output Limit Exceed. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case RF_STATUS:
            sprintf(tmpStr,"RF|%d_%d,", mem_use, time_use);
            sprintf(templog,"Case Result for runid:%d Restricted Function. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
    }   // end of switch (result)
    string s = tmpStr;
    caseResult.push_back(s);
}

void summary_case_result(int runid) 
{
    char result_filename[50]={0};
    //mem_use/=1024;
    sprintf(result_filename,"results/%d.bott",runid);
    FILE *result_file=fopen(result_filename,"w");
    fprintf(result_file,"<type> %d\n<runid> %d\n<memory_used> %d\n<time_used> %d\n<result> ",RESULT_REPORT,runid, 0, 0);
    char templog[2000]={0};
    int score = 0;
    string outs = "";
    for (int i=0; i<caseResult.size(); ++i) {
        outs += caseResult[i];  
        if (caseResult[i][0] == 'A' && caseResult[i][1] == 'C') score ++;
    }
    string head;
    if (score == caseResult.size()) 
        head = "Accepted";
    else 
        head = "Unaccepted";
    score *= 10;
    
    fprintf(result_file,"%s|%d:%s\n", head.c_str(), score, outs.c_str());
    sprintf(templog,"Result for runid:%d %s. Total Mem:%d, time:%d\n",runid, outs.c_str(), 0, 0);
    writelog(templog);
    
    fprintf(result_file,"__COMPILE-INFO-BEGIN-LABLE__\n");
    fprintf(result_file,"\n__COMPILE-INFO-END-LABLE__\n");
    fclose(result_file);
    clean_files(runid);
}

void generate_result(int result, int runid, int mem_use, int time_use)
{
    char result_filename[50]={0};
    mem_use/=1024;
    sprintf(result_filename,"results/%d.bott",runid);
    FILE *result_file=fopen(result_filename,"w");
    fprintf(result_file,"<type> %d\n<runid> %d\n<memory_used> %d\n<time_used> %d\n<result> ",RESULT_REPORT,runid,mem_use,time_use);
    char templog[2000]={0};
    switch (result)
    {
        case AC_STATUS:
            fprintf(result_file,"Accepted\n");
            sprintf(templog,"Result for runid:%d Accepted. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case RE_STATUS:
            fprintf(result_file,"Runtime Error\n");
            sprintf(templog,"Result for runid:%d Runtime Error. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case CE_STATUS:
            fprintf(result_file,"Compile Error\n");
            sprintf(templog,"Result for runid:%d Compile Error. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case WA_STATUS:
            fprintf(result_file,"Wrong Answer\n");
            sprintf(templog,"Result for runid:%d Wrong Answer. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case PE_STATUS:
            fprintf(result_file,"Presentation Error\n");
            sprintf(templog,"Result for runid:%d Presentation Error. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case TLE_STATUS:
            fprintf(result_file,"Time Limit Exceed\n");
            sprintf(templog,"Result for runid:%d Time Limit Exceed. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case MLE_STATUS:
            fprintf(result_file,"Memory Limit Exceed\n");
            sprintf(templog,"Result for runid:%d Memory Limit Exceed. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case OLE_STATUS:
            fprintf(result_file,"Output Limit Exceed\n");
            sprintf(templog,"Result for runid:%d Output Limit Exceed. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case RF_STATUS:
            fprintf(result_file,"Restricted Function\n");
            sprintf(templog,"Result for runid:%d Restricted Function. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case ND_STATUS:
            fprintf(result_file,"Problem Disabled (No Data)\n");
            sprintf(templog,"Result for runid:%d No Data. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
        case NJ_STATUS:
            fprintf(result_file,"Problem Disabled (No SPJ)\n");
            sprintf(templog,"Result for runid:%d No SPJ. Total Mem:%d, time:%d\n",runid,mem_use,time_use);
            writelog(templog);
            break;
    }
    fprintf(result_file,"__COMPILE-INFO-BEGIN-LABLE__\n");
    if (result!=ND_STATUS) {
        char ce_info_filename[20]={0};
        sprintf(ce_info_filename,"%d.txt",runid);
        FILE *ce_info_file=fopen(ce_info_filename,"r");
        int nol=0;
        while (fgets(buffer,MAX_DATA_SIZE,ce_info_file)&&nol<50) {
            nol++;
            fputs(buffer,result_file);
        }
        fclose(ce_info_file);
    }
    fprintf(result_file,"\n__COMPILE-INFO-END-LABLE__\n");
    fclose(result_file);
    clean_files(runid);
}

void subjudger()
{
    char code_name[15]={0};
    char compile_command[80]={0};
    FILE *code_file;
    bool need_compile=true;
//compile part
    switch (head->lang)
    {
        case CLANG:
            sprintf(code_name,"%d.c",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"gcc %s -o %d -O -fno-asm -Wall -lm 2>%d.txt",code_name,head->runid,head->runid);
            break;
        case CPPLANG:
            sprintf(code_name,"%d.cpp",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"g++ %s -o %d -O -fno-asm -Wall -lm 2>%d.txt",code_name,head->runid,head->runid);
            break;
        case JAVALANG:
            sprintf(code_name,"Main.java");
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"javac -g:none -Xlint %s 2>%d.txt",code_name,head->runid);
            head->case_limit*=3;
            head->time_limit*=3;
            head->memory_limit*=2;
            break;
        case FPASLANG:
            sprintf(code_name,"%d.pas",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"fpc %s -o%d -Co -Cr -Ct -Ci >%d.txt",code_name,head->runid,head->runid);
            break;
        case PYLANG:
            sprintf(code_name,"%d.py",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            need_compile=false;
            break;
        case CSLANG:
            sprintf(code_name,"%d.cs",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"mcs %s -out:%d.exe 2>%d.txt",code_name,head->runid,head->runid);
            break;
        case FORTLANG:
            sprintf(code_name,"%d.f",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"gfortran %s -o%d -O -Wall 2>%d.txt",code_name,head->runid,head->runid);
            break;
        case PERLLANG:
            sprintf(code_name,"%d.pl",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            need_compile=false;
            break;
        case RUBYLANG:
            sprintf(code_name,"%d.rb",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            need_compile=false;
            break;
        case ADALANG:
            sprintf(code_name,"%d.ada",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"gnatmake %s 2>%d.txt",code_name,head->runid);
            break;
        case SMLLANG:
            sprintf(code_name,"%d.sml",head->runid);
            code_file=fopen(code_name,"w");
            fprintf(code_file,"%s",head->src);
            fclose(code_file);
            sprintf(compile_command,"mlton -output %d %s 2>%d.txt",head->runid,code_name,head->runid);
            break;
    }
    if (need_compile) {
        char templog[200]={0};
        sprintf(templog,"Compiling runid:%d\n",head->runid);
        writelog(templog);
        struct rlimit compile_limit;
        if (head->lang==JAVALANG) compile_limit.rlim_max=compile_limit.rlim_cur=JAVA_COMPILE_TIME;
        else compile_limit.rlim_max=compile_limit.rlim_cur=GCC_COMPILE_TIME;
        int cpid;
        if ((cpid=fork())==0) {
            setrlimit(RLIMIT_CPU,&compile_limit);
            system(compile_command);
            exit(0);
        }
        else {
            int cstat;
            waitpid(cpid,&cstat,NULL);
            if (WIFSIGNALED(cstat)&&WTERMSIG(cstat)!=0) {
                generate_result(CE_STATUS,head->runid,0,0);
                return;
            }
        }
        char compile_out[15]={0};
        sprintf(compile_out,"%d",head->runid);
        FILE* check_file=fopen(compile_out,"r");
        if (check_file==NULL&&(head->lang==CPPLANG||head->lang==CLANG||head->lang==FPASLANG||head->lang==FORTLANG||head->lang==SMLLANG)) {
            generate_result(CE_STATUS,head->runid,0,0);
            return;
        }
        if (check_file!=NULL) fclose(check_file);
        sprintf(compile_out,"%d.ali",head->runid);
        check_file=fopen(compile_out,"r");
        if (check_file==NULL&&head->lang==ADALANG) {
            generate_result(CE_STATUS,head->runid,0,0);
            return;
        }
        else if (check_file!=NULL&&(head->lang==ADALANG)) {
            sprintf(compile_command,"gnatbind -x %s 2>%d.txt",compile_out,head->runid);
            system(compile_command);
            sprintf(compile_command,"gnatlink %s 2>%d.txt",compile_out,head->runid);
            system(compile_command);
            sprintf(compile_out,"%d",head->runid);
            fclose(check_file);
            check_file=fopen(compile_out,"r");
            if (check_file==NULL) {
                generate_result(CE_STATUS,head->runid,0,0);
                return;
            }
            fclose(check_file);
        }
        if (check_file!=NULL) fclose(check_file);
        sprintf(compile_out,"%d.exe",head->runid);
        check_file=fopen(compile_out,"r");
        if (check_file==NULL&&head->lang==CSLANG) {
            generate_result(CE_STATUS,head->runid,0,0);
            return;
        }
        if (check_file!=NULL) fclose(check_file);
        sprintf(compile_out,"Main.class");
        check_file=fopen(compile_out,"r");
        if (check_file==NULL&&head->lang==JAVALANG) {
            generate_result(CE_STATUS,head->runid,0,0);
            return;
        }
        if (check_file!=NULL) fclose(check_file);
    }
    pid_t pid;
    int runstat;
    bool aced=true;
    bool peed=true;
    int time_passed=0;
    int total_time=0;
    int mem_used=0;
    bool excuted=false;
    bool spj_compiled=false;
    struct rusage rinfo;
    struct user_regs_struct reg;
    struct rlimit time_limit,output_limit;
    caseResult.clear();
    for (int i=0;i<head->number_of_cases;i++)
    {
        total_time=mem_used=time_passed=0;
        aced=peed=true;

        excuted=false;
        if ((pid=fork())==0) {
            //if (head->time_limit<total_time) total_time=1;
            time_limit.rlim_cur=head->case_limit;
            char templog[400]={0};
            sprintf(templog,"Current total time used: %d\nTime limit for case %d is %d\n",total_time,i,time_limit.rlim_cur);
            writelog(templog);
            time_limit.rlim_cur=(time_limit.rlim_cur+999)/1000;
            if (time_limit.rlim_cur<=0) time_limit.rlim_cur=1;
            time_limit.rlim_max=time_limit.rlim_cur+1;
            setrlimit(RLIMIT_CPU,&time_limit);
            output_limit.rlim_max=output_limit.rlim_cur=200*1024*1024;
            setrlimit(RLIMIT_FSIZE,&output_limit);
            if (head->lang==JAVALANG) {
                run_java_program(head->runid,head->pid,i);
            }
            else if (head->lang==PYLANG) {
                run_python_program(head->runid,head->pid,i);
            }
            else if (head->lang==CSLANG) {
                run_csharp_program(head->runid,head->pid,i);
            }
            else if (head->lang==PERLLANG) {
                run_perl_program(head->runid,head->pid,i);
            }
            else if (head->lang==RUBYLANG) {
                run_ruby_program(head->runid,head->pid,i);
            }
            else {
                run_other_program(head->runid,head->pid,i);
            }
            exit(0);
        }
        else {
            char templog[300]={0};
            sprintf(templog,"Running program, runid: %d, pid: %d, testcase: %d\n",head->runid,head->pid,i);
            writelog(templog);
            runstat=0;
            struct timeval case_startv,case_nowv;
            struct timezone case_startz,case_nowz;
            gettimeofday(&case_startv,&case_startz);
            /*gettimeofday(&case_nowv,&case_nowz);
            int time_passed=(case_nowv.tv_sec-case_startv.tv_sec)*1000+(case_nowv.tv_usec-case_startv.tv_usec)/1000;
            temp_pid=waitpid(-1,&runstat,WNOHANG);
            while (temp_pid!=pid&&time_passed<=head->case_limit&&time_passed<=head->time_limit-total_time) {
                time_passed=(case_nowv.tv_sec-case_startv.tv_sec)*1000+(case_nowv.tv_usec-case_startv.tv_usec)/1000;
                temp_pid=waitpid(-1,&runstat,WNOHANG);
                gettimeofday(&case_nowv,&case_nowz);
            }
            if (temp_pid!=pid) {
                kill(pid,SIGKILL);
                generate_result(TLE_STATUS,head->runid,-1,total_time);
                system(clean_command);
                sprintf(clean_command,"rm %s",code_name);
                system(clean_command);
                sprintf(clean_command,"rm %d %d.ou",head->runid,head->runid);
                system(clean_command);
                if (head->lang==JAVALANG) {
                    sprintf(clean_command,"rm Main.class");
                    system(clean_command);
                }
                exit(1);
            }*/
            bool getResult=false;
            while (!getResult) {
//                wait4(pid,&runstat,WNOHANG,&rinfo);
                wait4(pid,&runstat,NULL,&rinfo);
//                usleep(1000);
//                gettimeofday(&case_nowv,&case_nowz);
                time_passed=(rinfo.ru_utime.tv_sec+rinfo.ru_stime.tv_sec)*1000+(rinfo.ru_utime.tv_usec+rinfo.ru_stime.tv_usec)/1000;
//                int time_passed2=(case_nowv.tv_sec-case_startv.tv_sec)*1000+(case_nowv.tv_usec-case_startv.tv_usec)/1000;
//                if (time_passed2>head->case_limit+5000) {
//                    writelog("ERROR! May used thread sleep or similar!\n");
//                    total_time+=time_passed2-1000;
//                    generate_result(TLE_STATUS,head->runid,mem_used,total_time);
                    //ptrace(PTRACE_KILL,pid,NULL,NULL);
//                    sprintf(templog,"kill %d",pid);
//                    system(templog);
//                    return;
//                }
                if (head->case_limit<total_time+time_passed) {
                    ptrace(PTRACE_KILL,pid,NULL,NULL);
                    generate_case_result(TLE_STATUS,head->runid,mem_used,total_time+time_passed);
                    getResult=true;
                    break;
                }
                if (mem_used<getpagesize()*rinfo.ru_minflt) mem_used=getpagesize()*rinfo.ru_minflt;
                if (WIFEXITED(runstat)) {
                    sprintf(templog,"Used time for this case: %d\n",time_passed);
                    writelog(templog);
                    sprintf(templog,"Run status: %d\n",runstat);
                    writelog(templog);
                    break;
                }
                if (WIFSIGNALED(runstat)&&WTERMSIG(runstat)!=SIGTRAP) {
                    int signal=WTERMSIG(runstat);
                    total_time+=time_passed;
                    sprintf(templog,"Used time for this case: %d\n",time_passed);
                    writelog(templog);
                    sprintf(templog,"Run status: %d\n",runstat);
                    writelog(templog);
                    switch (signal)
                    {
                        case SIGXCPU:
                            generate_case_result(TLE_STATUS,head->runid,mem_used,total_time);
                            break;
                        case SIGXFSZ:
                            generate_case_result(OLE_STATUS,head->runid,mem_used,total_time);
                            break;
                        default:
                            generate_case_result(RE_STATUS,head->runid,mem_used,total_time);
                    }
                    ptrace(PTRACE_KILL,pid,NULL,NULL);
                    break;
                    //return;
                }
                else if (WIFSTOPPED(runstat)&&WSTOPSIG(runstat)!=SIGTRAP) {
                    int signal=WSTOPSIG(runstat);
                    total_time+=time_passed;
                    sprintf(templog,"Used time for this case: %d\n",time_passed);
                    writelog(templog);
                    sprintf(templog,"Run status: %d\n",runstat);
                    writelog(templog);
                    switch (signal)
                    {
                        case SIGXCPU:
                            generate_case_result(TLE_STATUS,head->runid,mem_used,total_time);
                            break;
                        case SIGXFSZ:
                            generate_case_result(OLE_STATUS,head->runid,mem_used,total_time);
                            break;
                        default:
                            generate_case_result(RE_STATUS,head->runid,mem_used,total_time);
                    }
                    ptrace(PTRACE_KILL,pid,NULL,NULL);
                    getResult=true;
                    break;
                    //return;
                }
                else if ((runstat>>8)!=5&&(runstat>>8)>0) {
                    total_time+=time_passed;
                    sprintf(templog,"Used time for this case: %d\n",time_passed);
                    writelog(templog);
                    sprintf(templog,"Run status: %d\n",runstat);
                    writelog(templog);
                    generate_case_result(RE_STATUS,head->runid,mem_used,total_time);
                    ptrace(PTRACE_KILL,pid,NULL,NULL);
                    getResult=true;
                    break;
                    //return;
                }
                ptrace(PTRACE_GETREGS,pid,NULL,&reg);
                #ifdef __i386__
                //printf("System call:%ld\n",reg.orig_eax);
                if (reg.orig_eax==SYS_execve&&!excuted) excuted=true;
                else {
                    if (head->lang==JAVALANG) {
                        if (syscalls_java[reg.orig_eax]) {
                            ptrace(PTRACE_KILL,pid,NULL,NULL);
                            sprintf(templog,"Invalid system call:%ld\n",reg.orig_eax);
                            writelog(templog);
                            generate_case_result(RF_STATUS,head->runid,mem_used,total_time);
                            getResult=true;
                            break;
                        }
                    }
                    else if (head->lang==CSLANG) {
                        if (syscalls_csharp[reg.orig_eax]) {
                            ptrace(PTRACE_KILL,pid,NULL,NULL);
                            sprintf(templog,"Invalid system call:%ld\n",reg.orig_eax);
                            writelog(templog);
                            generate_case_result(RF_STATUS,head->runid,mem_used,total_time);
                            break;
                        }
                    }
                    else if (syscalls_other[reg.orig_eax]) {
                        ptrace(PTRACE_KILL,pid,NULL,NULL);
                        sprintf(templog,"Invalid system call:%ld\n",reg.orig_eax);
                        writelog(templog);
                        generate_case_result(RF_STATUS,head->runid,mem_used,total_time);
                        break;
                    }
                }
                #else
                //printf("System call:%ld\n",reg.orig_rax);
                if (reg.orig_rax==SYS_execve&&!excuted) excuted=true;
                else {
                    if (head->lang==JAVALANG) {
                        if (syscalls_java[reg.orig_rax]) {
                            ptrace(PTRACE_KILL,pid,NULL,NULL);
                            sprintf(templog,"Invalid system call:%ld\n",reg.orig_rax);
                            writelog(templog);
                            generate_case_result(RF_STATUS,head->runid,mem_used,total_time);
                            break;
                        }
                    }
                    else if (head->lang==CSLANG) {
                        if (syscalls_csharp[reg.orig_rax]) {
                            ptrace(PTRACE_KILL,pid,NULL,NULL);
                            sprintf(templog,"Invalid system call:%ld\n",reg.orig_rax);
                            writelog(templog);
                            generate_case_result(RF_STATUS,head->runid,mem_used,total_time);
                            break;
                        }
                    }
                    else if (syscalls_other[reg.orig_rax]) {
                        ptrace(PTRACE_KILL,pid,NULL,NULL);
                        sprintf(templog,"Invalid system call:%ld\n",reg.orig_rax);
                        writelog(templog);
                        generate_case_result(RF_STATUS,head->runid,mem_used,total_time);
                        break;
                    }
                }
                #endif
                if (mem_used>head->memory_limit*1024) {
                    sprintf(templog,"Used time for this case: %d\n",time_passed);
                    writelog(templog);
                    sprintf(templog,"Run status: %d\n",runstat);
                    writelog(templog);
                    generate_case_result(MLE_STATUS,head->runid,mem_used,total_time);
                    ptrace(PTRACE_KILL,pid,NULL,NULL);
                    //return;
                }
                ptrace(PTRACE_SYSCALL,pid,NULL,NULL);
            }
            if (getResult) continue;
            total_time+=time_passed;
            if (head->case_limit<total_time) {
                generate_case_result(TLE_STATUS,head->runid,mem_used,head->time_limit);
                getResult=true;
                break;
            }
            writelog("Comparing results...\n");
            if (head->special_judge_status==0) {
                FILE *program_out,*standard_out;
                char po_file[50]={0};
                char so_file[50]={0};
                int eofp=EOF,eofs=EOF;
                sprintf(so_file,"testdata/%d/%d.out",head->pid,i);
                sprintf(po_file,"%d.ou",head->runid);
                program_out=fopen(po_file,"r");
                standard_out=fopen(so_file,"r");
                char po_char,so_char;
                while (1) {
                    while((eofs=fscanf(standard_out,"%c",&so_char))!=EOF&&so_char=='\r');
                    while((eofp=fscanf(program_out,"%c",&po_char))!=EOF&&po_char=='\r');
                    if (eofs==EOF||eofp==EOF) break;
                    if (so_char!=po_char) {
                        sprintf(templog,"Expected %c, got %c. Not Acceptable.\n",so_char,po_char);
                        writelog(templog);
                        aced=false;
                        break;
                    }
                }
                while((so_char=='\n'||so_char=='\r')&&((eofs=fscanf(standard_out,"%c",&so_char))!=EOF));
                while((po_char=='\n'||po_char=='\r')&&((eofp=fscanf(program_out,"%c",&po_char))!=EOF));
                if (eofp!=eofs) aced=false;
                fclose(program_out);
                fclose(standard_out);
                //if (aced) continue;
                program_out=fopen(po_file,"r");
                standard_out=fopen(so_file,"r");
                while (!aced) {
                    while((eofs=fscanf(standard_out,"%c",&so_char))!=EOF&&(so_char==' '||so_char=='\n'||so_char=='\r'));
                    while((eofp=fscanf(program_out,"%c",&po_char))!=EOF&&(po_char==' '||po_char=='\n'||po_char=='\r'));
                    if (eofs==EOF||eofp==EOF) break;
                    if (so_char!=po_char) {
                        sprintf(templog,"Expected %c, got %c. Not PE.\n",so_char,po_char);
                        writelog(templog);
                        peed=false;
                        break;
                    }
                }
                while((so_char==' '||so_char=='\n'||so_char=='\r')&&((eofs=fscanf(standard_out,"%c",&so_char))!=EOF));
                while((po_char==' '||po_char=='\n'||po_char=='\r')&&((eofp=fscanf(program_out,"%c",&po_char))!=EOF));
                if (eofp!=eofs) {
                    peed=false;
                    sprintf(templog,"EOF status, user:%d, std:%d, lastchar: user:%c, std:%c\n",eofp,eofs,po_char,so_char);
                    writelog(templog);
                }
                fclose(program_out);
                fclose(standard_out);
            }
            else {
                int spjpid;
                struct rlimit spjtime;
                char spjprog[100]={0};
                char spjcase[10]={0};
                char userout[10]={0};
                peed=false;
                sprintf(spjcase,"%d",i);
                sprintf(userout,"%d",head->runid);
                if (!spj_compiled) {
                    writelog("Need special judge, compiling...\n");
                    char spj_path[100]={0};
                    sprintf(spj_path,"spj/%d.cpp",head->pid);
                    FILE * spj_file=fopen(spj_path,"r");
                    if (spj_file==NULL) {
                        writelog("Cannot find special judger.\n");
                        generate_result(NJ_STATUS,head->runid,0,0);
                        return;
                    }
                    char compile_spj[200]={0};
                    sprintf(compile_spj,"g++ %s -o %dspj",spj_path,head->runid);
                    system(compile_spj);
                    spj_compiled=true;
                    writelog("Compiled\n");
                }
                sprintf(spjprog,"./%dspj",head->runid);
                writelog("Running special judge to generate result...\n");
                if ((spjpid=fork())==0) {
                    spjtime.rlim_cur=60;
                    spjtime.rlim_max=spjtime.rlim_cur+1;
                    setrlimit(RLIMIT_CPU,&spjtime);
                    freopen(logfile,"a",stdout);
                    execl(spjprog,spjprog,spjcase,userout,NULL);
                    exit(0);
                }
                else {
                    int spjstat=0;
                    waitpid(spjpid,&spjstat,0);
                    sprintf(templog,"Special judge returned, status:%d\n",spjstat);
                    writelog(templog);
                    if (WIFEXITED(spjstat)) {
                        if (WEXITSTATUS(spjstat)!=0) {
                            aced=peed=false;
                            //break;
                        }
                    }
                    else {
                        aced=peed=false;
                        //break;
                    }
                }
            }
            //writelog("Compared result.\n");
            //if (!aced&&!peed) break;
            if  (!aced&&!peed) {
                generate_case_result(WA_STATUS,head->runid,mem_used,total_time);
            } else {
                if (aced) generate_case_result(AC_STATUS,head->runid,mem_used,total_time);
                else if (peed) generate_case_result(PE_STATUS,head->runid,mem_used,total_time);
            }
        }
    }
    /*if (aced) generate_result(AC_STATUS,head->runid,mem_used,total_time);
    else if (peed) generate_result(PE_STATUS,head->runid,mem_used,total_time);
    else generate_result(WA_STATUS,head->runid,mem_used,total_time);*/
    summary_case_result(head->runid);
    return;
}

void send_register_info()
{
    write(sockfd,judger_string,sizeof(judger_string));
    //sleep(1);
}

void reconnect()
{
    writelog("ERROR in network connection!\n");
    writelog("Reconnecting...\n");
    close(sockfd);
    sockfd=socket(AF_INET,SOCK_STREAM,0);
    while (connect(sockfd,(struct sockaddr*)&server,sizeof(server))==-1) sleep(1);
    send_register_info();
    writelog("Successfully reconnected.\n");
    sleep(1);
}

int main(int argc, char *argv[])
{
    int port_number;
    init_error();
    init();
    char templog[3000]={0};
    mkdir("results",0777);
    head=tail=NULL;
    if (argc!=3) {
        printf("Illegal call!\nPlease follow the format below!\n%s <IP address> <port>\n",argv[0]);
        exit(1);
    }
    if ((sockfd=socket(AF_INET,SOCK_STREAM,0))==-1) {
        writelog("socket() error!\n");
        exit(1);
    }
    bzero(&server,sizeof(server));
    port_number=atoi(argv[2]);
    //port_number=5566;
    server.sin_family=AF_INET;
    server.sin_port=htons(port_number);
    server.sin_addr.s_addr=inet_addr(argv[1]);
    //server.sin_addr.s_addr=inet_addr("127.0.0.1");
    if (connect(sockfd,(struct sockaddr*)&server,sizeof(server))==-1)
    {
        writelog("connect() error!\n");
        exit(1);
    }
    writelog("Connected!\n");
    send_register_info();
    FILE *target_file;
    while (1)
    {
        target_file=fopen("temp.bott","w");
        usleep(5000);
        got_things=false;
        while ((num=recv(sockfd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT))>0)
        {
            got_things=true;
            fputs(buffer,target_file);
        }
        fclose(target_file);
        if (num==0) reconnect();
        if (got_things) {
            writelog("Received a command.\n\n");
            convert();
        }
        if (head!=NULL) {
            FILE * check_file=NULL;
            char case_path[50]={0};
            for (int i=0;i<head->number_of_cases;i++) {
                sprintf(case_path,"testdata/%d/%d.in",tail->pid,i);
                writelog("Checking Data, path: ");
                writelog(case_path);
                writelog("\n");
                check_file=NULL;
                check_file=fopen(case_path,"r");
                if (check_file!=NULL) fclose(check_file);
                //check_file=NULL;
                sprintf(case_path,"testdata/%d/%d.out",tail->pid,i);
                writelog("Checking Data, path: ");
                writelog(case_path);
                writelog("\n");
                if (check_file==NULL||(check_file=fopen(case_path,"r"))==NULL) {
                    /*printf("Sending message, need data for pid:%d\n",head->pid);
                    FILE *tmp_sender=fopen("need_data.bott","w");
                    fprintf(tmp_sender,"%d\n%d\n",NEED_DATA,head->pid);
                    fclose(tmp_sender);
                    int source=open("need_data.bott",O_RDONLY);
                    memset(buffer,0,sizeof(buffer));
                    while((num=read(source,buffer,sizeof(buffer)))>0) write(sockfd,buffer,num);
                   // write(sockfd,"__BOTT_FILE_OVER_LABLE__",24);
                    printf("Finished.\n\n");*/

                    /*while(write(sockfd,NEED_DATA,sizeof(NEED_DATA))==-1);
                    char tempstr[10];
                    memset(tempstr,0,sizeof(tempstr));
                    sprintf(tempstr,"%d",head->pid);
                    while(write(sockfd,tempstr,sizeof(tempstr))==-1);
                    while(write(sockfd,PACK_OVER_SIGN,sizeof(PACK_OVER_SIGN))==-1);*/
                    sprintf(templog,"No testdata for pid: %d.\n",head->pid);
                    writelog(templog);
                    if (check_file!=NULL) fclose(check_file);
                    break;
                }
                if (check_file!=NULL) fclose(check_file);
            }
            if (check_file==NULL) {
                queuesize--;
                generate_result(ND_STATUS,head->runid,0,0);
                sprintf(templog,"Sending result of runid:%d\n",head->runid);
                writelog(templog);
                int source;
                char temp_name[200]={0};
                sprintf(temp_name,"results/%d.bott",head->runid);
                source=open(temp_name,O_RDONLY);
                memset(buffer,0,sizeof(buffer));
                while((num=read(source,buffer,sizeof(buffer)))>0)
                    write(sockfd,buffer,num);
                close(source);
                writelog("Sent.\n\n");
                head=head->next;
                if (head==NULL) tail=NULL;
                continue;
            }
            queuesize--;
            for (int i=0;i<MAX_JUDGER_NUMBER;i++)
            {
                if (head==NULL) break;
                if (subjudger_status[i]==false) {
                    subjudger_status[i]=true;
                    subjudger_runid[i]=head->runid;
                    subjudger_st[i]=time(NULL);
                    if (head->lang==JAVALANG) subjudger_limit[i]=head->case_limit*head->number_of_cases*3+30000;
                    else subjudger_limit[i]=head->time_limit*head->number_of_cases+30000;
                    if ((subjudger_id[i]=fork())==-1) {
                        writelog("fork() failed!\n");
                        queuesize++;
                    }
                    else if (subjudger_id[i]==0) {
                        sprintf(templog,"Judging: runid:%d  pid:%d  lang:%d\n",head->runid,head->pid,head->lang);
                        writelog(templog);
                        subjudger();
                        writelog("Judged.\n\n");
                        exit(1);
                    }
                    else {
                        temp=head;
                        if (head==tail) tail=tail->next;
                        head=head->next;
                        free(temp);
                    }
                }
            }
        }
        pid_t pid_terminated;
        int final_state;
        pid_terminated=waitpid(-1,&final_state,WNOHANG);
        //if (pid_terminated==0) continue;
        int now_time=time(NULL);
        for (int i=0;i<MAX_JUDGER_NUMBER;i++)
        {
            if (subjudger_status[i]&&pid_terminated==subjudger_id[i]) {
                sprintf(templog,"Sending result of runid:%d\n",subjudger_runid[i]);
                writelog(templog);
                subjudger_status[i]=false;
                int source;
                char temp_name[200]={0};
                sprintf(temp_name,"results/%d.bott",subjudger_runid[i]);
                source=open(temp_name,O_RDONLY);
                memset(buffer,0,sizeof(buffer));
                while((num=read(source,buffer,sizeof(buffer)))>0)
                    write(sockfd,buffer,num);
                close(source);
               // write(sockfd,"__BOTT_FILE_OVER_LABLE__",24);
                writelog("Sent.\n\n");
            }
            else if (subjudger_status[i]&&now_time-subjudger_st[i]>subjudger_limit[i]/1000) {
                sprintf(templog,"kill %d",subjudger_id[i]);
                system(templog);
                generate_result(RF_STATUS,subjudger_runid[i],0,0);
                writelog("Something goes wrong with this run.\n");
                sprintf(templog,"Sending result of runid:%d\n",subjudger_runid[i]);
                writelog(templog);
                subjudger_status[i]=false;
                int source;
                char temp_name[200]={0};
                sprintf(temp_name,"results/%d.bott",subjudger_runid[i]);
                source=open(temp_name,O_RDONLY);
                memset(buffer,0,sizeof(buffer));
                while((num=read(source,buffer,sizeof(buffer)))>0)
                    write(sockfd,buffer,num);
                close(source);
                //write(sockfd,"__BOTT_FILE_OVER_LABLE__",24);
                writelog("Sent.\n\n");
            }
        }
    }
    return 0;
}
