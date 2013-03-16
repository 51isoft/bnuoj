#ifndef PROGRAM_H
#define PROGRAM_H

#include "chaclient.h"

class Program
{
    public:
        Program();
        virtual ~Program();
        string Getsrc_filename() { return src_filename; }
        void Setsrc_filename(string val) { src_filename = val; }
        string Getexc_filename() { return exc_filename; }
        void Setexc_filename(string val) { exc_filename = val; }
        string Getin_filename() { return in_filename; }
        void Setin_filename(string val) { in_filename = val; }
        string Getout_filename() { return out_filename; }
        void Setout_filename(string val) { out_filename = val; }
        bool Getcompiled() { return compiled; }
        void Setcompiled(bool val) { compiled = val; }
        string Getcinfo_filename() { return cinfo_filename; }
        void Setcinfo_filename(string val) { cinfo_filename = val; }
        string Getce_info() { return ce_info; }
        void Setce_info(string val) { ce_info = val; }
        int Gettotal_time_limit() { return total_time_limit; }
        void Settotal_time_limit(int val) { total_time_limit = val; }
        int Getcase_time_limit() { return case_time_limit; }
        void Setcase_time_limit(int val) { case_time_limit = val; }
        int Getmemory_limit() { return memory_limit; }
        void Setmemory_limit(int val) { memory_limit = val; }
        int Gettime_used() { return time_used; }
        void Settime_used(int val) { time_used = val; }
        int Getmemory_used() { return memory_used; }
        void Setmemory_used(int val) { memory_used = val; }
        int Getlanguage() { return language; }
        void Setlanguage(int val) { language = val; }
        string Getsource() { return source; }
        void Setsource(string val) { source = val; }
        string Getresult() { return result; }
        void Setresult(string val) { result = val; }
        int Getcompile_time_limit() { return compile_time_limit; }
        void Setcompile_time_limit(int val) { compile_time_limit = val; }
        bool Gethas_input() { return has_input; }
        void Sethas_input(bool val) { has_input = val; }
        bool Getcheck_exit_status() { return check_exit_status; }
        void Setcheck_exit_status(bool val) { check_exit_status = val; }
        string Loadallfromfile(string filename,int limit=-1);
        void Run();
        int Compile();
        void Savetofile(string filename,string content);
        bool Checkfile(string);
        string Inttostring(int);
    protected:
    private:
        int Excution();
        void Deletefile(string);
        string base_filename;
        string src_filename;
        string exc_filename;
        string in_filename;
        string out_filename;
        string err_filename;
        string res_filename;
        bool compiled;
        string cinfo_filename;
        string ce_info;
        int total_time_limit;
        int case_time_limit;
        int memory_limit;
        int time_used;
        int memory_used;
        int language;
        string source;
        string result;
        int compile_time_limit;
        bool has_input;
        bool check_exit_status;
        static bool para_inited;
};

#endif // PROGRAM_H
