#include "chaclient.h"
#include "Program.h"
#include "Comparator.h"
#include "Logger.h"
#include "Bott.h"
#include "PConfig.h"

map <string,string> config;

int GENERAL_COMPILE_TIME,GENERATOR_RUN_TIME,GENERATOR_RUN_MEMORY,VMLANG_MULTIPLIER,MAX_OUTPUT_LIMIT,EXTRA_RUNTIME,CHECKER_RUN_TIME,CHECKER_RUN_MEMORY;

char judger_string[500]={0};
char logfile[200]={0};
int lowprivid=0;

int sockfd;
struct sockaddr_in server;
char buffer[MAX_DATA_SIZE];
Bott * bott;

string tmpnam() {
    string res="";
    for (int i=0;i<10;++i) res+='a'+rand()%26;
    return res;
}

void init()
{
    srand(time(NULL));
    FILE * fin=fopen("config.ini","r");
    char ts1[1000],ts2[1000];
    while (fscanf(fin,"%s = %s",ts1,ts2)!=EOF) {
        config[ts1]=ts2;
    }
    strcpy(judger_string,config["judger_string"].c_str());
    lowprivid=atoi(config["low_privilege_uid"].c_str());
    strcpy(logfile,config["log_file"].c_str());
    GENERAL_COMPILE_TIME=atoi(config["general_compile_time"].c_str());
    GENERATOR_RUN_TIME=atoi(config["generator_run_time"].c_str());
    GENERATOR_RUN_MEMORY=atoi(config["generator_run_memory"].c_str());
    VMLANG_MULTIPLIER=atoi(config["vmlang_multiplier"].c_str());
    MAX_OUTPUT_LIMIT=atoi(config["max_output_limit"].c_str());
    EXTRA_RUNTIME=atoi(config["extra_runtime"].c_str());
    CHECKER_RUN_TIME=atoi(config["checker_run_time"].c_str());
    CHECKER_RUN_MEMORY=atoi(config["checker_run_memory"].c_str());
    strcat(judger_string,"\nBNU");
    fclose(fin);
    Logger::Getinstance()->Setfilename(config["log_file"]);
}

void send_register_info()
{
    write(sockfd,judger_string,sizeof(judger_string));
    sleep(1);
}

void connect_server(int argc, char *argv[]) {
    int port_number;
    mkdir("cha_results",0777);
    //mkdir("cha_results");
    mkdir("results",0777);
    if (argc!=3) {
        printf("Illegal call!\nPlease follow the format below!\n%s <IP address> <port>\n",argv[0]);
        exit(1);
    }
    if ((sockfd=socket(AF_INET,SOCK_STREAM,0))==-1) {
        LOG("socket() error!\n");
        exit(1);
    }
    bzero(&server,sizeof(server));
    port_number=atoi(argv[2]);
    //port_number=5907;
    server.sin_family=AF_INET;
    server.sin_port=htons(port_number);
    server.sin_addr.s_addr=inet_addr(argv[1]);
    //server.sin_addr.s_addr=inet_addr("219.224.30.65");
    if (connect(sockfd,(struct sockaddr*)&server,sizeof(server))==-1)
    {
        LOG("connect() error!");
        exit(1);
    }
    LOG("Connected!");
    send_register_info();
}

void reconnect()
{
    LOG("ERROR in network connection!");
    LOG("Reconnecting...");
    close(sockfd);
    sockfd=socket(AF_INET,SOCK_STREAM,0);
    while (connect(sockfd,(struct sockaddr*)&server,sizeof(server))==-1) sleep(1);
    send_register_info();
    LOG("Successfully reconnected.");
    //sleep(1);
}

int receive_command() {
    int got_things=false;
    int num;
    FILE *target_file;
    target_file=fopen("temp.bott","w");
    while ((num=recv(sockfd,buffer,MAX_DATA_SIZE,MSG_DONTWAIT))>0)
    {
        got_things=true;
        fputs(buffer,target_file);
    }
    fclose(target_file);
    if (num==0) return -1;
    else if (got_things) return 1;
    else return 0;
}

void parse_bott()
{
    bott=new Bott("temp.bott");
}

Program * datagen, * stdprogram, * usrprogram, * checker;
PConfig * problem;
Comparator * cmp;
Bott retbott;

string Loadallfromfile(string filename) {
    string res="",tmps;
    fstream fin(filename.c_str(),fstream::in);
    if (fin.fail()) return res;
    while (getline(fin,tmps)) {
        if (res!="") res+="\n";
        res+=tmps;
        if (fin.eof()) break;
        //getline(fin,tmps);
    }
    fin.close();
    return res;
}

