#include <stdio.h>
#include <string.h>
#include <stdlib.h>

char a[100],b[100];
char c[]="curl \"http://localhost:8888/contest/crawler/uvalive.php?from=%s&to=%s\" -o tmpfile.txt";
char cmd[1000];

int main() {
    freopen("failuva.txt","r",stdin);
    while (scanf("%s%s",&a,&b)!=EOF) {
        sprintf(cmd,c,b,b);
//        printf("%s\n",cmd);
        system(cmd);
    }
    return 0;
}
