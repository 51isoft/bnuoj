#ifndef LOGGER_H
#define LOGGER_H

#include "chaclient.h"

class Logger
{
    public:
        /** Default constructor */
        Logger();
        /** Default destructor */
        virtual ~Logger();
        /** Access filename
         * \return The current value of filename
         */
        string Getfilename() { return filename; }
        /** Set filename
         * \param val New value to set
         */
        void Setfilename(string val) { filename = val; }
        /** Access instance
         * \return The current value of instance
         */
        static Logger * Getinstance();
        void writelog(char *);
        void writelog(const char *);
        void writelog(string);
    protected:
    private:
        string filename; //!< Member variable "filename"
        static Logger * instance; //!< Member variable "instance"
};

#endif // LOGGER_H
