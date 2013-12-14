#include "uvahandler.h"

CURL *curl;
CURLcode res;
CURLM *multi_handle;
int still_running;
struct curl_httppost *formpost=NULL;
struct curl_httppost *lastptr=NULL;
struct curl_slist *headerlist=NULL;
char tmps[1000000];

string getAllFromFile(char *filename) {
    string res="";
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) res+=tmps;
    fclose(fp);
    return res;
}

string getFirstFromFile(char *filename) {
    string res=getAllFromFile(filename);
    int loc=res.find("<tr class=\"sectiontableentry1\">");
    loc+=strlen("<tr class=\"sectiontableentry1\">");
    int loc2=res.find("</tr>",loc);
    //cout<<res.substr(loc,loc2-loc)<<endl<<"===="<<endl;
    return res.substr(loc,loc2-loc);
}

string getUVALineFromFile(char *filename) {
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) {
        res=tmps;
        if (res.find("<input type=\"hidden\" ")==0) {
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
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "uva.cookie");
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "uva.cookie");
        curl_easy_setopt(curl, CURLOPT_URL,"http://uva.onlinejudge.org/");
        res = curl_easy_perform(curl);
        //curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return "";
    string ts=getUVALineFromFile(tfilename);
    //cout<<ts;
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
        curl_easy_setopt(curl, CURLOPT_REFERER, "http://uva.onlinejudge.org/");
        curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
        //curl_easy_setopt(curl, CURLOPT_VERBOSE, 1);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "uva.cookie");
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "uva.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://uva.onlinejudge.org/index.php?option=com_comprofiler&task=login");
        post=post+"username="+username+"&passwd="+escapeURL(password);
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        curl_easy_setopt(curl, CURLOPT_FOLLOWLOCATION, 1);
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return false;
    string ts=getAllFromFile(tfilename);
    //cout<<ts;
    if (ts.find("<td>No account yet? ")!=string::npos||ts.find("<div class='error'>")!=string::npos||ts.find("You are not authorized to view this page!")!=string::npos) return false;
    return true;
}

string convert(int x) {
    char tt[100];
    sprintf(tt,"%d",x);
    string t=tt;
    return t;
}

bool submit(string pid,string lang,string source)
{
    formpost=NULL;
    lastptr=NULL;
    headerlist=NULL;
    FILE * fp=fopen(tfilename,"w+");
    static const char buf[] = "Expect:";
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "problemid",
                 CURLFORM_COPYCONTENTS, "",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "category",
                 CURLFORM_COPYCONTENTS, "",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "localid",
                 CURLFORM_COPYCONTENTS, pid.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "language",
                 CURLFORM_COPYCONTENTS, lang.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "code",
                 CURLFORM_COPYCONTENTS, source.c_str(),
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "codeupl",
                 CURLFORM_FILE, "emptyfile.txt",
                 CURLFORM_END);
    curl_formadd(&formpost,
                 &lastptr,
                 CURLFORM_COPYNAME, "codeupl",
                 CURLFORM_FILENAME, "",
                 CURLFORM_END);

    curl = curl_easy_init();

    headerlist = curl_slist_append(headerlist, buf);
    if(curl)
    {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_URL, "http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=25&page=save_submission");
        //curl_easy_setopt(curl, CURLOPT_VERBOSE, 1);
        curl_easy_setopt(curl, CURLOPT_HEADER, 1);
        curl_easy_setopt(curl, CURLOPT_HTTPPOST, formpost);
        curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headerlist);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "uva.cookie");
        curl_easy_perform(curl);
        curl_easy_cleanup(curl);
        curl_formfree(formpost);
        curl_slist_free_all (headerlist);

        fclose(fp);

        string ts=getAllFromFile(tfilename);
        if (ts.find("You have to select a programming language.")!=string::npos||
            ts.find("The selected problem ID does not exist.")!=string::npos||
            ts.find("You have to paste or upload some source code.")!=string::npos||
            ts.find(" You are not authorised to view this resource.")!=string::npos) return false;
    }
    return true;
}


string getResult(string s) {
    int pos=s.find("<td>");
    pos++;pos=s.find("<td>",pos);
    pos++;pos=s.find("<td>",pos);
    int st=pos+strlen("<td>");
    pos=s.find("</td>",pos);
    string rs=s.substr(st,pos-st);
    if ((st=rs.find("</a>"))!=string::npos) {
        pos=st;
        while (rs[pos]!='>') pos--;
        pos++;
        rs=rs.substr(pos,st-pos);
    }
    return rs;
}

