#include "scuhandler.h"

CURL *curl;
CURLcode res;
char tmps[1000000];

int loadJpg(const char* Name,int &Width,int &Height,int *& BMap) {
    unsigned char a, r, g, b;
    int width, height;
    struct jpeg_decompress_struct cinfo;
    struct jpeg_error_mgr jerr;

    FILE * infile; /* source file */
    JSAMPARRAY pJpegBuffer; /* Output row buffer */
    int row_stride; /* physical row width in output buffer */
    if ((infile = fopen(Name, "rb")) == NULL) {
        fprintf(stderr, "can't open %s\n", Name);
        return 0;
    }
    cinfo.err = jpeg_std_error(&jerr);
    jpeg_create_decompress(&cinfo);
    jpeg_stdio_src(&cinfo, infile);
    (void) jpeg_read_header(&cinfo, TRUE);
    (void) jpeg_start_decompress(&cinfo);
    width = cinfo.output_width;
    height = cinfo.output_height;
    
    //printf("~~~~~ %d %d\n",width,height);

    unsigned char * pDummy = new unsigned char [width * height * 4];
    unsigned char * pTest = pDummy;
    if (!pDummy) {
        printf("NO MEM FOR JPEG CONVERT!\n");
        return 0;
    }
    row_stride = width * cinfo.output_components;
    pJpegBuffer = (*cinfo.mem->alloc_sarray)
            ((j_common_ptr) & cinfo, JPOOL_IMAGE, row_stride, 1);

    while (cinfo.output_scanline < cinfo.output_height) {
        (void) jpeg_read_scanlines(&cinfo, pJpegBuffer, 1);
        for (int x = 0; x < width; x++) {
            a = 0; // alpha value is not supported on jpg
            r = pJpegBuffer[0][cinfo.output_components * x];
            if (cinfo.output_components > 2) {
                g = pJpegBuffer[0][cinfo.output_components * x + 1];
                b = pJpegBuffer[0][cinfo.output_components * x + 2];
            } else {
                g = r;
                b = r;
            }
            *(pDummy++) = b;
            *(pDummy++) = g;
            *(pDummy++) = r;
            *(pDummy++) = a;
        }
    }
    fclose(infile);
    (void) jpeg_finish_decompress(&cinfo);
    jpeg_destroy_decompress(&cinfo);

    BMap = (int*) pTest;
    Height = height;
    Width = width;
//    Depht = 32;
}

char getXY(int n,int x,int y,int Width,int Height,int * BMap) {
    int v = BMap[x*Width+(y+n*8+3)];
    //return v;
    if (v>0x600000) return ' ';
    else return 'x';
}

string getCode(const char * filename) {
    int *jpg,width,height;
    jpg=NULL;
    loadJpg(filename,width,height,jpg);
    if (jpg==NULL) return "";
    
//    for (int i=0;i<height;++i,printf("\n"))
//        for (int j=0;j<width;++j) printf("%c",getXY(0,i,j,width,height,jpg));
    
    char res[5];
    for (int n=0;n<4;++n) {
        if (getXY(n,5,1,width,height,jpg)=='x'&&
                getXY(n,5,2,width,height,jpg)==' '&&
                getXY(n,5,6,width,height,jpg)=='x') res[n]=0;
        
        else if (getXY(n,2,2,width,height,jpg)=='x'&&
                getXY(n,2,3,width,height,jpg)==' '&&
                getXY(n,2,4,width,height,jpg)=='x') res[n]=1;
        
        else if (getXY(n,9,1,width,height,jpg)=='x'&&
                getXY(n,9,2,width,height,jpg)=='x'&&
                getXY(n,9,3,width,height,jpg)=='x'&&
                getXY(n,9,4,width,height,jpg)=='x'&&
                getXY(n,9,5,width,height,jpg)=='x') res[n]=2;
        
        else if (getXY(n,5,1,width,height,jpg)==' '&&
                getXY(n,5,2,width,height,jpg)=='x'&&
                getXY(n,5,3,width,height,jpg)=='x'&&
                getXY(n,5,4,width,height,jpg)==' ') res[n]=3;
        
        else if (getXY(n,2,3,width,height,jpg)==' '&&
                getXY(n,2,4,width,height,jpg)=='x'&&
                getXY(n,2,5,width,height,jpg)=='x') res[n]=4;
        
        else if (getXY(n,4,1,width,height,jpg)=='x'&&
                getXY(n,4,2,width,height,jpg)=='x'&&
                getXY(n,4,3,width,height,jpg)=='x'&&
                getXY(n,4,4,width,height,jpg)==' ') res[n]=5;
        
        else if (getXY(n,4,1,width,height,jpg)=='x'&&
                getXY(n,4,2,width,height,jpg)==' '&&
                getXY(n,4,3,width,height,jpg)=='x'&&
                getXY(n,4,4,width,height,jpg)=='x') res[n]=6;
        
        else if (getXY(n,1,1,width,height,jpg)=='x'&&
                getXY(n,1,6,width,height,jpg)=='x') res[n]=7;
        
        else if (getXY(n,5,2,width,height,jpg)=='x'&&
                getXY(n,5,3,width,height,jpg)=='x'&&
                getXY(n,5,4,width,height,jpg)=='x'&&
                getXY(n,5,5,width,height,jpg)=='x') res[n]=8;
        
        else if (getXY(n,6,3,width,height,jpg)=='x'&&
                getXY(n,6,4,width,height,jpg)=='x'&&
                getXY(n,6,5,width,height,jpg)==' '&&
                getXY(n,6,6,width,height,jpg)=='x') res[n]=9;
        
        else res[n]=0;
    }
    delete jpg;
    res[0]+='0';res[1]+='0';res[2]+='0';res[3]+='0';res[4]=0;
    return res;
}

