#include "cfhandler.h"

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
    int cnt=0;
    while (fgets(tmps,1000000,fp))
    {
        ts=tmps;
        if (ts.find((string)"<tr class=\"first-row\">")!=string::npos) cnt++;
        if (cnt>0&&ts.find((string)"<a href=\"/profile/"+username+"\"")!=string::npos)
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

int calctta(string a) {
    int b=0;
    for(int c=0;c<a.length();c++){
        b=(b+(c+1)*(c+2)*a[c])%1009;
        if(c%3==0) b++;
        if(c%2==0) b*=2;
        if(c>0) b-=((int)(a[c/2]/2))*(b%5);
        while(b<0) b+=1009;
        while(b>=1009) b-=1009;
    } 
    return b;
}

string getttaValue() {
    FILE * fp=fopen("cf.cookie","r");
    char tmpstr[500]={0};
    while (fscanf(fp,"%s",tmpstr)!=EOF) {
        if (strcmp(tmpstr,"39ce7")==0) {
            fscanf(fp,"%s",tmpstr);
            fclose(fp);
            break;
        }
    }

    writelog(tmpstr);
    writelog("\n");

    string tta=covert(calctta(tmpstr));
    writelog("tta Value: ");
    writelog((char *)tta.c_str());
    writelog("\n");
    return tta;
}


string getCFLineFromFile(char *filename,string mark) {
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) {
        res=tmps;
        if (res.find(mark)!=string::npos) {
            while (fgets(tmps,1000000,fp)) {
                res=tmps;
                if (res.find("<input type=\'hidden\' ")!=string::npos) break;
            }
            break;
        }
    }
    fclose(fp);
    int loc=0,tp;
    // cout<<res;
    string rres="";
    while ((loc=res.find("name=\'",loc))!=string::npos) {
        loc=loc+strlen("name=\'");
        tp=res.find("\'",loc);
        //cout <<loc <<" " << tp <<" "<<res.substr(loc,tp-loc)<<endl;
        rres=rres+res.substr(loc,tp-loc);
        loc=res.find("value=\'",tp);
        loc=loc+strlen("value=\'");
        tp=res.find("\'",loc);
        //cout <<loc <<" " << tp <<" "<<res.substr(loc,tp-loc)<<endl;
        rres=rres+"="+escapeURL(res.substr(loc,tp-loc))+"&";
    }
    return rres;
}
string getPara(string mark) {
    FILE * fp=fopen(tfilename,"w");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        //curl_easy_setopt(curl, CURLOPT_VERBOSE, 1);
        curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "cf.cookie");
        curl_easy_setopt(curl, CURLOPT_URL,"http://codeforces.com/enter");
        res = curl_easy_perform(curl);
        //curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return "";
    string ts=getCFLineFromFile(tfilename,mark);
    //cout<<ts;
    return ts;
}


bool login()
{
    curl = curl_easy_init();
    curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "cf.cookie");
    curl_easy_setopt(curl, CURLOPT_URL, "http://codeforces.com/");
    curl_easy_perform(curl);
    curl_easy_cleanup(curl);
    
    string tts=getPara("Login into Codeforces");    
    
    struct curl_slist *headers=NULL;
    
    FILE * fp=fopen(tfilename,"w");
    //cout<<tfilename<<endl;
    curl = curl_easy_init();
    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
        curl_easy_setopt(curl, CURLOPT_REFERER, "http://codeforces.com/enter");
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "cf.cookie");
        curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);
        string post=(string)tts+"action=enter&handle="+username+"&password="+password+"&_tta="+getttaValue();
        cout<<post;
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        curl_easy_setopt(curl, CURLOPT_URL, "http://codeforces.com/enter");
        res = curl_easy_perform(curl);

        //print_cookies(curl);

        curl_easy_cleanup(curl);
    }
    fclose(fp);

    if (res) return false;
    string ts=getAllFromFile(tfilename);
    cout<<"===============Login\n"<<ts<<"\n============\n";
    if (ts.find("Invalid handle or password")!=string::npos) return false;
    return true;
}


string getCFActionFromFile(char *filename) {
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) {
        res=tmps;
        if (res.find("<form class=\"submit-form\"")!=string::npos) break;
    }
    fclose(fp);
    int loc=0,tp;
    cout<<res;
    string rres="";
    loc=res.find("action=\"",loc);
    loc=loc+strlen("action=\"");
    tp=res.find("\"",loc);
    rres=res.substr(loc,tp-loc);
    return rres;
}