bool Checkfile(string filename) {
    FILE * fp=fopen(filename.c_str(),"r");
    if (fp!=NULL) {
        fclose(fp);
        return true;
    }
    return false;
}

int gendata(string outfilename) {
    LOG("Generating Data...");
    datagen=new Program;
    datagen->Settotal_time_limit(GENERATOR_RUN_TIME*1000);
    datagen->Setcase_time_limit(GENERATOR_RUN_TIME*1000);
    datagen->Setmemory_limit(GENERATOR_RUN_MEMORY);
    datagen->Setlanguage(bott->Getdata_lang());
    datagen->Setsource(bott->Getdata_detail());
    datagen->Setout_filename(outfilename);
    datagen->Sethas_input(false);
    datagen->Run();
    if (datagen->Getresult()!="Normal") {
        LOG("Generator Failed.");
        return 1;
    }
    LOG("Generated.");
    return 0;
}

int set_data(string filename) {
    if (bott->Getdata_type()!=0) {
        return gendata(filename);
    }
    else {
        LOG("Save data to file.");
        fstream fin(filename.c_str(),fstream::out);
        fin<<bott->Getdata_detail();
        fin.close();
        return 0;
    }
}

int check_data(int pid,string in_filename,string result_filename) {
    problem=new PConfig(pid);
    if (problem->Geterror()) {
        LOG("No Data Checker!");
        delete problem;
        return -1;
    }
    LOG("Run Checker");
    checker=new Program;
    checker->Setlanguage(problem->Getdata_checker_language());
    checker->Setsource(Loadallfromfile(problem->Getdata_checker_filename()));
    checker->Sethas_input(true);
    checker->Setin_filename(in_filename);
    checker->Setout_filename(result_filename);
    checker->Setcheck_exit_status(true);
    checker->Settotal_time_limit(CHECKER_RUN_TIME*1000);
    checker->Setcase_time_limit(CHECKER_RUN_TIME*1000);
    checker->Setmemory_limit(CHECKER_RUN_MEMORY);
    checker->Run();
    while (checker->Getresult()=="Compile Error") {
        checker->Setcompiled(false);
        checker->Run();
    }
    if (checker->Getresult()!="Normal") {
        LOG("Invalid Data");
        return 1;
    }
    LOG("Valid Data");
    return 0;
}

int run_program(Program * program,string in_filename,string src,int language) {
    if (src=="") return -1;
    program->Setlanguage(language);
    program->Setsource(src);
    program->Sethas_input(true);
    program->Setin_filename(in_filename);
    program->Setout_filename(tmpnam());
    program->Settotal_time_limit(bott->Gettime_limit());
    program->Setcase_time_limit(bott->Getcase_limit());
    program->Setmemory_limit(bott->Getmemory_limit());
    program->Run();
    if (program->Getresult()!="Normal") return 1;
    return 0;
}

string Inttostring(int x) {
    char tt[100];
    sprintf(tt,"%d",x);
    string t=tt;
    return t;
}

void send_result(string filename) {
    LOG("Sending "+filename);
    int source=open(filename.c_str(),O_RDONLY),num;
    memset(buffer,0,sizeof(buffer));
    while((num=read(source,buffer,sizeof(buffer)))>0)
        write(sockfd,buffer,num);
    close(source);
    LOG("Sent.");
}

void copyfile(string from,string to) {
    LOG("Copy "+from+" to "+to);
    system(("cp "+from+" "+to).c_str());
}

