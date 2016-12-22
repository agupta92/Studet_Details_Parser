<?php
/** Include path **/
set_include_path('PHPExcel-1.8/Classes/');
include_once 'PHPExcel/IOFactory.php';
include_once 'config.php';
/*
*@author    Ankit Gupta <agupta_92@yahoo.co.in>
*Class to Make Database connection and Import CSV Data vi PHPExcel Extesion
*/
class csvimport
{
    var $dbhost = DBHOST;   // mysql database server
    var $dbuser = DBUSER;       // database username
    var $dbpass = DBPASSWORD;       // database password
    var $dbname = DBNAME;       // database name
    var $error = '';
    var $result = false;
    var $filename = '';
    public $sheetData = null;

    /*
    *Constructor to Make Database Connection and to Import xls file.
    *@param Filename with Path
    */
    function __construct($filename)
    {   
        $Conn = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass) 
                     or $this->error_msg("Error: Invalid MySQL Server Information");
        if (!$Conn)
            $this->error_msg("Error: Invalid MySQL Server Information");
        $DB_select = mysql_select_db($this->dbname, $Conn);
        if (!$DB_select)
            $this->error_msg("Error: Invalid MySQL Database"); 
        $this->filename = $filename;
        $this->import();
    }

    //sql execution function which will return resource id
    function execute($sql)
    {
        if ($sql!="")
        {
            $result = mysql_query($sql) or $this->error_msg("Error: Check MySQL Query($sql)");
            if ($result)
                return $result;
            else
                return false;
        }
    }
     
    //function to check number of record of resource id
    function count_check($result)
    {
        if ($result)
        {
            if (mysql_num_rows($result) > 0)
                return true;
            else
                return false;                   
        }
    }       
     
    //function to display error message
    function error_msg($msg)
    {
        if ($msg != '')
            echo ($msg);
        else
            return true;
    }

    //Import CSV File
    function import()
    {
        if (file_exists($this->filename))
        {   
            $objPHPExcel = PHPExcel_IOFactory::load($this->filename);
            $this->sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        }
    } 
}
?>