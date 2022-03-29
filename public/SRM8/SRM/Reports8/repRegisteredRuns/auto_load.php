<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");
if ($datasource == 'sql') {
    require_once ("../shared/helpers/Model/Report.php");
    require_once ("../shared/helpers/Model/QueryReport.php");
} else {
    require_once ("../shared/helpers/Model/search.php");
    require_once ("../shared/helpers/Model/Report.php");
    require_once ("../shared/helpers/Model/TableReport.php");
}

// require_once ('../shared/pdf/class.ezpdf.php');
//export all
if ($pdf_export === 1) {
    require_once("../shared/pdf1/tcpdf.php");
    require_once("../shared/helpers/pdf_export_provider1.php");
} elseif ($pdf_export === 2) {

    require_once("../shared/pdf2/Cezpdf.php");
    require_once("../shared/helpers/pdf_export_provider2.php");
} elseif ($pdf_export === 3) {
    require_once ('../shared/pdf3/autoload.inc.php');
    require_once("../shared/helpers/pdf_export_provider2.php");
}

require_once ("../shared/helpers/functions.php");
require_once ("../shared/helpers/celltypes.php");

require_once ("../shared/helpers/lib.php");

require_once ("../shared/helpers/export.php");


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

    $nRecords = (is_array($all_records)) ? count($all_records) : 0;
    if ($records_per_page == 0) {
        $records_per_page = 10;
    }

    $numberOfPages = ceil($nRecords / $records_per_page);
    if ($numberOfPages == 0 || $nRecords == 0) {
        $empty_Report = true;
        $numberOfPages = 1;
    } else {
        $empty_Report = false;
        if (isset($sub_totals) && !empty($sub_totals) && in_array($sub_totals["group_by"], $group_by)) {
            require_once ("../shared/helpers/Model/SubTotal.php");
            $sub_totals_obj = new SubTotal($sub_totals, $all_records);
        }
    }
}
$levels = count($group_by);
?>