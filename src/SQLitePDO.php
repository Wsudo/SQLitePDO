<?php
namespace Wsudo\SQLitePDO;
use PDO,Exception;

use function GuzzleHttp\Promise\all;

/*
    * SQLite3 PHP PDO Generator
    * Version 1.1
    * PHP Version Must Be 7++
    * Writen By @Wsudo
    * Our Team @Win_Tab
    * License FREE
    * TOOLs = Logger , MultiGenerator
*/
/* 
    Logger Class All Static
*/
class Logger{
    /* logger Modes Consts */
    const FATAL_ERROR      = ['TEXT' => "FATAL ERROR" , 'CONST_TYPE' => "SAVE_FATAL_ERRORS"];
    const WARING_ERROR     = ['TEXT' => "WARNING" , 'CONST_TYPE' => "SAVE_WARNING_ERRORS" ];
    const SECSESSFULLY_LOG = ['TEXT' => "SECESSFULLY OPERATION" , 'CONST_TYPE' => "SAVE_SECSSESFULLY_LOGS"];
    const LOADER_LOG       = ['TEXT' => "DATABASE LOADED" , 'CONST_TYPE' => "SAVE_LOADER_LOGS"];
    /* logger modes consts */
    const ERRORS_MODE      = ['SAVE_FATAL_ERRORS' => TRUE , 'SAVE_WARNING_ERRORS' => TRUE , 'SAVE_SECSSESFULLY_LOGS' => FALSE , 'SAVE_LOADER_LOGS' => FALSE];
    const SECSESSFULLY_MODE= ['SAVE_FATAL_ERRORS' => FALSE , 'SAVE_WARNING_ERRORS' => FALSE , 'SAVE_SECSSESFULLY_LOGS' => TRUE , 'SAVE_LOADER_LOGS' => TRUE];
    const LOADER_MODE      = ['SAVE_FATAL_ERRORS' => FALSE , 'SAVE_WARNING_ERRORS' => FALSE , 'SAVE_SECSSESFULLY_LOGS' => FALSE , 'SAVE_LOADER_LOGS' => TRUE];
    const ALL_LOGS         = ['SAVE_FATAL_ERRORS' => TRUE , 'SAVE_WARNING_ERRORS' => TRUE , 'SAVE_SECSSESFULLY_LOGS' => TRUE , 'SAVE_LOADER_LOGS' => TRUE];
    const LOGGER_OFF       = ['SAVE_FATAL_ERRORS' => FALSE , 'SAVE_WARNING_ERRORS' => FALSE , 'SAVE_SECSSESFULLY_LOGS' => FALSE , 'SAVE_LOADER_LOGS' => FALSE];
    /* logger saver locks */
    static public $SAVE_FATAL_ERRORS      = TRUE;
    static public $SAVE_WARNING_ERRORS    = FALSE;
    static public $SAVE_SECSSESFULLY_LOGS = FALSE;
    static public $SAVE_LOADER_LOGS       = FALSE;
    /* logger file & directory manager */
    static public $LOGGER_FILE           = 'SQLitePDO.log';
    static public $HANDLE_TO_DIR         = FALSE;
    static public $LOGGER_FILE_DIRECTORY = __DIR__;
    static public $MAX_LOGGER_FILE_SIZE  = 1000000;
    /* logger max file size setters (byte)*/
    const BIG_SIZE    = 10000000;
    const NORMAL_SIZE = 1000000;
    const SAFE_SIZE   = 100000;
    const OFF_SIZE    = 1000;
    /* logger configs generator method */
    static function Configure(array $settings =[] , int $logger_file_size = 1000000 , $logger_file_name = 'SQLitePDO.log' )
    {
        if(is_array($settings) && count($settings)>1)
        {
            foreach($settings as $index => $status)
            {
                self::${$index}=$status;
            }
        }else{
            foreach(self::ERRORS_MODE as $index => $status)
            {
                self::${$index}=$status;
            }
        }
        if(is_int($logger_file_size))
        {
            self::$MAX_LOGGER_FILE_SIZE = $logger_file_size;
        }
        if(is_string($logger_file_name))
        {
            self::$LOGGER_FILE = trim($logger_file_name);
        }
    }
    /* logger directory setter method */
    static function setLoggerDirectory(string $LOGGER_FILE_DIRECTORY = '')
    {
        if(is_string($LOGGER_FILE_DIRECTORY) && !empty($LOGGER_FILE_DIRECTORY))
        {
            if(!is_dir($LOGGER_FILE_DIRECTORY))
            {
                mkdir($LOGGER_FILE_DIRECTORY);
            }
            self::$LOGGER_FILE_DIRECTORY = $LOGGER_FILE_DIRECTORY;
            self::$HANDLE_TO_DIR = TRUE;
            return true;
        }else{
            return false;
        }
    }
    /* logger file manager method (size checker and Deleter) */
    static function loggerFileManager(string $FILE_PATH = '')
    {
        if(is_string($FILE_PATH) && file_exists($FILE_PATH))
        {
            if(sizeof($FILE_PATH) >= self::$MAX_LOGGER_FILE_SIZE)
            {
                @unlink($FILE_PATH);
                @touch($FILE_PATH);
                return true;
            }else{
                return false;
            }
        }
    }
    /* logger get logs file size */
    static function getLoggerSize()
    {
        $dirname = (self::$HANDLE_TO_DIR ===true) ? self::$LOGGER_FILE_DIRECTORY : __DIR__;
        $path = $dirname."/".self::$LOGGER_FILE;
        return sizeof($path) ?? false;
    }
    /* get logger text */
    static function getText($type = 'unknown' , $file = 'unknown' , $message = 'unknown' , $line = 0 , $func = 'unkown' , $class = 'unkown')
    {
        $msg = "\n\n= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =";
        $msg .= "\n ".date("[ Y - m - d  h : i : s ]") ." ".$type;
        $msg .= "\nFILE   --->  " . $file;
        $msg .= "\nMSG    --->  " . str_replace("\n" , "\n            " , $message);
        $msg .= "\nLINE   --->  " . $line ;
        $msg .= "\nMETHOD --->  " . $func ;
        $msg .= "\nCLASS  --->  " .$class ;
        return $msg;
    }
    /*  logger saver (file) */
    static function Saver($msg)
    {
        $dirname = (self::$HANDLE_TO_DIR ===true) ?(self::$LOGGER_FILE_DIRECTORY) : __DIR__;
        if($dirname==__DIR__)
        {
            $path = self::$LOGGER_FILE;
        }else{
            $path = $dirname."/".self::$LOGGER_FILE;
        }
        if(!file_exists($path))
        {
            @touch($path);
        }
        self::loggerFileManager($path);
        $logs = file_get_contents($path);
        return file_put_contents($path , $logs . $msg);
    }
    /* logger generator method (Main) */
    static function Logger($method , $file = 'unknown' , $message = "unknown" , $line = 0 , $func = "unknown" , $class = "unknown")
    {
        if(is_string($method))
        {
            self::Saver(self::getText(
                " SOURRCE LOGGER " , $file , $method , $line , $func , $class
            ));
        }else if(is_array($method) && count($method)==2 && isset($method['CONST_TYPE']))
        {
            if(self::${$method['CONST_TYPE']}===TRUE)
            {
                self::Saver(self::getText(
                    $method['TEXT'], $file , $message , $line , $func , $class
                ));
            }
        }
    }
    /* get logger mode */
    static function getLoggerMode($logger=[])
    {
        return $logger;
    }
}


