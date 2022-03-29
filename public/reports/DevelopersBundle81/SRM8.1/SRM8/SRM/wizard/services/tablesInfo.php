<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
/*
 * #################################################################################################
 * This sends json data to wizard/step_3.php with table and columns info
 * ################################################################################################
 */
define("DIRECTACESS", 1);
require_once ("../request.php");

$_GET = array();
$_POST = clean_input_array($_POST);
$_ENV = array();
$_FILES = array();
$_COOKIE = array();
require_once("functions.php");
if (!is_connected() ) {
    echo ( " Must be connected to run this script" );
    exit();
} elseif (!isset($_POST ["tablesInfo"]) || !isset($_SESSION ["srm_f62014_table"])) {
    echo ("invalid request");
} else {

    require_once "../lib.php";

    if (isset($_POST ["tablesInfo"]) && isset($_SESSION ["srm_f62014_table"])) {
        foreach ($_SESSION ["srm_f62014_table"] as $key => $val) {
            $val = clean_input($val);
            $tablesInfo [$val] = array();
            $result = $dbHandler->query("DESCRIBE `$val`", "ASSOC");
            foreach ($result as $k => $value) {
                $type = (strpos($value ["Type"], "(")) ? substr($value ["Type"], 0, strpos($value ["Type"], "(")) : $value ["Type"];
                $type = strtolower($type);
                $tablesInfo [$val] [$value ["Field"]] = $type;
            }
        }
        $json = json_encode($tablesInfo);
        echo $json;
        exit();
    }
}