string getRealRunid() {
    string s=getAllFromFile(tfilename);
    int pos=s.find("Submission+received+with+ID+");
    while (s[pos]!='D') pos++;
    pos++;pos++;
    int st=pos;
    while (s[pos]!='\r'&&s[pos]!='\n') pos++;
    return s.substr(st,pos-st);
}

char tempce[MAX_DATA_SIZE];

string getCEinfo(string runid) {
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "uva.cookie");
        string url=(string)"http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=9&page=show_compilationerror&submission="+runid;
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
//    strcpy(tempce,info.c_str());
//    decode_html_entities_utf8(tempce, 0);
//    info=tempce;
    int position = info.find( "\\" );
    while ( position != string::npos ) {
        info.replace( position, 1, "\\\\" );
        position = info.find( "\\", position + 2 );
    }
    return info;
}

string getRunid(string s) {
    int pos=s.find("</td>");
    int st=pos;
    while (s[pos]!='>') pos--;
    pos++;
    return s.substr(pos,st-pos);
}

string getUsedTime(string s) {
    int pos=s.find("</td>");
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    int st=pos;
    while (s[pos]!='>') pos--;
    pos++;
    string t=s.substr(pos,st-pos);
    double r;
    sscanf(t.c_str(),"%lf",&r);
    return convert((int)(r*1000+0.01));
}

string getUsedMem(string s) {
    int pos=s.find("</td>");
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    pos++;pos=s.find("</td>",pos);
    int st=pos;
    while (s[pos]!='>') pos--;
    pos++;
    return s.substr(pos,st-pos);
}

//http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=9
bool getStatus(string pid,string lang,string & result,string& ce_info,string &tu,string &mu) {
    int begin=time(NULL);
    string runid=getRealRunid();
    //cout<<runid;
    tu=mu="0";
    string ts;
    while (true) {
        if (time(NULL)-begin>MAX_WAIT_TIME) break;
        FILE * fp=fopen(tfilename,"w+");
        curl = curl_easy_init();
        if(curl) {
            curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
            curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
            curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "uva.cookie");
            curl_easy_setopt(curl, CURLOPT_USERAGENT, "curl");
            string url=(string)"http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=9";
            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            res = curl_easy_perform(curl);
            curl_easy_cleanup(curl);
        }
        fclose(fp);
        if (res) return false;
        ts=getFirstFromFile(tfilename);
        if (ts.find("<b>One or more following ERROR(s) occurred.")!=string::npos||ts.find("The page is temporarily unavailable")!=string::npos) return false;
        if (runid!=getRunid(ts)) continue;
        result=getResult(ts);
        cout << result <<endl;
        if (result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Judging")==string::npos
            &&result.find("judge")==string::npos
            &&result.find("Queuing")==string::npos
            &&result.find("queue")==string::npos
            &&result.find("Compiling")==string::npos
            &&result.find("Received")==string::npos
            &&result.find("Linking")==string::npos
            &&result.find("Submission error")==string::npos
            &&result!=""&&result[0]!='\t'&&result[0]!=' ') {
            break;
        }
    }
    if (!(result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Judging")==string::npos
            &&result.find("judge")==string::npos
            &&result.find("Queuing")==string::npos
            &&result.find("queue")==string::npos
            &&result.find("Compiling")==string::npos
            &&result.find("Received")==string::npos
            &&result.find("Linking")==string::npos
            &&result.find("Submission error")==string::npos
            &&result!=""&&result[0]!='\t'&&result[0]!=' ')) return false;
    if (result=="Compilation error") {
        result="Compile Error";
        ce_info=getCEinfo(runid);
    }
    else ce_info="";
    tu=getUsedTime(ts);
    mu=getUsedMem(ts);
    if (mu=="") mu="0";
    if (result!="Accepted"&&result[result.length()-1]=='d') {
        result.erase(result.end()-2,result.end());
    }
    for (int i=0;i<result.length();i++) {
        if (result[i]==' ') {
            if (result[i+1]>='a'&&result[i+1]<='z') result[i+1]+='A'-'a';
        }
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
    try {
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
        }
        toBottFile(runid,tu,mu,result,ce_info);
    }
    catch (exception & e) {
        writelog(((string)"Something went wrong!"+e.what()+"\n").c_str());
        toBottFile(runid,"0","0","Judge Error","");
    }
}
/*
int main() {
    init();
    judge("100","1","1","fduishfnquoe4ru312094ur\nfh8fdjps");
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

void writelog(const char* log)
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
            judge(temp.vid,convert(temp.lang),convert(temp.runid),temp.src);
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
