#include "spojhandler.h"

CURL *curl;
CURLcode res;
CURLM *multi_handle;
int still_running;
struct curl_httppost *formpost=NULL;
struct curl_httppost *lastptr=NULL;
struct curl_slist *headerlist=NULL;
char tmps[1000000];

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
    while (fgets(tmps,1000000,fp))
    {
        ts=tmps;
        if (ts.find("<tr class=\"kol")!=string::npos)
        {
            res=tmps;
            while (1) {
                fgets(tmps,1000000,fp);
                ts=tmps;
                if (ts.find("</tr>")!=string::npos) break;
                else res=res+ts;
            }
            break;
        }
    }
    fclose(fp);
    return res;
}

string covert(int x)
{
    char tt[100];
    sprintf(tt,"%d",x);
    string t=tt;
    return t;
}


bool login()
{
    /*curl = curl_easy_init();
    curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "spoj.cookie");
    curl_easy_setopt(curl, CURLOPT_URL, "http://www.spoj.com/");
    res = curl_easy_perform(curl);
    curl_easy_cleanup(curl);*/

    FILE * fp=fopen(tfilename,"w+");
    //cout<<tfilename<<endl;i
    curl = curl_easy_init();
    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_REFERER, "http://www.spoj.com/logout");
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "spoj.cookie");
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "spoj.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://www.spoj.com/logout");
        string post=(string)"login_user="+username+"&password="+password+"&submit=Log+In";
        //cout<<post;
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        res = curl_easy_perform(curl);

//        print_cookies(curl);

        curl_easy_cleanup(curl);
    }
    fclose(fp);

    if (res) return false;
    string ts=getAllFromFile(tfilename);
    //cout<<ts;
    if (ts.find("Authentication failed! <br/><a href=\"/forgot\">")!=string::npos) return false;
    return true;
}

int submit(string pid,string lang,string source)
{
    FILE * fp=fopen(tfilename,"w+");
    //curl = curl_easy_init();
    static const char buf[] = "Expect:";
    string cid;
    string pname;
    int loc=0;
    while (pid[loc]>='0'&&pid[loc]<='9') loc++;
    formpost=NULL;
    lastptr=NULL;
    headerlist=NULL;

    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "submit",
                 CURLFORM_COPYCONTENTS, "Send",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "problemcode",
                 CURLFORM_COPYCONTENTS, pid.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "lang",
                 CURLFORM_COPYCONTENTS, lang.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "file",
                 CURLFORM_COPYCONTENTS, source.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "subm_file",
                 CURLFORM_FILE, "emptyfile.txt",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "subm_file",
                 CURLFORM_FILENAME, "",
                 CURLFORM_END);


    curl = curl_easy_init();
    multi_handle = curl_multi_init();

    headerlist = curl_slist_append(headerlist, buf);

    if(curl && multi_handle)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_URL, "http://www.spoj.com/submit/complete/");
        curl_easy_setopt(curl, CURLOPT_HTTPPOST, formpost);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "spoj.cookie");
        curl_multi_add_handle(multi_handle, curl);
        curl_multi_perform(multi_handle, &still_running);
        while(still_running)
        {
            struct timeval timeout;
            int rc;

            fd_set fdread;
            fd_set fdwrite;
            fd_set fdexcep;
            int maxfd = -1;

            long curl_timeo = -1;

            FD_ZERO(&fdread);
            FD_ZERO(&fdwrite);
            FD_ZERO(&fdexcep);

            timeout.tv_sec = 1;
            timeout.tv_usec = 0;

            curl_multi_timeout(multi_handle, &curl_timeo);
            if(curl_timeo >= 0)
            {
                timeout.tv_sec = curl_timeo / 1000;
                if(timeout.tv_sec > 1)
                    timeout.tv_sec = 1;
                else
                    timeout.tv_usec = (curl_timeo % 1000) * 1000;
            }

            curl_multi_fdset(multi_handle, &fdread, &fdwrite, &fdexcep, &maxfd);

            rc = select(maxfd+1, &fdread, &fdwrite, &fdexcep, &timeout);

            switch(rc)
            {
            case -1:
                break;
            case 0:
            default:
                //printf("perform!\n");
                curl_multi_perform(multi_handle, &still_running);
                //printf("running: %d!\n", still_running);
                break;
            }
        }

        curl_multi_cleanup(multi_handle);
        curl_easy_cleanup(curl);
        curl_formfree(formpost);
        curl_slist_free_all (headerlist);

        fclose(fp);

        string ts=getAllFromFile(tfilename);
        cout<<"===sub===\n"<<ts<<"\n=====\n"<<endl;
//        writelog((char *)ts.c_str());
        if (ts.find("in this language for this problem")!=string::npos) return 2;
    }
    return 0;
}


string getResult(string s)
{
    int pos=s.find("statusres_");
    while (s[pos]!='>') pos++;
    pos++;
    while (s[pos]==' '||s[pos]=='\t'||s[pos]=='\r'||s[pos]=='\n') pos++;
    int st=pos;
    pos=s.find("<br/>",st);
    return s.substr(st,pos-st);
}

string getRunid(string s)
{
    int pos=s.find("statusres_");
    while (s[pos]!='_') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='\"') pos++;
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
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "spoj.cookie");
        string url=(string)"http://www.spoj.com/error/"+runid;
        curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);

    string info=getAllFromFile(tfilename);
    int st=info.find("<small>");
    while (info[st]!='>') st++;
    st++;
    int ed=info.find("</small>");
    return info.substr(st,ed-st);
}

