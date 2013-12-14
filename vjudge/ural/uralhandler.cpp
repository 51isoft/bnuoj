#include "uralhandler.h"

CURL *curl;
CURLcode res;
CURLM *multi_handle;
int still_running;
struct curl_httppost *formpost=NULL;
struct curl_httppost *lastptr=NULL;
struct curl_slist *headerlist=NULL;
char tmps[1000010];

string getAllFromFile(char *filename)
{
    string res="";
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) res+=tmps;
    fclose(fp);
    return res;
}

string getResFromFile(char *filename)
{
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    fgets(tmps,1000000,fp);
    ts=tmps;
    fclose(fp);
    int st=ts.find("BNU Vjudge</A>");
    st=ts.rfind("<TR",st);
    int pos=ts.find("</TR>",st);
    return ts.substr(st,pos-st);
}

string covert(int x)
{
    char tt[100];
    sprintf(tt,"%d",x);
    string t=tt;
    return t;
}

bool submit(string pid,string lang,string source)
{
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    static const char buf[] = "Expect:";
    formpost=NULL;
    lastptr=NULL;
    headerlist=NULL;
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "Action",
                 CURLFORM_COPYCONTENTS, "submit",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "spaceID",
                 CURLFORM_COPYCONTENTS, "1",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "JudgeID",
                 CURLFORM_COPYCONTENTS, username,
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "Language",
                 CURLFORM_COPYCONTENTS, lang.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "ProblemNum",
                 CURLFORM_COPYCONTENTS, pid.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "Source",
                 CURLFORM_COPYCONTENTS, source.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "SourceFile",
                 CURLFORM_FILE, "emptyfile.txt",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "SourceFile",
                 CURLFORM_FILENAME, "",
                 CURLFORM_END);


    curl = curl_easy_init();

    headerlist = curl_slist_append(headerlist, buf);
    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_URL, "http://acm.timus.ru/submit.aspx?space=1");
        curl_easy_setopt(curl, CURLOPT_HTTPPOST, formpost);
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "ural.cookie");
        curl_easy_perform(curl);
        curl_easy_cleanup(curl);
        curl_formfree(formpost);
        curl_slist_free_all (headerlist);

        fclose(fp);

        string ts=getAllFromFile(tfilename);
        //cout<<ts;
        if (ts.find("Object moved to <a href=\"/status.aspx?space=1\">here</a>.")==string::npos) return false;
    }
    return true;
}


string getResult(string s)
{
    int pos=s.find("<TD class=\"verdict_");
    while (s[pos]!='>') pos++;
    pos++;
    if (s[pos]=='<') {
        while (s[pos]!='>') pos++;
        pos++;
    }
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

string getRunid(string s)
{
    int pos=s.find("<TD class=\"id\">");
    while (s[pos]!='>') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

char tempce[MAX_DATA_SIZE];

string getCEinfo(string runid)
{
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "ural.cookie");
        string url=(string)"http://acm.timus.ru/ce.aspx?id="+runid;
        //cout<<post;
        //cout<<url;
        curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);

    string s=getAllFromFile(tfilename);

    return s;
}

string getUsedTime(string s) {
    int pos=s.find("<TD class=\"runtime\">");
    int st=pos;
    while (s[st]!='>') st++;
    pos=st;
    st++;
    while (s[pos]!='<') pos++;
    string t=s.substr(st,pos-st);
    double r;
    sscanf(t.c_str(),"%lf",&r);
    return covert((int)(r*1000+0.01));
}

string getUsedMem(string s) {
    int pos=s.find("<TD class=\"memory\">");
    int st=pos;
    while (s[st]!='>') st++;
    pos=st;
    st++;
    while (s[pos]!='K') pos++;
    string t=s.substr(st,pos-st);
    int r;
    while ((r=t.find(' '))!=string::npos) {
        t=t.substr(0,r)+t.substr(r+1);
    }
    sscanf(t.c_str(),"%d",&r);
    return covert(r);
}

