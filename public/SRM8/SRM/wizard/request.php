<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
error_reporting(0);
defined ( 'DIRECTACESS' ) or die ( "Error 301: Access denied!" );
// file needed for loading the wizard
if (basename ( getcwd () ) == "wizard") {
	$admin_file = "../Reports8/shared/config/admin.php";
	$install_directory = "../../install";
	$login_page_url = "../../HomePage/login.php";
        require_once($admin_file);
	require_once ("../../HomePage/models/Profile.class.php");
	require_once ("../Reports8/shared/helpers/Model/codegniter/ci_input.php");
	require_once ("../Reports8/shared/helpers/Model/safeValue.php");
	require_once ("../Reports8/shared/config/general_config.php");
	require_once ("../Reports8/shared/helpers/session.php");
	require_once ("../../HomePage/models/Authorize.class.php");
} elseif (basename ( getcwd () ) == "services" || basename ( getcwd () ) == "engine") {
	$admin_file = "../../Reports8/shared/config/admin.php";
	$install_directory = "../../../install";
	$login_page_url = "../../../HomePage/login.php";
	require_once ("../../../HomePage/models/Profile.class.php");
	require_once ("../../Reports8/shared/helpers/Model/codegniter/ci_input.php");
	require_once ("../../Reports8/shared/helpers/Model/safeValue.php");
	require_once ("../../Reports8/shared/config/general_config.php");
	require_once ("../../Reports8/shared/helpers/session.php");
	require_once ("../../../HomePage/models/Authorize.class.php");
} else {
	die ( "Error 307: Access to this directory is denied!" );
}

// case no admin file

if (! file_exists ( $admin_file ) || filesize ( $admin_file ) < 1) {
	die ( "Error 302 : Access is currently denied!!" );
}

$profile = new Profile ( $admin_file );
if ($profile->get_username () == "" || $profile->get_current_password () == "" || $profile->get_email () == "") {
	die ( "Error 303 : Access is currently denied!!" );
}

// if the install folder is not deleted

if (file_exists ( $install_directory )) {
	die ( "Error 304 : Access is currently denied!!" );
}

// Home page URL
$host = $_SERVER ['HTTP_HOST'];
$uri = rtrim ( dirname ( $_SERVER ['PHP_SELF'] ), '/\\' );
$http = isset ( $_SERVER ['HTTPS'] ) ? 'https://' : 'http://';
$wizard_exact_directory = $http . $host . $uri . "/";

// starting session

if (isset ( $proxy_detect ) && strtolower ( $proxy_detect ) == "yes") {
	$remoteaddr = $_SERVER ["REMOTE_ADDR"];
	$xforward = isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] ) ? $_SERVER ["HTTP_X_FORWARDED_FOR"] : "";
	if (! empty ( $xforward )) {
		$real_ip_address = $_SERVER ["HTTP_X_FORWARDED_FOR"];
		die ( "Error 305 : a proxy is detected, please contact the admin if you are not using one!" );
	}
	
	$proxy_headers = array (
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
	foreach ( $proxy_headers as $x ) {
		if (isset ( $_SERVER [$x] ))
			die ( "Error 306 : a proxy is detected, please contact the admin if you are not using one!" );
	}
}

// nw request token for CSRF protection
$request_token_value = md5 ( uniqid ( rand (), true ) );
$admin_login_key = "admin_access_SRM7";
// make sure the user is a valid logged in admin for any pages other than the login and forgot password

$auth = new Authorize ( $profile );
// no authorization to continue
if (! isset ( $_SESSION [$admin_login_key] ) || ! is_array ( $_SESSION [$admin_login_key] ) || $auth->validate ( $_SESSION [$admin_login_key] ) !== 1) {
	header ( 'HTTP/1.0 403 Forbidden' );
	header ( "location: $login_page_url" );
	exit ();
}

// no connection to proceed

if (isset ( $_GET ["id"] ) && $_GET ["id"] > 0) {
	$connection_key = isset ( $_SESSION ["srm_f62014_validate_key"] ) ? $_SESSION ["srm_f62014_validate_key"] : '';
	
	if ($connection_key !== md5 ( "srm_f62014_valid_1010" ) || ! isset ( $_SESSION ['srm_f62014_host'] ) || empty ( $_SESSION ['srm_f62014_host'] ) || ! isset ( $_SESSION ['srm_f62014_user'] ) || empty ( $_SESSION ['srm_f62014_user'] ) || ! isset ( $_SESSION ['srm_f62014_db'] ) || empty ( $_SESSION ['srm_f62014_db'] ) || ! isset ( $_SESSION ['srm_f62014_pass'] )) {
		header ( "location: $wizard_exact_directory?id=0" );
		exit ();
	}
}
if(!isset($_SESSION ["request_token_wizard"]))
$_SESSION ["request_token_wizard"] = "";

// important HTML headers
header ( "X-XSS-Protection: 1" );
header ( "X-Frame-Options: SAMEORIGIN" );
header ( "X-Content-Type-Options: nosniff" );
		

