#include "hduhandler.h"

CURL *curl;
CURLcode res;
char tmps[10000000];
char outs[MAX_DATA_SIZE];
struct curl_slist *headerlist=NULL;

int code_convert(char *from_charset,char *to_charset,char *inbuf,size_t inlen,char *outbuf,size_t outlen)
{
    iconv_t cd;
    int rc;
    char **pin = &inbuf;
    char **pout = &outbuf;

    cd = iconv_open(to_charset,from_charset);
    if (cd==0) return -1;
    memset(outbuf,0,outlen);
    if (iconv(cd,pin,&inlen,pout,&outlen)==-1) return -1;
    iconv_close(cd);
    return 0;
}

int g2u(char *inbuf,size_t inlen,char *outbuf,size_t outlen)
{
    return code_convert("gbk","utf-8",inbuf,inlen,outbuf,outlen);
}

string getAllFromFile(char *filename) {
    string res="";
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,10000000,fp)) {
//        g2u(tmps,strlen(tmps),outs,1000000);
        res+=tmps;
    }
    fclose(fp);
    return res;
}

string getLineFromFile(char *filename,int line) {
    string res="";
    FILE * fp=fopen(filename,"r");
    int cnt=0;
    while (fgets(tmps,10000000,fp)) {
        cnt++;
//        g2u(tmps,strlen(tmps),outs,1000000);
        res=tmps;
        if (res.find("<h1>Realtime Status</h1>")!=string::npos) {
            fgets(tmps,10000000,fp);
//            g2u(tmps,strlen(tmps),outs,1000000);
            res=res+tmps;
            fgets(tmps,10000000,fp);
//            g2u(tmps,strlen(tmps),outs,1000000);
            res=res+tmps;
            break;
        }
    }
    fclose(fp);
    return res;
}

bool login() {
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "hdu.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://acm.hdu.edu.cn/userloginex.php?action=login");
        string post=(string)"username="+username+"&userpass="+password+"&login=Sign+In";
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return false;
    string ts=getAllFromFile(tfilename);
    //cout<<ts;
    if (ts.find("No such user or wrong password.")!=string::npos) return false;
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
    headerlist=NULL;
    static const char buf[] = "Expect:";
    headerlist = curl_slist_append(headerlist, buf);
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "hdu.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://acm.hdu.edu.cn/submit.php?action=submit");
        curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headerlist);
/*
check=0
&problemid=1000
&language=0
&usercode=%23include%3Cstdio.h%3E%0D%0Aint+main%28%29+%7B%0D%0Aint+a%2Cb%3B%0D%0Ascanf%28%22%25d%25d%22%2C%26a%2C%26b%29%3B%0D%0Aprintf%28%22%25d%5Cn%22%2Ca%2Bb%29%3B%0D%0A%7D
*/

        string post=(string)"check=0&problemid="+pid+"&language="+lang+"&usercode="+escapeURL(source);
        //cout<<post;
        //writelog((char *)post.c_str());
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    curl_slist_free_all (headerlist);
    fclose(fp);
    if (res) return false;
    string ts=getAllFromFile(tfilename);
    //cout<<ts;
    //writelog((char *)ts.c_str());
    if (ts.find("Connect(0) to MySQL Server failed.")!=string::npos||ts.find("<b>One or more following ERROR(s) occurred.")!=string::npos||ts.find("<h2>The requested URL could not be retrieved</h2>")!=string::npos||ts.find("PHP: Maximum execution time of")!=string::npos||ts.find("<DIV>Exercise Is Closed Now!</DIV>")!=string::npos) return false;
    return true;
}