string convertResult(string res) {
    if (res.find("Compilation error")!=string::npos) return "Compile Error";
    else if (res.find("Wrong answer")!=string::npos) return "Wrong Answer";
    else if (res.find("Time limit exceeded")!=string::npos) return "Time Limit Exceed";
    else if (res.find("Memory limit exceeded")!=string::npos) return "Memory Limit Exceed";
    else if (res.find("Output limit exceeded")!=string::npos) return "Output Limit Exceed";
    else if (res.find("Crash")!=string::npos) return "Runtime Error";
    return res;
}


bool getStatus(string pid,string lang,string & result,string& ce_info,string &tu,string &mu)
{
    int begin=time(NULL);
    string runid;
    tu=mu="0";
    string ts;
    while (true)
    {
        FILE * fp=fopen(tfilename,"w+");
        curl = curl_easy_init();
        if(curl)
        {
            curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
            curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
            curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "ural.cookie");
            string url=(string)"http://acm.timus.ru/status.aspx?count=30";
            //cout<<url;
            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            res = curl_easy_perform(curl);
            curl_easy_cleanup(curl);
        }
        fclose(fp);
        if (res) return false;

        ts=getResFromFile(tfilename);
        cout << ts;
        if (ts=="") return false;
        writelog((char *)ts.c_str());
        runid=getRunid(ts);
        result=getResult(ts);
        //cout << result;
        if (result.find("Waiting")==string::npos
                &&result.find("Running")==string::npos
                &&result.find("Judging")==string::npos
                &&result.find("Queuing")==string::npos
                &&result.find("Compiling")==string::npos)
        {
            break;
        }
        if (time(NULL)-begin>MAX_WAIT_TIME) break;
    }
    if (!(result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Judging")==string::npos
            &&result.find("Queuing")==string::npos
            &&result.find("Compiling")==string::npos)) return false;
    //cout<<runid<<" "<<tu<<" "<<mu<<" "<<result<<endl;
    if (result=="Compilation error") ce_info=getCEinfo(runid);
    else {
        tu=getUsedTime(ts);
        mu=getUsedMem(ts);
        ce_info="";
    }
    //cout<<ce_info;
    result=convertResult(result);
    return true;
}

/*
<type> 3
<runid> 35533
<memory_used> 0
<time_used> 0
<result> Wrong Answer
__COMPILE-INFO-BEGIN-LABLE__

__COMPILE-INFO-END-LABLE__
*/

void toBottFile(string runid,string tu,string mu,string result,string ce_info)
{
    FILE * fp=fopen(tfilename,"w+");
    fputs("<type> 3\n",fp);
    fprintf(fp,"<runid> %s\n",runid.c_str());
    fprintf(fp,"<memory_used> %s\n",mu.c_str());
    fprintf(fp,"<time_used> %s\n",tu.c_str());
    fprintf(fp,"<result> %s\n",result.c_str());
    fputs("__COMPILE-INFO-BEGIN-LABLE__\n",fp);
    fputs(ce_info.c_str(),fp);
    fputs("\n__COMPILE-INFO-END-LABLE__\n",fp);
    fclose(fp);
}

