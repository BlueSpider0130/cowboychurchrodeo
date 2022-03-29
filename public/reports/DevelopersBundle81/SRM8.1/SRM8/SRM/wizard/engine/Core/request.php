<?php

//error_reporting(0);
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
// case system is not installed
if (!file_exists("../shared/config/admin.php") || filesize("../shared/config/admin.php") < 1) {
    die("Error 200: Access is currently denied!");
}



require_once ("../shared/helpers/Model/codegniter/ci_input.php");
require_once ("config.php");
if (strtolower(substr($file_name, 0, 3)) != "rep") {
    $file_name = "rep" . $file_name;
}

if ($layout == "Mobile")
    $layout = "mobile";

//report URL
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$http = isset($_SERVER ['HTTPS']) ? 'https://' : 'http://';
$extra = $file_name . ".php";
$report_exact_url = $http . $host . $uri . "/" . $extra;
$report_exact_directory = $http . $host . $uri . "/";
// starting session
require_once("../shared/config/general_config.php");
if (isset($proxy_detect) && strtolower($proxy_detect) == "yes") {
    $remoteaddr = $_SERVER["REMOTE_ADDR"];
    $xforward = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : "";
    if (!empty($xforward)) {
        $real_ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
        die("Error 201: a proxy is detected, please contact the admin if you are not using one!");
    }


    $proxy_headers = array(
        'HTTP_VIA',
        'HTTP_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'HTTP_FORWARDED_FOR_IP',
        'VIA',
        'X_FORWARDED_FOR',
        'FORWARDED_FOR',
        'X_FORWARDED',
        'FORWARDED',
        'CLIENT_IP',
        'FORWARDED_FOR_IP',
        'HTTP_PROXY_CONNECTION'
    );
    foreach ($proxy_headers as $x) {
        if (isset($_SERVER[$x]))
            die("Error 202: a proxy is detected, please contact the admin if you are not using one!");
    }
}
require_once("../shared/config/admin.php");
require_once ("../shared/helpers/session.php");

// nw request token for CSRF protection
$request_token_value = md5(uniqid(rand(), true));
// Main files to load report settings, ACL, connection to db and security
$path = "../shared/languages/" . $language . ".php";
if (file_exists($path))
    require_once ("../shared/languages/" . $language . ".php");
else
    require_once ("../shared/languages/en.php");
// the report key is a unique key is used in the session values that are related to this particular report
// not the sessions that might affect many reports at once
$report_key = sha1(str_replace(" ", "_", $file_name));
// report session key not remove it from here
$report_filtering_key = $report_key . "_Filtering";
$user_login_key = $report_key . "_User_Login";
$admin_login_key = "admin_access_SRM7";
$request_token = $report_key . "_Request_Token";
if (!isset($_SESSION [$request_token]))
    $_SESSION [$request_token] = "";

require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect ();




require_once ("../shared/helpers/Model/safeValue.php");
require_once ("init.php");
require_once ("../shared/helpers/Model/DatabaseHandler.php");
require_once ("../shared/helpers/Model/Member.php");
require_once ("../shared/helpers/Model/User.php");
require_once ("../shared/helpers/Model/DbMember.php");
require_once ("../shared/helpers/Model/admin.php");
if (basename($_SERVER ['PHP_SELF']) != "login.php" && basename($_SERVER ['PHP_SELF']) != "Forgot_password.php") {
    require_once ("../shared/helpers/Authorize.php");
    require_once("../shared/helpers/Model/ParametersHandler.php");


    $param = new ParametersHandler($tables_filters,$file_name);


    if ($param->is_parameter_report()) {


        $validation_result = "";
        if ($param->is_posted_param($_POST)) {

            $validation_result = $param->validate_posted_report_param($_POST);
            if ($validation_result === 1) {
                $param->set_valid_posted_params($_POST);
                $param->save_params_in_session();
                $tables_filters = $param->add_dynamic_params_to_filters_array();
                $_POST= array();
            } else {
                //the view will display error message


                require_once("../shared/views/param.php");
                exit();
            }
        } elseif ($param->is_params_in_session()) {
            //params is remembered
           $param->set_valid_posted_params($param->get_params_from_session());
           $tables_filters = $param->add_dynamic_params_to_filters_array();

            
        }else{
            require_once("../shared/views/param.php");
            exit();
        }
    }
}



//important HTML headers
header("X-XSS-Protection: 1");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
