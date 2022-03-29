<?php 
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
error_reporting(0);
if (! defined ( "DIRECTACESS" ))
	exit ( "Error 100 : No direct script access allowed" );
	// case system is not installed or no valid profile in file
$admin_file = "../SRM/Reports8/shared/config/admin.php";
$create_new_report_path = "../SRM/wizard/";
if (! file_exists ( $admin_file ) || filesize ( $admin_file ) < 1) {
	die ( "Error 101 : Access is currently denied!!" );
}

require_once ("models/Profile.class.php");
$profile = new Profile ( $admin_file );
if ($profile->get_username () == "" || $profile->get_current_password () == "" || $profile->get_email () == "") {
	die ( "Error 102: Access is currently denied!!" );
}

//if the install folder is not deleted 
$install_directory = "../install";
if(file_exists($install_directory)){
	die ( "Error 103 : Access is currently denied!!" );
}

require_once ("../SRM/Reports8/shared/helpers/Model/codegniter/ci_input.php");
// Home page URL
$host = $_SERVER ['HTTP_HOST'];
$uri = rtrim ( dirname ( $_SERVER ['PHP_SELF'] ), '/\\' );
$http = isset ( $_SERVER ['HTTPS'] ) ? 'https://' : 'http://';
$extra = "index.php";
$homepage_exact_url = $http . $host . $uri . "/" . $extra;
$homepage_exact_directory = $http . $host . $uri . "/";

// starting session
require_once ("../SRM/Reports8/shared/config/general_config.php");
if (isset ( $proxy_detect ) && strtolower ( $proxy_detect ) == "yes") {
	$remoteaddr = $_SERVER ["REMOTE_ADDR"];
	$xforward = isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] ) ? $_SERVER ["HTTP_X_FORWARDED_FOR"] : "";
	if (! empty ( $xforward )) {
		$real_ip_address = $_SERVER ["HTTP_X_FORWARDED_FOR"];
		die ( "Error 104 : a proxy is detected, please contact the admin if you are not using one!" );
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
			die ( "Error 105 : a proxy is detected, please contact the admin if you are not using one!" );
	}
}

require_once ("../SRM/Reports8/shared/helpers/session.php");
// nw request token for CSRF protection
$request_token_value = md5 ( uniqid ( rand (), true ) );
$admin_login_key = "admin_access_SRM7";
// make sure the user is a valid logged in admin for any pages other than the login and forgot password
if (basename ( $_SERVER ['PHP_SELF'] ) != "login.php" && basename ( $_SERVER ['PHP_SELF'] ) != "Forgot_password.php") {
	require_once ("models/Authorize.class.php");
	$auth = new Authorize ( $profile );
	if (! isset ( $_SESSION [$admin_login_key] ) || ! is_array ( $_SESSION [$admin_login_key] ) || $auth->validate ( $_SESSION [$admin_login_key] ) !== 1) {
		header ( 'HTTP/1.0 403 Forbidden' );
		header ( "location: login.php" );
		exit ();
	}
}

if (! isset ( $_SESSION ["request_token"] ))
	$_SESSION ["request_token"] = "";

require_once ("../SRM/Reports8/shared/helpers/Model/safeValue.php");
require_once ("../SRM/Reports8/shared/helpers/Model/Member.php");
require_once ("../SRM/Reports8/shared/helpers/Model/admin.php");

// important HTML headers
header ( "X-XSS-Protection: 1" );
header ( "X-Frame-Options: SAMEORIGIN" );
header ( "X-Content-Type-Options: nosniff" );
		