void judge(string pid,string lang,string runid,string src)
{
    if (src.length()<15)
    {
        toBottFile(runid,"0","0","Compile Error","");
        return;
    }
    /*if (!login())
    {
        //writelog("Login error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    }*/
    //writelog("Logged In.\n");
    lang=corrlang[lang];
    //return;
    if (!submit(pid,lang,src))
    {
        writelog("Submit error! Sleep 4 seconds.\n");
        usleep(4000000);
        if (!submit(pid,lang,src)) {
            writelog("Submit error!\n");
            toBottFile(runid,"0","0","Judge Error","");
            return;
        }
    }
    writelog("Submitted.\n");
    //return;
    string result,ce_info,tu,mu;
    if (!getStatus(pid,lang,result,ce_info,tu,mu)) {
        writelog("Get Error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    };
    writelog("Got.\n");
    toBottFile(runid,tu,mu,result,ce_info);
}
/*
int main() {
    init();
    judge("1000","1","111","#include<stdio.h>\nint a,b;\nint main() {\n scanf(\"%d%d\",&a,&b);\nprintf(\"%d\\n\",a+b);\n}\n");
    return 0;
}
*/

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
    char vid[300];
    char vname[300];
};

int num;
int target;
char buffer[MAX_DATA_SIZE];
bool got_things;
int sockfd;
struct sockaddr_in server;
Judger_data temp;

void send_register_info()
{
    write(sockfd,judger_string,sizeof(judger_string));
    //puts(judger_string);
    //sleep(1);
}

void writelog(char* log)
{
    FILE * fp=fopen(logfile,"a");
    if (fp!=NULL) {
        fprintf(fp,"%s",log);
        fclose(fp);
    }
}

void reconnect()
{
    //printf("REcon\n");
    writelog("ERROR in network connection!\n");
    writelog("Reconnecting...\n");
    close(sockfd);
    sockfd=socket(AF_INET,SOCK_STREAM,0);
    while (connect(sockfd,(struct sockaddr*)&server,sizeof(server))==-1) sleep(1);
    send_register_info();
    writelog("Successfully reconnected.\n");
    sleep(1);
}

void convert()
{
    FILE *server_offer=fopen("temp.bott","r");
    int offer_type;
    char type_str[50];
    fscanf(server_offer,"%s %d\n",type_str,&offer_type);
    if (offer_type==NEED_JUDGE) {
        writelog("Received a judge request, ");
        fgets(buffer,MAX_DATA_SIZE,server_offer);
        memset(temp.src,0,sizeof(temp.src));
        while (fgets(buffer,MAX_DATA_SIZE,server_offer)&&strcmp(buffer,"__SOURCE-CODE-END-LABLE__\n")!=0)
            strcat(temp.src,buffer);
        char ts[20][50];
        fscanf(server_offer,"%s%d%s%d%s%d%s%d%s%d%s%d%s%d%s%d%*s%s%*s%s",ts[0],&temp.runid,ts[1],&temp.lang,ts[2],
            &temp.pid,ts[3],&temp.number_of_cases,ts[4],&temp.time_limit,ts[5],&temp.case_limit,ts[6],
            &temp.memory_limit,ts[7],&temp.special_judge_status,temp.vname,temp.vid);
        fclose(server_offer);
        char templog[1000]={0};
        sprintf(templog,"runid:%d\n",temp.runid);
        writelog(templog);
        return;
    }
}

int main(int argc, char *argv[])
{
    int port_number;
    if (argc!=3) {
        printf("Illegal call!\nPlease follow the format below!\n%s <IP address> <port>\n",argv[0]);
        exit(1);
    }

    init();
    curl_global_init(CURL_GLOBAL_ALL);

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
    //return 0;
    char templog[3000]={0};
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
        //printf("%d\n",num);
        //writelog("test");
        if (got_things) {
            writelog("Received a command.\n\n");
            convert();
            //void judge(string pid,string lang,string runid)
            judge(temp.vid,covert(temp.lang),covert(temp.runid),temp.src);
            sprintf(templog,"Sending result of runid:%d\n",temp.runid);
            writelog(templog);
            int source=open(tfilename,O_RDONLY);
            memset(buffer,0,sizeof(buffer));
            while((num=read(source,buffer,sizeof(buffer)))>0) {
                write(sockfd,buffer,num);
                //printf("%s\n",buffer);
            }
            close(source);
            // write(sockfd,"__BOTT_FILE_OVER_LABLE__",24);
            writelog("Sent.\n\n");
        }
    }

    return 0;
}