string getResult(string s) {
    int pos=s.find("<font color=");
    while (s[pos]!='>') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

string getRunid(string s) {
    int pos=s.find("<td height=22px>");
    while (s[pos]!='>') pos++;
    pos++;
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

char tempce[MAX_DATA_SIZE];

string getCEinfo(string runid) {
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "hdu.cookie");
        string url=(string)"http://acm.hdu.edu.cn/viewerror.php?rid="+runid;
        //cout<<url;
        curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    string ts=getAllFromFile(tfilename);

    int pos=ts.find("<pre>");
    while (ts[pos]!='>') pos++;
    pos++;
    int st=pos;
    while (ts[pos]!='<') pos++;
    string info=ts.substr(st,pos-st);
    strcpy(tempce,info.c_str());
    g2u(tempce,strlen(tempce),outs,MAX_DATA_SIZE);
    decode_html_entities_utf8(outs, 0);
    info=outs;
    int position = info.find( "\\" );
    while ( position != string::npos ) {
        info.replace( position, 1, "\\\\" );
        position = info.find( "\\", position + 2 );
    }
    return info;
}

string getUsedTime(string s) {
    int pos=s.find("MS</td>");
    int st=pos;
    while (s[pos]!='>') pos--;
    pos++;
    return s.substr(pos,st-pos);
}

string getUsedMem(string s) {
    int pos=s.find("K</td>");
    int st=pos;
    while (s[pos]!='>') pos--;
    pos++;
    return s.substr(pos,st-pos);
}

//http://acm.hdu.edu.cn/status.php?first=&pid=1000&user=bnuvjudge&lang=1&status=0
bool getStatus(string pid,string lang,string & result,string& ce_info,string &tu,string &mu) {
    int begin=time(NULL);
    string runid;
    tu=mu="0";
    string ts;
    int tried=0;
    while (true) {
        FILE * fp=fopen(tfilename,"w+");
        curl = curl_easy_init();
        if(curl) {
            curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
            curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
            curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "hdu.cookie");
            string url=(string)"http://acm.hdu.edu.cn/status.php?first=&pid="+pid+"&user="+username+"&lang=&status=0";
            //cout<<url;
            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            res = curl_easy_perform(curl);
            curl_easy_cleanup(curl);
        }
        fclose(fp);
        if (res) return false;
        ts=getLineFromFile(tfilename,77);
        //cout<<ts;
        //writelog((char *)ts.c_str());
        /*if (ts.find("alert(\"Login failed!)")!=string::npos) return false;
        */
        if (ts.find("Connect(0) to MySQL Server failed.")!=string::npos||ts.find("<b>One or more following ERROR(s) occurred.")!=string::npos||ts.find("<h2>The requested URL could not be retrieved</h2>")!=string::npos||ts.find("PHP: Maximum execution time of")!=string::npos||ts.find("<DIV>Exercise Is Closed Now!</DIV>")!=string::npos) {
            tried++;
            if (tried>=MAX_TRY_TIME) return false;
            continue;
        }
        runid=getRunid(ts);
        result=getResult(ts);
        cout << result<<endl<<runid<<endl;
        if (result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Judging")==string::npos
            &&result.find("Queuing")==string::npos
            &&result.find("Compiling")==string::npos
            &&result.find("\n")&&result!="") {
            break;
        }
        if (time(NULL)-begin>MAX_WAIT_TIME) break;
    }
    if (!(result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Judging")==string::npos
            &&result.find("Queuing")==string::npos
            &&result.find("Compiling")==string::npos)) return false;
    if (result=="Compilation Error") {
        result="Compile Error";
        ce_info=getCEinfo(runid);
    }
    else ce_info="";
    tu=getUsedTime(ts);
    mu=getUsedMem(ts);
    if (result!="Accepted"&&result[result.length()-1]=='d') {
        result.erase(result.end()-2,result.end());
    }
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
    if (src.length()<51) {
        toBottFile(runid,"0","0","Compile Error","");
        return;
    }
    if (!login()) {
        writelog("Login error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    }
    writelog("Logined\n");
    lang=corrlang[lang];
    if (!submit(pid,lang,src)) {
        writelog("Submit error!\n");
        toBottFile(runid,"0","0","Judge Error","");
        return;
    }
    writelog("Submitted\n");
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
    /*getchar();
    printf("After\n");*/
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
