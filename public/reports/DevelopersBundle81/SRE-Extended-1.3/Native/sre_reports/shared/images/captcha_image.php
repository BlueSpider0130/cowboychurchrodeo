<?php 
/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
define ( "DIRECTACESS", "true" );
error_reporting(E_ERROR  | E_PARSE);
ob_start();
require_once("../config/general_config.php");

require_once("../libraries/session.php");
require_once("../libraries/Model/safeValue.php");
$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();
if($obj_captcha ){
	header ( 'Content-Type: image/png' );
	$obj_captcha->generate_security_code();
	$obj_captcha->render_captcha();
}
ob_end_flush();
?>