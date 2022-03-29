<?php
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */
ob_start();
define ( "DIRECTACESS", "true" );
require_once ("request.php");
/*
 * #################################################################################################
 * hANDLING SUPER GLOBALS
 * ################################################################################################
 */
$_CLEANED = remove_unexpected_superglobals($_GET, array("setlLayout"));
$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();
$report_url = basename ( __DIR__ );
$report_url .= ".php";
if(!file_exists($report_url)){
	$report_url = $file_name . ".php"	;
}
if(!isset($_CLEANED["RequestToken"]) || $_CLEANED["RequestToken"] != $_SESSION[$request_token]){
    ob_end_clean();
	header ( "location: " . $report_url );
	exit ();
}

/*
 * #################################################################################################
 * Changing the layout
 * ################################################################################################
 */
$all_layouts = array (
		"AlignLeft",
		"Block",
		"Stepped",
		"Outline",
		"Horizontal"
);
$posted_layout = isset ( $_CLEANED ["setlLayout"] ) ? $_CLEANED ["setlLayout"]  : "";
$posted_layout_key = array_search ( $posted_layout, $all_layouts );
$keys = array (
		0,
		1,
		2,
		3,
		4
);
if (in_array ( $posted_layout_key, $keys )) {
	$_SESSION ["change_layout_srm7"] = $all_layouts [$posted_layout_key];
}
ob_end_clean();
header ( "location: " . $report_url );
exit ();