int submit(string pid,string lang,string source)
{
    FILE * fp=fopen(tfilename,"w");
    curl = curl_easy_init();
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
    curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
    curl_easy_setopt(curl, CURLOPT_URL, "http://codeforces.com/problemset/submit");
    curl_easy_perform(curl);
    curl_easy_cleanup(curl);
    fclose(fp);
    
    string ta=getCFActionFromFile(tfilename);
    
    fp=fopen(tfilename,"w");
    //curl = curl_easy_init();
    static const char buf[] = "Expect:";
    string cid;
    string pname;
    int loc=0;
    while (pid[loc]>='0'&&pid[loc]<='9') loc++;
    cid=pid.substr(0,loc);
    pname=pid.substr(loc);
    formpost=NULL;
    lastptr=NULL;
    headerlist=NULL;
    srand(time(NULL));
    int tmp=rand()%120;
    source+='\n';
    for (int i=0;i<tmp;i++) source+=' ';
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "action",
                 CURLFORM_COPYCONTENTS, "submitSolutionFormSubmitted",
                 CURLFORM_END);
/*    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "submittedProblemCode",
                 CURLFORM_COPYCONTENTS, pid.c_str(),
                 CURLFORM_END);*/
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "contestId",
                 CURLFORM_COPYCONTENTS, cid.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "submittedProblemIndex",
                 CURLFORM_COPYCONTENTS, pname.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "programTypeId",
                 CURLFORM_COPYCONTENTS, lang.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "source",
                 CURLFORM_COPYCONTENTS, source.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "sourceFile",
                 CURLFORM_FILE, "emptyfile.txt",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "sourceFile",
                 CURLFORM_FILENAME, "",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "_tta",
                 CURLFORM_COPYCONTENTS, getttaValue().c_str(),
                 CURLFORM_END);


    curl = curl_easy_init();

    headerlist = curl_slist_append(headerlist, buf);

    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_URL, ((string)"http://codeforces.com/problemset/submit"+ta).c_str());
        curl_easy_setopt(curl, CURLOPT_HTTPPOST, formpost);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
        curl_easy_setopt(curl, CURLOPT_VERBOSE, 1);
        curl_easy_perform(curl);
        
        curl_easy_cleanup(curl);
        curl_formfree(formpost);
        curl_slist_free_all (headerlist);

        fclose(fp);

        string ts=getAllFromFile(tfilename);
        cout<<ts<<endl;
        //writelog((char *)ts.c_str());
        if (ts.find("You have submitted exactly the same code before")!=string::npos) return 1;
        if (ts.find("<span class=\"error for__source\">")!=string::npos) return 3;
        if (ts.find("Choose valid language")!=string::npos) return 2;
    }
    return 0;
}


string getResult(string s)
{
    int pos=s.find("submissionId");
    while (s[pos]!='>') pos++;
    pos++;
    while (s[pos]!='>'&&(s[pos]!='I'||s[pos]!='n')) pos++;
    if (s[pos]=='I') return "In queue";
    pos++;
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

string getRunid(string s)
{
    int pos=s.find("submissionId=");
    while (s[pos]!='\"') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='\"') pos++;
    return s.substr(st,pos-st);
}

char tempce[MAX_DATA_SIZE];

string getCFProtocolFromFile(char *filename) {
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) {
        res=tmps;
        if (res.find("<meta name=\"X-Csrf-Token\"")!=string::npos) break;
    }
    fclose(fp);
    int loc=0,tp;
    cout<<res;
    string rres="";
    loc=res.find("content=\"",loc);
    loc=loc+strlen("content=\"");
    tp=res.find("\"",loc);
    rres=res.substr(loc,tp-loc);
    return rres;
}

string getCEinfo(string runid)
{
    FILE * fp=fopen(tfilename,"w");
    curl = curl_easy_init();
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
    curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
    curl_easy_setopt(curl, CURLOPT_URL, "http://codeforces.com/problemset/submit");
    curl_easy_perform(curl);
    curl_easy_cleanup(curl);
    fclose(fp);
    string csrf=getCFProtocolFromFile(tfilename);

    fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
        string url=(string)"http://codeforces.com/data/judgeProtocol";
        string post=(string)"submissionId="+runid+"&csrf_token="+csrf;
        //cout<<post;
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        //cout<<url;
        curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);

    string info=getAllFromFile(tfilename);
    return info;
}

