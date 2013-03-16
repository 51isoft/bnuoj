#ifndef SERVER_CONN_H_INCLUDED
#define SERVER_CONN_H_INCLUDED

#include <stdio.h>
#include <stdlib.h>
#include <assert.h>
#include <string.h>
#include <strings.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/fcntl.h>
#include <sys/socket.h>
#include <sys/wait.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <sys/time.h>
#include <errno.h>
#include <netdb.h>
#include <pthread.h>
#include <mysql/mysql.h>
#include <string>
#include <map>

using namespace std;

#define MAX_JUDGER_NUMBER 255
#define MAX_DATA_SIZE 855350
#define CHECK_STATUS 1
#define NEED_JUDGE 2
#define SEND_DATA 3
#define DO_CHALLENGE 4
#define DO_PRETEST 5
#define DO_TESTALL 6
#define JUDGER_STATUS_REPORT 1
#define NEED_DATA 2
#define RESULT_REPORT 3
#define CHALLENGE_REPORT 4
#define CPPLANG 1
#define CLANG 2
#define JAVALANG 3
#define FPASLANG 4
#define AC_STATUS 0
#define CE_STATUS 1
#define RE_STATUS 2
#define WA_STATUS 3
#define TLE_STATUS 4
#define MLE_STATUS 5
#define PE_STATUS 6

char db_ip[50]={0};
char db_user[50]={0};
char db_pass[50]={0};
char db_table[50]={0};
char judger_string[100]={0};
char submit_string[100]={0};
char rejudge_string[100]={0};
char error_string[100]={0};
char challenge_string[100]={0};
char pretest_string[100]={0};
char testall_string[100]={0};
char logfile[100]={0};
int server_port;

void init()
{
    map <string,string> config;
    srand(time(NULL));
    FILE * fin=fopen("config.ini","r");
    char ts1[1000],ts2[1000];
    while (fscanf(fin,"%s = %s",ts1,ts2)!=EOF) {
        config[ts1]=ts2;
    }
    strcpy(db_ip,config["database_ip"].c_str());
    strcpy(db_user,config["database_user"].c_str());
    strcpy(db_pass,config["database_password"].c_str());
    strcpy(db_table,config["database_table"].c_str());
    strcpy(judger_string,config["judge_connect_string"].c_str());
    strcpy(submit_string,config["submit_string"].c_str());
    strcpy(rejudge_string,config["rejudge_string"].c_str());
    strcpy(error_string,config["error_rejudge_string"].c_str());
    strcpy(challenge_string,config["challenge_string"].c_str());
    strcpy(pretest_string,config["pretest_string"].c_str());
    strcpy(testall_string,config["test_all_string"].c_str());
    strcpy(logfile,config["log_file"].c_str());
    server_port=atoi(config["port_listen"].c_str());
    fclose(fin);
}


#endif // SERVER_CONN_H_INCLUDED
