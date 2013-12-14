#include "nbuthandler.h"

CURL *curl;
CURLcode res;
char tmps[1000000];

string getAllFromFile(char *filename) {
    string res="";
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) res+=tmps;
    fclose(fp);
    return res;
}

string getResFromFile(char *filename) {
    string res=getAllFromFile(filename);
    int st=res.find("<td style=\"text-align: center;\">");
    int pos=res.find("</tr>",st);
    return res.substr(st,pos-st);
}

string getHiddenLineFromFile(char *filename) {
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) {
        res=tmps;
        if (res.find("<input type=\"hidden\"")!=string::npos) {
            while (fgets(tmps,1000000,fp)) {
                ts=tmps;
                if (ts.find("<input type=\"hidden\" ")==string::npos) break;
                res=res+ts;
            }
            break;
        }
    }
    fclose(fp);
    int loc=0,tp;
    // cout<<res;
    string rres="";
    while ((loc=res.find("name=\"",loc))!=string::npos) {
        loc=loc+strlen("name=\"");
        tp=res.find("\"",loc);
        //cout <<loc <<" " << tp <<" "<<res.substr(loc,tp-loc)<<endl;
        rres=rres+escapeURL(res.substr(loc,tp-loc));
        loc=res.find("value=\"",tp);
        loc=loc+strlen("value=\"");
        tp=res.find("\"",loc);
        //cout <<loc <<" " << tp <<" "<<res.substr(loc,tp-loc)<<endl;
        rres=rres+"="+escapeURL(res.substr(loc,tp-loc))+"&";
    }
    return rres;
}

string getPara() {
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        //curl_easy_setopt(curl, CURLOPT_VERBOSE, 1);
        curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "nbut.cookie");
        curl_easy_setopt(curl, CURLOPT_URL,"http://ac.nbutoj.com/User/login.xhtml");
        res = curl_easy_perform(curl);
        //curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return "";
    string ts=getHiddenLineFromFile(tfilename);
    cout<<ts;
    return ts;
}

bool login() {
    string post=getPara();
    if (post=="") return false;
    FILE * fp=fopen(tfilename,"w+");
    //curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        //curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
        //curl_easy_setopt(curl, CURLOPT_VERBOSE, 1);
        //curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "nbut.cookie");
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "nbut.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://ac.nbutoj.com/User/chklogin.xhtml");
        post=post+"username="+username+"&password="+escapeURL(password);
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1);
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return false;
    string ts=getAllFromFile(tfilename);
    //cout<<ts;
    if (ts!="1") return false;
    return true;
}

string covert(int x) {
    char tt[100];
    sprintf(tt,"%d",x);
    string t=tt;
    return t;
}

bool submit(string pid,string lang,string source) {
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "nbut.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://ac.nbutoj.com/Problem/submitok.xhtml");
/*
language=1000&id=1000&code=fsafgvasfadsvgds%0AGfd%0Ah%0Aefh%0Afd%0Ahdf%0Ajh%0Afe
*/

        string post=(string)"language="+lang+"&id="+pid+"&code="+escapeURL(source);
        //cout<<post;
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return false;
    string ts=getAllFromFile(tfilename);
    //cout<<ts;
    if (ts.find("{\"status\":0")!=string::npos||ts.find("The page is temporarily unavailable")!=string::npos) return false;
    return true;
}


string getResult(string s) {
    int pos=s.find("<span style=");
    while (s[pos]!='>') pos++;
    pos++;
    int st=pos;
    pos=s.find("</",pos);
    return s.substr(st,pos-st);
}

string getRunid(string s) {
    int pos=s.find("<td");
    while (s[pos]!='>') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

char tempce[MAX_DATA_SIZE];

string getCEinfo(string runid) {
    FILE * fp=fopen(tfilename,"w");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "nbut.cookie");
        string url=(string)"http://ac.nbutoj.com/Problem/viewce.xhtml?submitid="+runid;
        //cout<<url;
        curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    string ts=getAllFromFile(tfilename);
    //cout<<ts;

    int pos=ts.find("<pre");
    while (ts[pos]!='>') pos++;
    pos++;
    int st=pos;
    pos=ts.find("</pre>");

    string info=ts.substr(st,pos-st);
    //strcpy(tempce,info.c_str());
    //decode_html_entities_utf8(tempce, 0);
    //info=tempce;
    /*int position = info.find( "<br/>" );
    while ( position != string::npos ) {
        info.replace( position, strlen("<br/>"), "\n" );
        position = info.find( "<br/>", position );
    }*/
//    return "";
    return info;
}

