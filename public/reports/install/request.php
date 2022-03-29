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
	exit ( "Error 400 : No direct script access allowed" );
	// case system is not installed or no valid profile in file
$admin_file = "../SRM/Reports8/shared/config/admin.php";

require_once ("../HomePage/models/Profile.class.php");
$profile = new Profile ( $admin_file );



require_once ("../SRM/Reports8/shared/helpers/Model/codegniter/ci_input.php");
// Home page URL
$host = $_SERVER ['HTTP_HOST'];
$uri = rtrim ( dirname ( $_SERVER ['PHP_SELF'] ), '/\\' );
$http = isset ( $_SERVER ['HTTPS'] ) ? 'https://' : 'http://';
$extra = "index.php";
$install_exact_url = $http . $host . $uri . "/" . $extra;
$homepage_exact_url = str_replace("install","HomePage",$install_exact_url);

// starting session
require_once ("../SRM/Reports8/shared/config/general_config.php");
if (isset ( $proxy_detect ) && strtolower ( $proxy_detect ) == "yes") {
	$remoteaddr = $_SERVER ["REMOTE_ADDR"];
	$xforward = isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] ) ? $_SERVER ["HTTP_X_FORWARDED_FOR"] : "";
	if (! empty ( $xforward )) {
		$real_ip_address = $_SERVER ["HTTP_X_FORWARDED_FOR"];
		die ( "Error 403 : a proxy is detected!" );
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
			die ( "Error 404 : a proxy is detected!" );
	}
}

require_once ("../SRM/Reports8/shared/helpers/session.php");
// nw request token for CSRF protection
$request_token_value = md5 ( uniqid ( rand (), true ) );
$install_session_key = "install_srm7";


if (! isset ( $_SESSION ["request_token"] ))
	$_SESSION ["request_token"] = "";

require_once ("../SRM/Reports8/shared/helpers/Model/safeValue.php");
require_once ("../SRM/Reports8/shared/helpers/Model/Member.php");


// important HTML headers
header ( "X-XSS-Protection: 1" );
header ( "X-Frame-Options: SAMEORIGIN" );
header ( "X-Content-Type-Options: nosniff" );
		