void dochallenge() {
    string inpfile=tmpnam();
    retbott.Settype(CHALLENGE_REPORT);
    datagen=NULL;
    retbott.Setcha_id(bott->Getcha_id());
    if (set_data(inpfile)) {
        //failtogen
        retbott.Setcha_result("Challenge Error");
        retbott.Setcha_detail("Generator Failed: "+datagen->Getresult());
        if (datagen!=NULL) delete datagen;
    }
    else {
        string check_result_filename=tmpnam();
        int check_stat=check_data(bott->Getpid(),inpfile,check_result_filename);
        if (check_stat==1) {
            retbott.Setcha_result("Challenge Error");
            retbott.Setcha_detail("Data Check Failed: "+checker->Getresult()+"\nChecker Output Detail:\n--------------------------\n"+Loadallfromfile(check_result_filename)+"\n--------------------------\nInvalid Data!!");
        }
        else if (check_stat==-1) {
            retbott.Setcha_result("Challenge Error");
            retbott.Setcha_detail("No Config File For Problem: "+Inttostring(bott->Getpid()));
        }
        else {
            stdprogram=new Program;
            int stdres=run_program(stdprogram,inpfile,Loadallfromfile(problem->Getsolution_filename()),problem->Getsolution_language());
            while (stdprogram->Getresult()=="Compile Error") {
                stdprogram->Setcompiled(false);
                stdprogram->Run();
                if (stdprogram->Getresult()!="Normal") stdres=1;
                else stdres=0;
            }
            usrprogram=new Program;
            int usrres=run_program(usrprogram,inpfile,bott->Getsrc(),bott->Getlanguage());
            while (usrprogram->Getresult()=="Compile Error") {
                usrprogram->Setcompiled(false);
                usrprogram->Run();
                if (usrprogram->Getresult()!="Normal") usrres=1;
                else usrres=0;
            }
            if (stdres!=0) {
                retbott.Setcha_result("Challenge Error");
                retbott.Setcha_detail("Standard Program Failed: "+stdprogram->Getresult());
            }
            else if (usrres!=0) {
                retbott.Setcha_result("Challenge Success");
                string newf="cha_"+tmpnam();
                copyfile(stdprogram->Getin_filename(),"testdata/"+Inttostring(bott->Getpid())+"/"+newf+".in");
                copyfile(stdprogram->Getout_filename(),"testdata/"+Inttostring(bott->Getpid())+"/"+newf+".out");
                retbott.Setcha_detail("Standard: "+stdprogram->Getresult()+"  User: "+usrprogram->Getresult());
            }
            else {
                cmp=new Comparator;
                cmp->Setin_filename(inpfile);
                cmp->Setout_filename(usrprogram->Getout_filename());
                cmp->Setstdout_filename(stdprogram->Getout_filename());
                cmp->Setsrc_filename(usrprogram->Getsrc_filename());
                cmp->Setisspj(bott->Getspj());
                cmp->Setpid(bott->Getpid());
                int cres=cmp->Compare();
                if (cres==AC_STATUS) {
                    retbott.Setcha_result("Challenge Failed");
                    retbott.Setcha_detail("Same Result.");
                }
                else if (cres==PE_STATUS) {
                    retbott.Setcha_result("Challenge Success");
                    retbott.Setcha_detail("Presentation Error.\nComparator Output Detail:\n--------------------------\n"+cmp->Getdetail()+"\n--------------------------");
                    string newf="cha_"+tmpnam();
                    copyfile(stdprogram->Getin_filename(),"testdata/"+Inttostring(bott->Getpid())+"/"+newf+".in");
                    copyfile(stdprogram->Getout_filename(),"testdata/"+Inttostring(bott->Getpid())+"/"+newf+".out");
                }
                else if (cres==JE_STATUS) {
                    retbott.Setcha_result("Challenge Failed");
                    retbott.Setcha_detail("No SPJ.");
                }
                else {
                    retbott.Setcha_result("Challenge Success");
                    string newf="cha_"+tmpnam();
                    copyfile(stdprogram->Getin_filename(),"testdata/"+Inttostring(bott->Getpid())+"/"+newf+".in");
                    copyfile(stdprogram->Getout_filename(),"testdata/"+Inttostring(bott->Getpid())+"/"+newf+".out");
                    retbott.Setcha_detail("Wrong Answer.\nComparator Output Detail:\n--------------------------\n"+cmp->Getdetail()+"\n--------------------------");
                }
                delete cmp;
            }
            delete stdprogram;
            delete usrprogram;
        }
        delete problem;
        delete checker;
        if (datagen!=NULL) delete datagen;
    }
    retbott.Setout_filename("cha_results/"+Inttostring(retbott.Getcha_id()));
    retbott.toFile();
    send_result(retbott.Getout_filename());
}

bool getalldata(int pid,vector <string> & files,int type) {
    string tmp_file=tmpnam();
    string base;
    if (type==DO_PRETEST) system(("ls testdata/"+Inttostring(pid)+"/pretest_*.in >"+tmp_file).c_str());
    else system(("ls testdata/"+Inttostring(pid)+"/*.in >"+tmp_file).c_str());
    string tmp_string=Loadallfromfile(tmp_file);
    system(("rm "+tmp_file).c_str());
    strcpy(buffer,tmp_string.c_str());
    char *pch;
    pch=strtok(buffer,"\t\n\r ");
    while (pch!=NULL) {
        base=((string)pch).substr(0,strlen(pch)-3);
        LOG("Checking "+base);
        files.push_back(base);
        if (!Checkfile(base+".out")) return false;
        pch=strtok(NULL,"\t\n\r ");
    }
    return true;
}