string getAllFromFile(char *filename) {
    string res="";
    FILE * fp=fopen(filename,"r");
    while (fgets(tmps,1000000,fp)) res+=tmps;
    fclose(fp);
    return res;
}


string getResFromFile(char *filename) {
    string res="",ts;
    FILE * fp=fopen(filename,"r");
    int cnt=0;
    while (fgets(tmps,1000000,fp))
    {
        ts=tmps;
        cnt++;
        if (ts.find("<td height=\"44\">")!=string::npos)
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

bool login() {
    FILE * fp=fopen(tfilename,"w+");
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEJAR, "scu.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://cstest.scu.edu.cn/soj/login.action");
        string post=(string)"back=2&submit=login&id="+username+"&password="+password;
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return false;
    string ts=getAllFromFile(tfilename);
//    cout<<ts;
    if (ts.find("<title>ERROR</title>")!=string::npos) return false;
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
    
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
    curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "scu.cookie");
    curl_easy_setopt(curl, CURLOPT_URL, ((string)"http://cstest.scu.edu.cn/soj/submit_form.action?id="+pid).c_str());
    res=curl_easy_perform(curl);
    fclose(fp);
    if (res) {
        curl_easy_cleanup(curl);
        return false;
    }
    
    fp=fopen(tfilename,"w+");
    curl_easy_setopt(curl, CURLOPT_URL, "http://cstest.scu.edu.cn/soj/validation_code");
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
    curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
    res = curl_easy_perform(curl);
    fclose(fp);
    
    if (res) {
        curl_easy_cleanup(curl);
        return false;
    }
    string code=getCode(tfilename);
    cout<<"Validation Code: "<<code <<endl;
    //return false;
    fp=fopen(tfilename,"w+");
    
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "scu.cookie");
        curl_easy_setopt(curl, CURLOPT_URL, "http://cstest.scu.edu.cn/soj/submit.action");
/*
problemId=1001&validation=7258&language=C%2B%2B+%28G%2B%2B-3%29&source=fasfohjp%3Bthj3emiofe%27wjmpfe%0D%0Agd%0D%0Agh%0D%0Ads%0D%0Ah%0D%0Asdh%0D%0Asd%0D%0Ahsdh&submit=Submit
*/

        string post=(string)"problemId="+pid+"&submit=Submit&validation="+code+"&language="+escapeURL(lang)+"&source="+escapeURL(source);
        //cout<<post;
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, post.c_str());
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
    }
    fclose(fp);
    if (res) return false;
    string ts=getAllFromFile(tfilename);
    cout<<ts;
    if (ts.find("<title>ERROR</title>")!=string::npos||ts.find("The page is temporarily unavailable")!=string::npos) return false;
    return true;
}


string getResult(string s) {
    int pos=s.find("<font");
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
        curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "scu.cookie");
        string url=(string)"http://cstest.scu.edu.cn/soj/judge_message.action?id="+runid;
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
    decode_html_entities_utf8(tempce, 0);
    info=tempce;
    int position = info.find( "\\" );
    while ( position != string::npos ) {
        info.replace( position, 1, "\\\\" );
        position = info.find( "\\", position + 2 );
    } 
    return info;
}

