<?php
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */
namespace Sre\SmartReportingEngine\src\Engine;

class Constants{

  /// Public Access Mode
  /**
    * No session checks will be performed for reports with this access mode, and so if your report is available on line any one can access it.
    */
  const SRE_PUBLIC_REPORT= "PUBLIC_REPORT";
  #define SRE_PRIVATE_REPORT
  /// Private Access Mode
  /**
    *  If you  want to assign this access mode to your report then you should :
  *  1) Use at least one of the ReportOptions::security_check_session_* functions .
  *  2) Set a default or custom login and logout page.
    */

  const SRE_PRIVATE_REPORT= "PRIVATE_REPORT";
    
  /// a numeric data type
  /**
    * a numeric data type
    */
  const SRE_NUMBER= "NUMBER";
  /**
    * A textual data type
    */
  const SRE_TEXT= "TEXT";
  /**
    * A date  type
    */
  const SRE_DATE= "DATE";
  
  
  /**
    * an array data type
    */
  const SRE_ARRAY= "ARRAY";
  /**
    * An object data type
    */
  const SRE_OBJECT= "OBJECT";
  /**
    * A Boolean data type
    */
  const SRE_BOOLEAN= "BOOLEAN";
  /**
    * array of all allowed data types
    */
  const SRE_DATA_TYPES= [
      self::SRE_NUMBER,
      self::SRE_TEXT,
      self::SRE_DATE,
      self::SRE_ARRAY,
      self::SRE_OBJECT,
      self::SRE_BOOLEAN
  ];
  /**
    * A table based data source
    */
  const SRE_Table= "table";
  /**
    *  A SQL query data source
    */
  const SRE_SQL= "sql";
  const BASEPATH= true;
  const DIRECTACESS= true;
  const SRE__Product_DIR__= "SmartReportingEngine";
  const SRE__SRE_DIR__= "src";
  const SRE__Engin__DIR__= "Engine";  //The directory in which the model classes is to be saved
  const SRE__REPORTS__DIR__= "sre_reports"; //The directory in which reports to be stored
  const SRE__CORE__DIR__= "Core"; //the directory in which the core engine files is located
  const SRE_MOBILE_REDIRECT= false;
}