string getUsedTime(string s) {
    int pos=s.find("center;\">");
    pos=s.find("center;\">",pos+1);
    pos=s.find("center;\">",pos+1);
    pos=s.find("center;\">",pos+1);
    pos=s.find("center;\">",pos+1);
    while (s[pos]!='>') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

string getUsedMem(string s) {
    int pos=s.find("center;\">");
    pos=s.find("center;\">",pos+1);
    pos=s.find("center;\">",pos+1);
    pos=s.find("center;\">",pos+1);
    pos=s.find("center;\">",pos+1);
    pos=s.find("center;\">",pos+1);
    while (s[pos]!='>') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

string convertResult(string res) {
    if (res.find("ACCEPTED")!=string::npos) return "Accepted";
    if (res.find("WRONG_ANSWER")!=string::npos) return "Wrong Answer";
    if (res.find("PRESENTATION_ERROR")!=string::npos) return "Presentation Error";
    if (res.find("RUNTIME_ERROR")!=string::npos) return "Runtime Error";
    if (res.find("TIME_LIMIT_EXCEEDED")!=string::npos) return "Time Limit Exceed";
    if (res.find("MEMORY_LIMIT_EXCEEDED")!=string::npos) return "Memory Limit Exceed";
    if (res.find("OUTPUT_LIMIT_EXCEEDED")!=string::npos) return "Output Limit Exceed";
    if (res.find("COMPILATION_ERROR")!=string::npos) return "Compile Error";
    if (res.find("RESTRICTED_FUNCTION")!=string::npos) return "Restricted Function";
    return res;
}

//http://acm.nbut.cn/Problem/status.xhtml?username=bnuvjudge&problemid=1000&language=1
bool getStatus(string pid,string lang,string & result,string& ce_info,string &tu,string &mu) {
    int begin=time(NULL);
    string runid;
    tu=mu="0";
    string ts;
    while (true) {
        FILE * fp=fopen(tfilename,"w+");
        curl = curl_easy_init();
        if(curl) {
            curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
            curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
            curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "nbut.cookie");
            string url=(string)"http://ac.nbutoj.com/Problem/status.xhtml?username="+username+"&problemid="+pid+"&language="+lang;
            //cout<<url<<endl;
            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            res = curl_easy_perform(curl);
            curl_easy_cleanup(curl);
        }
        fclose(fp);
        if (res) return false;
        ts=getResFromFile(tfilename);
        //cout<<ts;
        if (ts.find("Error Occurred")!=string::npos||ts.find("The page is temporarily unavailable")!=string::npos) return false;
        runid=getRunid(ts);
        result=getResult(ts);
        //cout << result;
        if (result.find("WAITING")==string::npos
            &&result.find("RUNNING")==string::npos
            &&result.find("JUDGING")==string::npos
            &&result.find("QUEUING")==string::npos
            &&result.find("COMPILING")==string::npos) {
            break;
        }
        if (time(NULL)-begin>MAX_WAIT_TIME) break;
    }
    if (!(result.find("WAITING")==string::npos
            &&result.find("RUNNING")==string::npos
            &&result.find("JUDGING")==string::npos
            &&result.find("QUEUING")==string::npos
            &&result.find("COMPILING")==string::npos)) return false;
    result=convertResult(result);
    if (result=="Compile Error") ce_info=getCEinfo(runid);
    else ce_info="";
    tu=getUsedTime(ts);
    mu=getUsedMem(ts);
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

void toBottFile(string runid,string tu,string mu,string result,string ce_info){
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

void judge(string pid,string lang,string runid,string src) {
    if (src.length()<15) {
        toBottFile(runid,"0","0","Compile Error","");
        return;
    }
    if (!login()) {
        writelog("Login error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    }
    lang=corrlang[lang];
    if (!submit(pid,lang,src)) {
        writelog("Submit error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    }
    string result,ce_info,tu,mu;
    if (!getStatus(pid,lang,result,ce_info,tu,mu)) {
        writelog("Get Error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    };
    toBottFile(runid,tu,mu,result,ce_info);
}

/*
int main() {
    init();
    judge("1000","2","111","main(){int a,b;while(scanf(\"%d%d\",&a,&b)&&a&&b) printf(\"%d\\n\",a+b);}");
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

