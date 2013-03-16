#ifndef JUDGER_CONN_H_INCLUDED
#define JUDGER_CONN_H_INCLUDED

#include <stdio.h>
#include <stdlib.h>
#include <assert.h>
#include <string.h>
#include <strings.h>
#include <unistd.h>
#include <pthread.h>

#include <sys/types.h>
#include <sys/fcntl.h>
#include <sys/socket.h>
#include <sys/wait.h>
#include <sys/resource.h>
#include <sys/signal.h>
#include <sys/time.h>
#include <sys/ptrace.h>
#include <sys/syscall.h>
#include <sys/user.h>

#include <netinet/in.h>

#include <arpa/inet.h>

#include <errno.h>
#include <netdb.h>

#include <string>
#include <vector>

using namespace std;

#include "restrict_syscalls.h"

#define MAX_JUDGER_NUMBER 1
#define MAX_DATA_SIZE 6553500
#define CHECK_STATUS 1
#define NEED_JUDGE 2
#define SEND_DATA 3
#define JUDGER_STATUS_REPORT 1
#define NEED_DATA 2
#define RESULT_REPORT 3
#define CPPLANG 1
#define CLANG 2
#define JAVALANG 3
#define FPASLANG 4
#define PYLANG 5
#define CSLANG 6
#define FORTLANG 7
#define PERLLANG 8
#define RUBYLANG 9
#define ADALANG 10
#define SMLLANG 11
#define AC_STATUS 0
#define CE_STATUS 1
#define RE_STATUS 2
#define WA_STATUS 3
#define TLE_STATUS 4
#define MLE_STATUS 5
#define PE_STATUS 6
#define OLE_STATUS 7
#define RF_STATUS 8
#define ND_STATUS 9
#define NJ_STATUS 10
#define JAVA_COMPILE_TIME 20
#define GCC_COMPILE_TIME 10

char judger_string[50]={0};
char logfile[200]={0};
int lowprivid=0;

void init()
{
    FILE * fin=fopen("config.ini","r");
    fscanf(fin,"%s%d%s",judger_string,&lowprivid,logfile);
    fclose(fin);
}


#endif // JUDGER_CONN_H_INCLUDED
