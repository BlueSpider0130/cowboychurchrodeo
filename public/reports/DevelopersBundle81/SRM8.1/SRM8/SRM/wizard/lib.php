<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
/*
 * #################################################################################################
 * Preparing a database object to be used in all steps
 * ################################################################################################
 */

defined('DIRECTACESS') or die("Error 301: Access denied!");
require_once 'helpers/DatabaseHandler.php';
require_once 'services/functions.php';

$host = isset($_SESSION ["srm_f62014_host"]) ? $_SESSION ["srm_f62014_host"] : '';
$user = isset($_SESSION ["srm_f62014_user"]) ? $_SESSION ["srm_f62014_user"] : '';
$pass = isset($_SESSION ["srm_f62014_pass"]) ? base64_decode($_SESSION ["srm_f62014_pass"]) : '';

$db = isset($_SESSION ["srm_f62014_db"]) ? $_SESSION ["srm_f62014_db"] : '';

// return to first page if required session not found
// get instance from DatabaseHandler class
$dbHandler = @new DatabaseHandler($host, $user, $pass, $db);
list ( $is_connection_failed, $connection_error ) = $dbHandler->is_connection_failed();

if ((basename(getcwd()) == "wizard") && (isset($_GET ["id"]) && $_GET ["id"] > 0) && (!$dbHandler || $is_connection_failed)) {
    $host = $_SERVER ['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER ['PHP_SELF']), '/\\');
    $http = isset($_SERVER ['HTTPS']) ? 'https://' : 'http://';
    $wizard_exact_directory = $http . $host . $uri . "/";
    header("location: $wizard_exact_directory?id=0");
    exit();
}
?>