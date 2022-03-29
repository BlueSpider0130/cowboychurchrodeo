<?php
use Sre\SmartReportingEngine\src\Engine\Constants;
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */
if ($datasource == 'sql') {
    require_once ("../shared/libraries/Model/Report.php");
    require_once ("../shared/libraries/Model/QueryReport.php");
} else {
    require_once ("../shared/libraries/Model/search.php");
    require_once ("../shared/libraries/Model/Report.php");
    require_once ("../shared/libraries/Model/TableReport.php");
}

// 
if (isset($_GET ["export"]) && ($_GET ["export"] == "pdf" || $_GET ["export"] == "pdf1")) {
    //export all

    require_once("../shared/pdf-old/class.ezpdf.php");
}
require_once ("../shared/libraries/functions.php");
require_once ("../shared/libraries/celltypes.php");
require_once ("../shared/libraries/lib.php");
 require_once ("../shared/libraries/export.php");


/*
 * #################################################################################################
 * Creating the Report Sql
 * ################################################################################################
 */
if ($datasource == 'sql') {

    $sql = Prepare_QSql();
} else {

    $sql = Prepare_TSql();
}
if ($empty_search_parameters || $possible_attack) {
    // case user send empty search keywords or entered the $Enter_your_search_lang in the search box
    $all_records = array();
    $nRecords = 0;
    $empty_Report = true;
    $numberOfPages = 1;
    $records_per_page = 10;
} else {

    $all_records = query($sql [0], "LayOut : Prepare SQL", $sql [1], $sql [2]);
    if (is_array($all_records)) {
        $nRecords = count($all_records);
    } else {
        $nRecords = 0;
    }
    if ($records_per_page == 0) {
        $records_per_page = 10;
    }

    $numberOfPages = ceil($nRecords / $records_per_page);
    if ($numberOfPages == 0 || $nRecords == 0) {
        $empty_Report = true;
        $numberOfPages = 1;
    } else {
        $empty_Report = false;
    }
}
$levels = count($group_by);
?>