#include "Logger.h"

Logger* Logger::instance = NULL;

Logger::Logger()
{
    //ctor
}

Logger * Logger::Getinstance()
{
   if (!instance) instance = new Logger;
   return instance;
}


Logger::~Logger()
{
    //dtor
}

void Logger::writelog(char* log)
{
    FILE * fp=fopen(filename.c_str(),"a");
    while (fp==NULL) fp=fopen(filename.c_str(),"a");
    if (fp!=NULL) {
        fputs(log,fp);
        fputs("\n",fp);
        fclose(fp);
    }
}

void Logger::writelog(const char* log)
{
    writelog((char*)log);
}

void Logger::writelog(string log)
{
    writelog(log.c_str());
}
