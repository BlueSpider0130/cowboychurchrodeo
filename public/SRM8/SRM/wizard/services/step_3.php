<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
// handling super globals
define("DIRECTACESS", 1);
require_once ("../request.php");
$_GET = array();
$_POST = clean_input_array($_POST);
$_ENV = array();
$_FILES = array();
$_COOKIE = array();
require_once("functions.php");
if (!is_connected()) {
    echo ( " Must be connected to run this script" );
    exit();
}

require_once "sessionCleaner.php";

if(isset($_POST["groupcondition"]))
{
    if($_POST["groupcondition"] !=null)
    {
        $_SESSION["srp_filters_grouping"]= $_POST["groupcondition"];
    }elseif($_POST["groupcondition"] ==null && isset($_SESSION["srp_filters_grouping"]))
    {
        unset($_SESSION["srp_filters_grouping"]);
    }
   
}
// set selected table is session
if (isset($_POST ["tables"])) {

    unsetSessionStartFromDataSource();
    $selecteTables = explode(",", $_POST ["tables"]);

    // there is a change in the tables
    if ((isset($_SESSION ["srm_f62014_table"]) && (count(array_diff($selecteTables, $_SESSION ["srm_f62014_table"])) > 0 || count(array_diff($_SESSION ["srm_f62014_table"], $selecteTables)) > 0)) || count($selecteTables) === 1) {
        unset($_SESSION ['srm_f62014_relationships']);
    }

    if (is_array($selecteTables)) {

        if (count($selecteTables) > 0 && $selecteTables [0] !== "" && !empty($selecteTables [0]) && $selecteTables [0] !== null && $selecteTables [0] !== "null") {
            $_SESSION ["srm_f62014_table"] = $selecteTables;
            if (count($selecteTables) === 1)
                echo "success1";
            else
                echo "success2";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }

    exit();
}
// set selected relationship
if (isset($_POST ["addFilter"])) {

    $filterTable = isset($_POST ["table"]) ? $_POST ["table"] : "";
    $filterField = isset($_POST ["field"]) ? $_POST ["field"] : "";
    $fieldDataType = isset($_POST ["fieldDataType"]) ? $_POST ["fieldDataType"] : "";
    $filterType = isset($_POST ["filter"]) ? $_POST ["filter"] : "";
    $filterValue1 = isset($_POST ["filterValue1"]) ? $_POST ["filterValue1"] : "";
    $filterValue2 = isset($_POST ["filtervalue2"]) ? $_POST ["filtervalue2"] : "";
    require_once("../../Reports8/shared/helpers/Model/ReportFilterManager.php");
    $valid = 1;
    $filterManager = new FilterManager();
    //table must be in the tables array and clean
    if ($filterTable != "" && in_array($filterTable, $_SESSION ['srm_f62014_table'])) {
        $filterManager->table = $filterTable;
    } else {
        echo "error : Table $filterTable dosn't exist";
        $valid = 0;
    }
    //column must be in the cols array and clean
    if ($filterField != "") {
        $filterManager->column = $filterField;
    } else {
        echo "error : Field $filterField is empty";
        $valid = 0;
    }
    if ($fieldDataType != "" && check_is_clean($fieldDataType)) {
        $filterManager->column_datatype = $fieldDataType;
    } else {
        echo "error : The data type $fieldDataType of Field $filterField is not expected!";
        $valid = 0;
    }
    //filter must be one off the supported filters
    if ($filterType != "" && in_array($filterType, $filterManager->all_filters)) {
        $filterManager->filter_type = $filterType;
    } else {
        echo "error : the filter type is not expected !";
        $valid = 0;
    }

    //must be clean
    if ($filterValue1 != "" && check_is_clean($filterValue1) ) {
        $filterManager->filter_value_1 = $filterValue1;
    }elseif($filterType == "Is Today")
    {
        $filterManager->filter_value_1 = "today";

    } 
    elseif($filterType == "Is Null")
    {
        $filterManager->filter_value_1 = "IS NULL";

    } 
    elseif($filterType == "Is Not Null")
    {
        $filterManager->filter_value_1 = "IS NOT NULL";

    } 
     else {
        echo "error : the filter value is not accepted!";
        $valid = 0;
    }
    if ($filterValue2 != "") {
        if (stristr($filterValue1, $filterManager->parameter_text) && !stristr($filterValue2, $filterManager->parameter_text)) {
            echo "error : Both filter values should be asked by user!";
            $valid = 0;
        } elseif (stristr($filterValue2, $filterManager->parameter_text) &&   !stristr($filterValue1, $filterManager->parameter_text) ) {
            echo "error : Both filter values should be asked by user!";
            $valid = 0;
        } elseif (!check_is_clean($filterValue2)) {
            echo "error : the second filter value is not accepted for security reasons!";
            $valid = 0;
        } else {
            $filterManager->filter_value_2 = $filterValue2;
        }
    }


    if ($valid == 1) {
        $response = $filterManager->add_filter();
        echo "success : $response";
    }
    exit();
}

if (isset($_POST ["removeFilter"])) {
    $filter_name = isset($_POST["removeFilter"]) ? $_POST["removeFilter"] : "";
    require_once("../../Reports8/shared/helpers/Model/ReportFilterManager.php");


    $filterManager = new FilterManager();
    if ($filterManager->is_filter_exist($filter_name)) {
        $filterManager->remove_filter($filter_name);
        echo "success";
        exit();
    } else {
        echo "error : Filter $filter_name dosn't exist! ";
        exit();
    }
}
// saving relationship
if (isset($_POST ["relationships"])) {
    // deal with filters
    // deal with relationships
    if (isset($_SESSION ["srm_f62014_table"]) && is_array($_SESSION ["srm_f62014_table"]) && count($_SESSION ["srm_f62014_table"]) > 1) {
        $rel = explode(",", $_POST ["relationships"]);
        if (is_array($rel) && count($rel) > 0 && !empty($rel [0]) && $rel [0] !== "" && $rel [0] !== null && $rel [0] !== "null") {
            $_SESSION ["srm_f62014_relationships"] = $rel;
            echo "success";
        } else
            echo "error";
    } else {
        echo "success";
    }
    exit();
}        