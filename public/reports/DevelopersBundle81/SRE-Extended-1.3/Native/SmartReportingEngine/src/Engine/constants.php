<?php
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */




define("SRE_PUBLIC_REPORT", "PUBLIC_REPORT");
#define SRE_PRIVATE_REPORT  
/// Private Access Mode 
/**
  *  If you  want to assign this access mode to your report then you should :
 *  1) Use at least one of the ReportOptions::security_check_session_* functions .
 *  2) Set a default or custom login and logout page. 
  */

define("SRE_PRIVATE_REPORT" , "PRIVATE_REPORT");
  
/// a numeric data type 
/**
  * a numeric data type 
  */
define("SRE_NUMBER","NUMBER");
/**
  * A textual data type
  */
define("SRE_TEXT","TEXT");/**
  * A date data type
  */
define("SRE_DATE","DATE");
/**
  * an array data type
  */
define("SRE_ARRAY","ARRAY");
/**
  * An object data type
  */
define("SRE_OBJECT","OBJECT");
/**
  * A Boolean data type
  */
define("SRE_BOOLEAN","BOOLEAN");
/**
  * array of all allowed data types
  */
define("SRE_DATA_TYPES",json_encode(array(
    SRE_NUMBER,
    SRE_TEXT,
     SRE_DATE,
    SRE_ARRAY,
    SRE_OBJECT,
    SRE_BOOLEAN   
    
)));
/**
  * A table based data source
  */
define("SRE_Table","table");
/**
  *  A SQL query data source
  */
define("SRE_SQL","sql");
define('BASEPATH', true);
define("DIRECTACESS", true);
define("SRE__Product_DIR__","SmartReportingEngine");
define("SRE__src_DIR__","src");
define("SRE__Engin__DIR__", "Engine");  //The directory in which the model classes is to be saved
define("SRE__REPORTS__DIR__", "sre_reports"); //The directory in which reports to be stored
define("SRE__CORE__DIR__", "Core"); //the directory in which the core engine files is located
define("SRE_MOBILE_REDIRECT",false);