void dojudge(int type) {
    LOG("Runid: "+Inttostring(bott->Getrunid())+" Type: "+Inttostring(type));
    vector <string> in_files;
    if (!getalldata(bott->Getpid(),in_files,type)||in_files.size()==0) {
        retbott.Settype(RESULT_REPORT);
        retbott.Setrunid(bott->Getrunid());
        retbott.Settime_used(0);
        retbott.Setmemory_used(0);
        retbott.Setresult("Judge Error (No Data)");
        retbott.Setout_filename("results/"+Inttostring(retbott.Getrunid()));
        retbott.toFile();
        send_result(retbott.Getout_filename());
        return;
    }
    string inpfile=tmpnam();
    string stdout_file=tmpnam();
    retbott.Settype(RESULT_REPORT);
    retbott.Setrunid(bott->Getrunid());
    usrprogram=new Program;
    usrprogram->Setlanguage(bott->Getlanguage());
    usrprogram->Setsource(bott->Getsrc());
    usrprogram->Sethas_input(true);
    usrprogram->Setin_filename(inpfile);
    usrprogram->Setout_filename(tmpnam());
    if (type==NEED_JUDGE) usrprogram->Settotal_time_limit(bott->Gettime_limit());
    else usrprogram->Settotal_time_limit(bott->Getcase_limit()*in_files.size());
    usrprogram->Setcase_time_limit(bott->Getcase_limit());
    usrprogram->Setmemory_limit(bott->Getmemory_limit());
    bool aced,peed,jeed=false,has=false;
    aced=peed=true;
    cmp=new Comparator;
    cmp->Setin_filename(inpfile);
    cmp->Setout_filename(usrprogram->Getout_filename());
    cmp->Setisspj(bott->Getspj());
    cmp->Setpid(bott->Getpid());
    for (unsigned int i=0;i<in_files.size();i++) {
        system(("cp "+in_files[i]+".in "+inpfile).c_str());
        LOG("Do "+in_files[i]);
        usrprogram->Run();
        if (usrprogram->Getresult()!="Normal") {
            retbott.Setresult(usrprogram->Getresult());
            has=true;
            break;
        }
        else {
            system(("cp "+in_files[i]+".out "+stdout_file).c_str());
            system(("chmod 755 "+stdout_file).c_str());
            system(("chmod 755 "+inpfile).c_str());
            cmp->Setstdout_filename(stdout_file);
            cmp->Setsrc_filename(usrprogram->Getsrc_filename());
            int cres=cmp->Compare();
            system(("rm "+stdout_file).c_str());
            system(("chmod 600 "+inpfile).c_str());
            if (cres==PE_STATUS) aced=false;
            else if (cres==JE_STATUS) {
                jeed=false;
                break;
            }
            else if (cres!=AC_STATUS) {
                aced=peed=false;
                break;
            }
        }
    }
    retbott.Setce_info(usrprogram->Getce_info());
    retbott.Settime_used(usrprogram->Gettime_used());
    retbott.Setmemory_used(usrprogram->Getmemory_used());
    if (has) retbott.Setresult(usrprogram->Getresult());
    else if (jeed) retbott.Setresult("Judge Error (No SPJ)");
    else if (aced) {
        if (type==DO_PRETEST) retbott.Setresult("Pretest Passed");
        else retbott.Setresult("Accepted");
    }
    else if (peed) retbott.Setresult("Presentation Error");
    else retbott.Setresult("Wrong Answer");
    retbott.Setout_filename("results/"+Inttostring(retbott.Getrunid()));
    retbott.toFile();
    delete cmp;
    delete usrprogram;
    send_result(retbott.Getout_filename());
}

int main(int argc, char *argv[]) {
    init();
    //init_error();
    connect_server(argc,argv);
    while (1)
    {
        usleep(5000);
        int recstat=receive_command();
        if (recstat==-1) {
            reconnect();
            continue;
        }
        else if (recstat==0) continue;
        parse_bott();
        if (bott->Gettype()==DO_CHALLENGE) dochallenge();
        else if (bott->Gettype()==NEED_JUDGE||bott->Gettype()==DO_TESTALL||bott->Gettype()==DO_PRETEST) dojudge(bott->Gettype());
        delete bott;
    }
    return 0;
}