string getUsedTime(string s) {
    int pos=0;
    for (int i=0;i<6;i++) {
        pos=s.find("<td>",pos);
        pos+=strlen("<td>");
    }
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

string getUsedMem(string s) {
    int pos=0;
    for (int i=0;i<7;i++) {
        pos=s.find("<td>",pos);
        pos+=strlen("<td>");
    }
    int st=pos;
    while (s[pos]!='<') pos++;
    return s.substr(st,pos-st);
}

string getRunid(string s)
{
    int pos=s.find(">");
    pos++;
    int st=s.find("</td>",pos);
    return s.substr(pos,st-pos);
}


string convertResult(string res) {
    if (res.find("Compilation")!=string::npos) return "Compile Error";
    if (res.find("Accepted")!=string::npos) return "Accepted";
    if (res.find("Wrong")!=string::npos) return "Wrong Answer";
    if (res.find("Runtime")!=string::npos) return "Runtime Error";
    if (res.find("Time Limit")!=string::npos) return "Time Limit Exceed";
    if (res.find("Presentation")!=string::npos) return "Presentation Error";
    if (res.find("Memory")!=string::npos) return "Memory Limit Exceed";
    return res;
}

//http://cstest.scu.edu.cn/soj/solutions.action?userId=bnuvjudge&problemId=1001
bool getStatus(string pid,string lang,string & result,string& ce_info,string &tu,string &mu) {
    int begin=time(NULL);
    //cout<<runid<<endl;
    tu=mu="0";
    string ts,runid;
    while (true) {
        FILE * fp=fopen(tfilename,"w+");
        curl = curl_easy_init();
        if(curl) {
            curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
            curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, NULL);
            curl_easy_setopt(curl, CURLOPT_COOKIEFILE, "scu.cookie");
            string url=(string)"http://cstest.scu.edu.cn/soj/solutions.action?userId="+username+"&problemId="+pid;
            //cout<<url;
            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            res = curl_easy_perform(curl);
            curl_easy_cleanup(curl);
        }
        fclose(fp);
        if (res) return false;
        ts=getResFromFile(tfilename);
        if (ts=="") return false;
        //cout<<ts;
        runid=getRunid(ts);
        result=getResult(ts);
        cout << result <<endl;
        if (result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Being")==string::npos
            &&result.find("Queuing")==string::npos
            &&result.find("Compiling")==string::npos
            &&result!=""&&result[0]!='\n'&&result[0]!='\t'&&result[0]!='\r'&&result[0]!=' ') {
            break;
        }
        if (time(NULL)-begin>MAX_WAIT_TIME) break;
    }
    if (!(result.find("Waiting")==string::npos
            &&result.find("Running")==string::npos
            &&result.find("Being")==string::npos
            &&result.find("Queuing")==string::npos
            &&result.find("Compiling")==string::npos
            &&result!=""&&result[0]!='\n'&&result[0]!='\t'&&result[0]!='\r'&&result[0]!=' ')) return false;
    
    result=convertResult(result);
    tu=getUsedTime(ts);
    mu=getUsedMem(ts);
    if (result=="Compile Error") ce_info=getCEinfo(runid);
    else ce_info="";
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

bool logged=false;

void judge(string pid,string lang,string runid,string src) {
    if (src.length()<15) {
        toBottFile(runid,"0","0","Compile Error","");
        return;
    }
    if (!logged) {
        if (!login()) {
            writelog("Login error!\n");
            toBottFile(runid,"0","0","Judge Error","");
            return;
        }
        logged=true;
    }
    lang=corrlang[lang];
    if (!submit(pid,lang,src)) {
        writelog("Submit error! Assume not logged in.\n");
        if (!login()) {
            writelog("Login error!\n");
            toBottFile(runid,"0","0","Judge Error","");
            return;
        }
        if (!submit(pid,lang,src)) {
            writelog("Assume should wait a while. Sleep 2 seconds.\n");
            usleep(2000000);
            if (!submit(pid,lang,src)) {
                writelog("Submit error!\n");
                toBottFile(runid,"0","0","Judge Error","");
                return;
            }
        }
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
    judge("1001","1","1",getAllFromFile("test.cpp"));
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