string getUsedTime(string s)
{
    int pos=s.find(" ms");
    int st=pos;
    pos--;
    while (s[pos]!=' ') pos--;
    pos++;
    return s.substr(pos,st-pos);
}

string getUsedMem(string s)
{
    int pos=s.find(" KB");
    int st=pos;
    pos--;
    while (s[pos]!=' ') pos--;
    pos++;
    return s.substr(pos,st-pos);
}

string convertResult(string res) {
    if (res.find("Compilation error")!=string::npos) return "Compile Error";
    if (res.find("Wrong answer")!=string::npos) return "Wrong Answer";
    if (res.find("Runtime error")!=string::npos) return "Runtime Error";
    if (res.find("Time limit exceeded")!=string::npos) return "Time Limit Exceed";
    if (res.find("Idleness")!=string::npos||res.find("Memory limit exceeded")!=string::npos) return "Memory Limit Exceed";
	if (res.find("Denial of")!=string::npos) return "Judge Error";
    if (res.find("Judgement failed")!=string::npos) return "Judge Error";
    return res;
}

string getVerditFromFile(char *filename)
{
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    int cnt=0;
    while (fgets(tmps,1000000,fp))
    {
        ts=tmps;
        if (ts.find("<div  class=\"error\">")!=string::npos)
        {
            res=tmps;
            while (1) {
                fgets(tmps,1000000,fp);
                ts=tmps;
                if (ts.find("</pre></div>")!=string::npos) cnt++;
                res=res+ts;
                if (cnt==4) break;
            }
            break;
        }
    }
    fclose(fp);
    return res;
}

string getExtern(string pid,string runid) {
    pid=pid.substr(0,pid.length()-1);
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
        string url=(string)"http://codeforces.com/contest/"+pid+"/submission/"+runid;
        //cout<<url;
        curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    string info=getVerditFromFile(tfilename);
    return info;
}


//http://www.codeforces.com/problemset/status
bool getStatus(string pid,string lang,string & result,string& ce_info,string &tu,string &mu)
{
    usleep(1000000);
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
            curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "cf.cookie");
            string url=(string)"http://codeforces.com/submissions/"+username;
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
        //cout<<ts<<endl;
        if (ts=="") {
            if (time(NULL)-begin>MAX_WAIT_TIME) return false;
            else continue;
        }
        //cout<<ts;
        writelog((char *)ts.c_str());
        runid=getRunid(ts);
        result=getResult(ts);
        writelog((char *)result.c_str());
        if (result.find("Waiting")==string::npos
                &&result.find("Running")==string::npos
                &&result.find("Judging")==string::npos
                &&result.find("queue")==string::npos
                &&result.find("Compiling")==string::npos
                &&result.length()>6)
        {
            break;
        }
        if (time(NULL)-begin>MAX_WAIT_TIME) break;
    }
    if (!(result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Judging")==string::npos
            &&result.find("queue")==string::npos
            &&result.find("Compiling")==string::npos
            &&result.length()>6)) return false;
    tu=getUsedTime(ts);
    mu=getUsedMem(ts);
    //cout<<runid<<" "<<tu<<" "<<mu<<" "<<result<<endl;
    result=convertResult(result);
    if (result=="Compile Error") ce_info=getCEinfo(runid);
    else if (result!="Accepted") ce_info=getExtern(pid,runid);
    else ce_info="";
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
    try {
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
            if (rcode==1) toBottFile(runid,"0","0","Judge Error (Same Code)","");
            if (rcode==2) toBottFile(runid,"0","0","Judge Error (Invalid Language)","");
            if (rcode==3) toBottFile(runid,"0","0","Compile Error","");
            return;
        }
        writelog("Submitted!\n");
        string result,ce_info,tu,mu;
        if (!getStatus(pid,lang,result,ce_info,tu,mu)) {
            writelog("Get Error!\n");
            toBottFile(runid,"0","0","Judge Error","");
            return;
        };
        toBottFile(runid,tu,mu,result,ce_info);
    } catch (exception e) {
        writelog("Something went wrong!\n");
        toBottFile(runid,"0","0","Judge Error","");
    }
}

/*
int main() {
    init();
    judge("1A","1","1",getAllFromFile("test.cpp"));
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
