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

class ErrorMessages {

    public static $messages = array(
        "en" => array(
            "1" => "Could not create the report directory. Permission denied! Please make sure to give 755 permissions to the 'sre_reports' directory",
            "2" => "Could not write in the 'init.php' configuration file of the generated report. Permission denied! Please make sure to give 755 permissions to the 'sre_reports' directory ",
            "3" => "Could not create the 'init.php' configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the 'init.php' directory ",
            "4" => 'Could not  write in the configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the sre_reports directory ',
            "5" => 'Could not  create the configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the sre_reports directory ',
            "15" => "No columns were selected for the report",
            "16" => "No tables were selected for the report",
            "17" => "The datasource is set to SQL yet a SQL query is not found!",
            "18" => "The datasource is not recognized!",
            "19" => " Connection parameters are missing!",
            "20" => "The report name can't be empty!",
            "21" => "The reports directory can't be empty!",
            "22" => "The 'Constants::SRE__Engin__DIR__' constant can't be empty in '/sre_config/config.php' file.",
            "23" => "The 'Constants::SRE__CORE__DIR__ ' constant can't be empty in '/sre_config/config.php' file.",
            "25" => "Invalid sql query! Please write a valid select sql query. Don't use 'order by' , 'group by' or double qoutes  in your query. Alternativly, please use the 'set_order_by' , 'set_group_by' methods and single qoutes",
            "26" => "The set_grouping function expects a single dimensional  array of the column(s)!",
            "27" => "Error in 'sort by' array",
            "28" => "Error in labels!",
            "29" => "Report with same file name already exist! If you like to auto replace reports with same name please edit this setting in '/sre_config/config.php' file. ",
            "31" => "Error in filters array  keys ",
            "32" => "Error in filters parameter types ",
            "33" => "Error in filters parameters ",
            "34" => "Error in filters array ",
            "35" => "Error in filters validation ",
            "36" => "Data Filters are available only with the 'table' data source!",
            "37" => "The report to be created is based on multiple tables, so the column name parameter which is passed to  the 'label' function should be in the form of 'TablesName.ColumnName' ",
            "38" => "The report to be created  is based on multiple tables, so the column name parameter passed to  any 'format_column' function should be in the form of 'TablesName.ColumnName'",
            "39" => "The report to be created is based on multiple tables, so each column name parameter  passed to  the 'select_fields' function should be in the form of 'TablesName.ColumnName' ",
            "40" => "The report to be created is based on multiple tables, so the column name parameter which is passed to  the 'set_grouping' function should be in the form of 'TablesName.ColumnName' ",
            "41" => "The report to be created is based on multiple tables, so the column name parameter which is passed to  the 'sort_by' function should be in the form of 'TablesName.ColumnName' ",
            "42" => "The report to be created is based on multiple tables, yet there are no relationships defined! ",
            "43" => "Data type is not supported",
            "44" => "The 'security_init()' should be called before any security_check functions.",
            "50" => "The access mode of the report is private, yet no security session validation rules were added!",
            "51" => "The access mode of report is public, yet some session validation rules are required!",
            "52" => "The access mode of report is private yet the login page is not set!",
            "53" => "The access mode of report is private yet the logout page is not set!",
            "54" => " Incorrect session key format passed to the security method : ",
            "55" => "The access mode of report is public, yet  the 'security_init()' is called!",
        )
    );

}
