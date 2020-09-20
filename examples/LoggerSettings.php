<?php
include 'vendor/autoload.php';
use \Wsudo\SQLitePDO;
/* 
    Set Errors Mode Logger To Get (FATALS & WARNING) Errors In The File
*/

\Wsudo\SQLitePDO\Logger::Configure(
    \Wsudo\SQLitePDO\Logger::ERRORS_MODE ,
);

/* 
    Set Safe Mode Logger To Get (Loader && Secessfully) logs In The File
*/

\Wsudo\SQLitePDO\Logger::Configure(
    \Wsudo\SQLitePDO\Logger::SECSESSFULLY_MODE ,
);

/* 
    Set OFF Mode Logger To OFF any logs saver in the file
*/

\Wsudo\SQLitePDO\Logger::Configure(
    \Wsudo\SQLitePDO\Logger::LOGGER_OFF ,
);


/* ========================================================== */

/* 
    Set Logger File Size Limit
*/

\Wsudo\SQLitePDO\Logger::Configure(
    \Wsudo\SQLitePDO\Logger::ERRORS_MODE ,
    \Wsudo\SQLitePDO\Logger::SAFE_SIZE /* 100000 bytes is safe sizes */
);
/* 
    when the log file size go more than the limit size , 
    logger file manager will delete last logs ,
    and start it again to record new logs

    *********************
    logger file size consts :
    * SAFE_SIZE   = 100000 bytes (100kbs)
    * BIG_SIZE    = 10000000 bytes (10MGs)
    * NORMAL_SIZE = 1000000 bytes (1MGs)   default
    * OFF_SIZE    = 100 bytes (0.1kbs)
*/

/* ========================================================== */

/* 
    set logger file name
*/
\Wsudo\SQLitePDO\Logger::Configure(
    \Wsudo\SQLitePDO\Logger::ERRORS_MODE ,
    \Wsudo\SQLitePDO\Logger::SAFE_SIZE ,
    'MySQLites.log' /* this is a new logger file name */
);

/* ========================================================== */


/* 
    set logger file directory
*/

\Wsudo\SQLitePDO\Logger::setLoggerDirectory( 'MySqliteLogs' /* directory name */); 

\Wsudo\SQLitePDO\Logger::Configure(
    \Wsudo\SQLitePDO\Logger::ERRORS_MODE ,
    \Wsudo\SQLitePDO\Logger::SAFE_SIZE ,
    'MySQLites.log' 
);

/* ========================================================== */

/* 
    create a some log from our self
*/
// FATAL ERROR LOG
\Wsudo\SQLitePDO\Logger::logger(
    \Wsudo\SQLitePDO\Logger::FATAL_ERROR , 
    __FILE__ , "THE SOME THINGS ARE WRONGE " 
);
// WARNING ERROR LOG
\Wsudo\SQLitePDO\Logger::logger(
    \Wsudo\SQLitePDO\Logger::WARING_ERROR , 
    __FILE__ , "THE SOME THINGS ARE WRONGE " 
);
// SECESSFULLY LOG
\Wsudo\SQLitePDO\Logger::logger(
    \Wsudo\SQLitePDO\Logger::SECSESSFULLY_LOG , 
    __FILE__ , "THE SOME THINGS ARE GOOD ! " 
);
// LOADER LOG
class TestLoaderLog extends \Wsudo\SQLitePDO\SQlitePDOMultiGenerator{
    function __construct()
    {
        \Wsudo\SQLitePDO\Logger::Logger(
            \Wsudo\SQLitePDO\Logger::LOADER_LOG ,
            __FILE__ , "the test loader logger class loaded secessfully" , __LINE__ , __FUNCTION__ , __CLASS__
        );
    }
}


/* ========================================================== */


/*  create our self some simple log */
\Wsudo\SQLitePDO\Logger::Logger("hello would !!!");

?>