/* 
    SQlite Generator Class
    Single connector
*/
class SQLitePDOGenerator extends PDO {
    /* pdo connected object */
    public $PDO;
    /* sqlite file name */
    public $SQLITE_FILE_NAME;
    /* current query */
    public $Query;
    /* constructor and connector */
    function __construct(string $file_name = '' , array $logger = [] , int $logger_max_size = 0 , string $logger_file_name = '')
    {
        if(!is_string($file_name) || empty($file_name))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::FATAL_ERROR ,
                __FILE__ ,
                "THE SQLITE FILE NAME IS NOT STRING " ,
                __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        if(is_array($logger) && count($logger)===4)
        {
            if(is_int($logger_max_size) && $logger_max_size===0)
            {
                $logger_size = \Wsudo\SQLitePDO\Logger::NORMAL_SIZE;
            }else{
                $logger_size = $logger_max_size;
            }
            if(is_string($logger_file_name) && !empty($logger_file_name))
            {
                $logger_file = $logger_file_name;
            }else{
                $logger_file = \Wsudo\SQLitePDO\Logger::$LOGGER_FILE;
            }
            \Wsudo\SQLitePDO\Logger::Configure(
                \Wsudo\SQLitePDO\Logger::getLoggerMode($logger) , $logger_size , $logger_file
            );
        }else{
            \Wsudo\SQLitePDO\Logger::Configure(
                \Wsudo\SQLitePDO\Logger::ERRORS_MODE , \Wsudo\SQLitePDO\Logger::NORMAL_SIZE
            );
        }
        $this->SQLITE_FILE_NAME = $file_name;
        try{
            parent::__construct("sqlite:".$file_name);
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::SECSESSFULLY_LOG ,
                __FILE__ , "$file_name CONNECTED " , __LINE__ , __FUNCTION__ , __CLASS__
            );
        }catch(Exception $e)
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::FATAL_ERROR ,
                __FILE__ ,
                "PDO CAN NOT CONNECT :" .$e->__toString() ,
                __LINE__ , __FUNCTION__ , __CLASS__
            );
        }
        $this->PDO = $this;
        return $this->PDO;
    }
    /* run query method */
    function runQuery($Query='')
    {
        if(!is_string($Query) || empty($Query))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "CAN NOT RUN EMPTY/ARRAY QUERY" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        try{
            return $this->PDO->query($Query);
        }catch(Exception $e)
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "CAN NOT RUN QUERY :".$Query , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
    }
    /*  run exec method */
    function runExec($Query)
    {
        if(!is_string($Query) || empty($Query))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "CAN NOT EXEC EMPTY/ARRAY QUERY" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        try{
            return $this->PDO->exec($Query);
        }catch(Exception $e)
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "CAN NOT EXEC QUERY :".$Query , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
    }
    /* create table method */
    function createTable(string $table ="" , array $data =[])
    {
        if(!is_string($table) || empty($table))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "TABLE NAME IS NOT STRING" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        if(!is_array($data) || count($data) ===0)
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "DATA OF TABLE NAME $table IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        $Query = "CREATE TABLE IF NOT EXISTS ".$table."(".rtrim(implode(",",$data) ,",").")";
        return $this->runExec($Query);
    }
    /* insert to tables method */
    function insert(string $table ='' , array $data =[])
    {
        if(!is_string($table) || empty($table))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "TABLE NAME IS NOT STRING" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        if(!is_array($data) || count($data) ===0)
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "DATA OF INSERT $table IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        $Query = "INSERT INTO ".$table."(".implode(",",array_keys($data)).") VALUES('". implode("','",array_values($data))."')";
        return $this->runExec($Query);
    }
    /* update rows method */
    function update(string $table  , array $data =[] , string $where ='')
    {
        if(!is_string($table) || empty($table))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "TABLE NAME IS NOT STRING" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        if(!is_array($data) || count($data) ===0)
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "DATA OF UPDATE $table IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        $Query = "UPDATE ".$table ." SET ";
        $str = '';
        foreach($data as $k => $v)
        {
            $str.= "$k = '$v' , ";
        }
        $str = rtrim($str , " ,");
        $Query .= $str;
        if(!empty($where) && is_string($where))
        {
            $Query .= " WHERE " . $where;
        }
        return $this->runQuery($Query);
    }
    /* delete rows method */
    function delete(string $table ='' , string $where ='')
    {
        if(!is_string($table) || empty($table))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "TABLE NAME IS NOT STRING" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        $Query = "DELETE FROM ".$table;
        if(!empty($where) && is_string($where))
        {
            $Query .= " WHERE ". $where;
        }
        return $this->runQuery($Query);
    }
    /* select rows method */
    function select(string $table , string $where = '' , string $row ="*")
    {
        if(!is_string($table) || empty($table))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR , __FILE__ , "TABLE NAME IS NOT STRING" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
        $Query = "SELECT rowid,".$row. " FROM ". $table;
        if(!empty($where) && is_string($where))
        {
            $Query = " WHERE " . $where;
        }
        return $this->runQuery($Query);
    }
}