string getUsedTime(string s)
{
    int pos=s.find("See the best solutions");
    while (s[pos]!='>') pos++;
    pos++;
    while (s[pos]==' '||s[pos]=='\t'||s[pos]=='\r'||s[pos]=='\n') pos++;
    int st=pos;
    while (s[pos]!=' '&&s[pos]!='\t'&&s[pos]!='\r'&&s[pos]!='\n') pos++;
    string ts=s.substr(st,pos-st);
    double tt;
    sscanf(ts.c_str(),"%lf",&tt);
    return covert((int)(tt*1000+0.01));
}

string getUsedMem(string s)
{
    int pos=s.find("statusmem_");
    while (s[pos]!='>') pos++;
    pos++;
    while (s[pos]==' '||s[pos]=='\t'||s[pos]=='\r'||s[pos]=='\n') pos++;
    int st=s.find("M",pos);
    string ts=s.substr(pos,st-pos);
    double tt;
    sscanf(ts.c_str(),"%lf",&tt);
    return covert((int)(tt*1024+0.01));
}

string convertResult(string res) {
    if (res.find("compilation error")!=string::npos) return "Compile Error";
    if (res.find("wrong answer")!=string::npos) return "Wrong Answer";
    if (res.find("SIGXFSZ")!=string::npos) return "Output Limit Exceed";
    if (res.find("runtime error")!=string::npos) return "Runtime Error";
    if (res.find("time limit exceeded")!=string::npos) return "Time Limit Exceed";
    if (res.find("memory limit exceeded")!=string::npos) return "Memory Limit Exceed";
    if (res.find("SIGABRT")!=string::npos) return "Memory Limit Exceed";
    if (res.find("accepted")!=string::npos) return "Accepted";
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
            curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "spoj.cookie");
            string url=(string)"http://www.spoj.com/status/"+username+"/";
            //cout<<url;
            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            res = curl_easy_perform(curl);
            curl_easy_cleanup(curl);
        }
        fclose(fp);
        //return false;
        if (res) {
            if (time(NULL)-begin>MAX_WAIT_TIME) return false;
            else continue;
        }

        ts=getResFromFile(tfilename);
        if (ts=="") {
            if (time(NULL)-begin>MAX_WAIT_TIME) return false;
            else continue;
        }
        cout<<"=======\n"<<ts<<"\n=========\n";
        //writelog((char *)ts.c_str());
        runid=getRunid(ts);
        result=getResult(ts);
        writelog((char *)result.c_str());
        if (result.find("waiting")==string::npos
                &&result.find("Waiting")==string::npos
                &&result.find("running")==string::npos
                &&result.find("judging")==string::npos
                &&result.find("queue")==string::npos
                &&result.find("compiling")==string::npos
                &&result.length()>6)
        {
            break;
        }
        if (time(NULL)-begin>MAX_WAIT_TIME) break;
    }
    if (!(result.find("waiting")==string::npos
            &&result.find("Waiting")==string::npos
            &&result.find("running")==string::npos
            &&result.find("judging")==string::npos
            &&result.find("queue")==string::npos
            &&result.find("compiling")==string::npos
            &&result.length()>6)) return false;
    //cout<<runid<<" "<<tu<<" "<<mu<<" "<<result<<endl;
    result=convertResult(result);
    if (result=="Compile Error") ce_info=getCEinfo(runid);
    else ce_info="";
    if (result=="Accepted"||result=="Runtime Error"||result=="Wrong Answer") {
        tu=getUsedTime(ts);
        mu=getUsedMem(ts);
    }
    else tu=mu="0";
    //cout<<ce_info;
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
    if (!login())
    {
        writelog("Login error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    }
    writelog("Logged in!\n");
    lang=corrlang[lang];
    int rcode=0;
    if (rcode=submit(pid,lang,src))
    {
        writelog("Submit error!\n");
        if (rcode==2&&lang=="41") { // SPOJ C++ hack
            writelog("Try another c++\n");
            rcode=submit(pid,"1",src);
            if (rcode==2) toBottFile(runid,"0","0","Judge Error (Invalid Language)","");
            else if (rcode) toBottFile(runid,"0","0","Judge Error","");
            if (rcode) return;
        }
        else {
            if (rcode==2) toBottFile(runid,"0","0","Judge Error (Invalid Language)","");
            else toBottFile(runid,"0","0","Judge Error","");
            return;
        }
    }
    writelog("Submitted!\n");
    string result,ce_info,tu,mu;
    if (!getStatus(pid,lang,result,ce_info,tu,mu)) {
        writelog("Get Error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    };
    toBottFile(runid,tu,mu,result,ce_info);
}

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
//    if (argc!=3) {
//        printf("Illegal call!\nPlease follow the format below!\n%s <IP address> <port>\n",argv[0]);
//        exit(1);
//    }

    init();
    curl_global_init(CURL_GLOBAL_ALL);

    if ((sockfd=socket(AF_INET,SOCK_STREAM,0))==-1) {
        writelog("socket() error!\n");
        exit(1);
    }
    bzero(&server,sizeof(server));
    port_number=atoi(argv[2]);
    //port_number=5907;
    server.sin_family=AF_INET;
    server.sin_port=htons(port_number);
    server.sin_addr.s_addr=inet_addr(argv[1]);
    //server.sin_addr.s_addr=inet_addr("202.112.88.60");
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

