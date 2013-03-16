#include "PConfig.h"

PConfig::PConfig()
{
    //ctor
}

string PConfig::Inttostring(int x) {
    char tt[100];
    sprintf(tt,"%d",x);
    string t=tt;
    return t;
}

PConfig::PConfig(int pid)
{
    basedir=(string)"testdata/"+Inttostring(pid)+"/challenge/";
    FILE * fin=fopen((basedir+"config.ini").c_str(),"r");
    error=false;
    if (fin==NULL) {
        error=true;
        return;
    }

    char ts1[1000],ts2[1000];
    config.clear();
    while (fscanf(fin,"%s = %s",ts1,ts2)!=EOF) {
        config[ts1]=ts2;
    }

    data_checker_filename=basedir+config["data_checker_filename"];
    data_checker_language=atoi(config["data_checker_language"].c_str());
    solution_filename=basedir+config["solution_filename"];
    solution_language=atoi(config["solution_language"].c_str());
    fclose(fin);
}

PConfig::~PConfig()
{
    //dtor
}