/* multi sqlite generator class */
class SQlitePDOMultiGenerator
{
    public $ALL_SQLITES =[];
    public $ALL_SQLITES_FILES = [];
    public $DIRECTORY;
    public $IS_HANDLE_DIR = FALSE;
    function __construct( string $directory = '' , $handle_log_dir = false)
    {
        if(!empty($directory) && is_string($directory))
        {
            $this->IS_HANDLE_DIR = true ;
            $this->DIRECTORY = trim($directory);
            if(!is_dir($this->DIRECTORY))
            {
                mkdir($this->DIRECTORY);
            }
        }
        if($handle_log_dir===true)
        {
            \Wsudo\SQLitePDO\Logger::$LOGGER_FILE_DIRECTORY = $directory;
            \Wsudo\SQLitePDO\Logger::$HANDLE_TO_DIR = true;
        }
        return true;
    }
    function createMultiSQLites(array $data = [] , bool $returnSQLites = true )
    {
         if(!is_array($data ) || $data==[] || count(array_keys($data))!==count(array_values($data)))
         {
             \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::FATAL_ERROR , __FILE__ , "THE DATA MULTI SQLITES IS NOT VALID ", __LINE__ , __FUNCTION__ , __CLASS__ 
             );
             return false;
         }
         $ALL_SQLITES = [];
         $dir = __DIR__;
         if($this->IS_HANDLE_DIR===true)
         {
             $dir=$this->DIRECTORY;
         }
         foreach($data as $index => $file_name)
         {
             if(!isset($data[$index]) || is_array($file_name) || !is_string($file_name) || empty($file_name))
             {
                \Wsudo\SQLitePDO\Logger::Logger(
                    \Wsudo\SQLitePDO\Logger::FATAL_ERROR , __FILE__ , "THE FILENAME IS NOT VALID TO CREATE INDEX MULTI SQLITES ", __LINE__ , __FUNCTION__ , __CLASS__ 
                 );
                 continue;
             }
             $ALL_SQLITES[$index] =new \Wsudo\SQLitePDO\SQLitePDOGenerator($dir."/".$file_name , \Wsudo\SQLitePDO\Logger::ALL_LOGS);
             $this->ALL_SQLITES_FILES[$index] = $dir."/".$file_name;
         }
         if(count(array_keys($ALL_SQLITES))===0)
         {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::FATAL_ERROR , __FILE__ , "MULTI SQLITES CREATOR RETURN EMPTY", __LINE__ , __FUNCTION__ , __CLASS__ 
             );
             return false;
         }
         $this->ALL_SQLITES = $ALL_SQLITES;
         $returnSQLites = boolval($returnSQLites);
         if($returnSQLites===true)
         {
             return $this->ALL_SQLITES;
         }else{
             return true;
         }
    }
    /* get one index sqlites */
    function getIndexSQLite($index)
    {
        if(!is_int($index) && !is_string($index))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT GET INDEX OF SQLITES : INDEX PARAM IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
        if(isset($this->ALL_SQLITES[$index]))
        {
            return $this->ALL_SQLITES[$index];
        }else{
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT GET INDEX OF SQLITES : INDEX PARAM UNSETED ($index)" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return false;
        }
    }
    /* set one sqlite to sqlites */
    function setIndexSQLite($index_name , $sqlite_object , $returnSQLite = false)
    {
        if(!is_int($index_name) || !is_string($index_name))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT SET INDEX TO SQLITES : INDEX PARAM IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
        if(!is_object($sqlite_object))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT SET INDEX TO SQLITES : OBJECT PARAM IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
        if(!isset($this->ALL_SQLITES[$index_name]))
        {
            $this->ALL_SQLITES[$index_name] = $sqlite_object;
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::SECSESSFULLY_LOG , __FILE__ , "ADD NEW INDEX TO SQLITES IS SECESSFULLY : $index_name" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            if($returnSQLite===true)
            {
                return $this->ALL_SQLITES;
            }else{
                return true;
            }
        }else{
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::FATAL_ERROR ,__FILE__ , "CAN NOT SET INDEX TO SQLITES : INDEX IS EXISTS $index_name" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
    }
    /* create and add new sqlite object index to sqlites */
    function createNewSQLite($index_name , $file_name , $returnSQLites = false)
    {
        if(!is_int($index_name) || !is_string($index_name))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT SET INDEX TO SQLITES : INDEX PARAM IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
        if(!is_string($file_name)  || empty($file_name))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT SET INDEX TO SQLITES : INDEX PARAM IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
        $dir = __DIR__;
        if($this->IS_HANDLE_DIR===true)
        {
            $dir = $this->DIRECTORY;
        }
        if(!isset($ALL_SQLITES[$index_name]))
        {
            $path = $dir."/".$file_name;
            $ALL_SQLITES[$index_name]= new \Wsudo\SQLitePDO\SQLitePDOGenerator($path , \Wsudo\SQLitePDO\Logger::ERRORS_MODE , \Wsudo\SQLitePDO\Logger::BIG_SIZE);
            $this->ALL_SQLITES = $ALL_SQLITES;
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::SECSESSFULLY_LOG ,__FILE__ , "NEW SQLITE CREATE AND ADDED TO SQLITES INDEXES : $index_name" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            if($returnSQLites===true)
            {
                return $this->ALL_SQLITES;
            }else{
                return true;
            }
        }else{
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT CREATE SQLITE NEW OBJECT INDEX TO SQLITES : INDEX IS EXISTS $index_name" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
    }
    /* unset index or sqlite from sqlites */
    function unsetIndexSQLite($index_name , $returnSQLites =false)
    {
        if(!is_int($index_name) || !is_string($index_name))
        {
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT DEL INDEX FROM SQLITES : INDEX PARAM IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE;
        }
        if(isset($this->ALL_SQLITES[$index_name]))
        {
            $ALL_SQLITES = $this->ALL_SQLITES;
            unset($ALL_SQLITES[$index_name]);
            $this->ALL_SQLITES=$ALL_SQLITES;
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::SECSESSFULLY_LOG ,__FILE__ , "INDEX UNSETED SECESSFULLY $index_name" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            if($returnSQLites===true)
            {
                return $this->ALL_SQLITES;
            }else{
                return true;
            }
        }else{
            \Wsudo\SQLitePDO\Logger::Logger(
                \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT DEL/UNSET INDEX FROM SQLITES : INDEX IS NOT EXISTS" , __LINE__ , __FUNCTION__ , __CLASS__
            );
            return FALSE; 
        }
    }
    /* unset all sqlites */
    function unsetAllSQLites()
    {
        $this->ALL_SQLITES = [];
        \Wsudo\SQLitePDO\Logger::Logger(
            \Wsudo\SQLitePDO\Logger::SECSESSFULLY_LOG ,__FILE__ , "ALL SQLITES UNSETED" , __LINE__ , __FUNCTION__ , __CLASS__
        );
        return true;
    }
    /* unset multi sqlites indexes */
    function unsetMultiIndexSQlites(array $SQLitesIndexes =[] , $returnSQLites = false)
    {
        if($SQLitesIndexes==[] || !is_array($SQLitesIndexes))
        {
            return false;
        }
        $ALL_SQLITES=$this->ALL_SQLITES;
        foreach($SQLitesIndexes as $index)
        {
            if(is_string($index) || is_int($index))
            {
                if(isset($ALL_SQLITES[$index]))
                {
                    $ALL_SQLITES = $this->unsetIndexSQLite($index , TRUE);
                }else{
                    \Wsudo\SQLitePDO\Logger::Logger(
                        \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT MULTI DEL/UNSET INDEX FROM SQLITES : INDEX IS NOT EXISTS $index" , __LINE__ , __FUNCTION__ , __CLASS__
                    );
                }
            }else{
                \Wsudo\SQLitePDO\Logger::Logger(
                    \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT MULTI DEL/UNSET INDEX FROM SQLITES : INDEX IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
                );
            }
        }
        $this->ALL_SQLITES = $ALL_SQLITES;
        if($returnSQLites===true)
        {
            return $this->ALL_SQLITES;
        }else{
            return true;
        }
    }
    /* multi adder indexes sqlites */
    function setMultiIndexSQLites(array $SQLites =[])
    {
        if($SQLites==[] || !is_array($SQLites))
        {
            return false;
        }
        $ALL_SQLITES = $this->ALL_SQLITES;
        foreach($SQLites as $index => $object)
        {
            if((is_int($index) || is_string($index)) && is_object($object))
            {
                $ALL_SQLITES = $this->setIndexSQLite($index , $object , true);
            }else{
                \Wsudo\SQLitePDO\Logger::Logger(
                    \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT MULRI SET INDEX TO SQLITES : INDEX/OBJECT IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
                );
            }
        }
    }
    /* multi index creator */
    function multiCreateNewSQlite(array $data =[] , $returnSQLites =false)
    {
        if($data==[] || !is_array($data))
        {
            return false;
        }
        $ALL_SQLITES = $this->ALL_SQLITES;
        foreach($data as $ary)
        {
            if(is_array($ary) && isset($ary[0]) && (is_int($ary[0]) || is_string($ary[0])) && isset($ary[1]) && (is_int($ary[1]) || is_string($ary[1])))
            {
                $ALL_SQLITES = $this->createNewSQLite($ary[0] , $ary[1] , true);
            }else{
                \Wsudo\SQLitePDO\Logger::Logger(
                    \Wsudo\SQLitePDO\Logger::WARING_ERROR ,__FILE__ , "CAN NOT MULTI CREATE INDEX TO SQLITES : INDEX/FILE NAME  IS NOT VALID" , __LINE__ , __FUNCTION__ , __CLASS__
                );
            }
        }
        $this->ALL_SQLITES=$ALL_SQLITES;
        if($returnSQLites ===true)
        {
            return $this->ALL_SQLITES;
        }else{
            return true;
        }
    }
}
?>
