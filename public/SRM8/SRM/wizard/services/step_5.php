<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
// handle super globals
define ( "DIRECTACESS", 1 );
require_once ("../request.php");
require_once("functions.php");
if (!is_connected() ) {
    echo ( " Must be connected to run this script" );
    exit();
}
$_GET = array ();
$_POST = clean_input_array ( $_POST );
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();


// set group by and sort by in session
if (isset ( $_POST ["groupbyFields"] ) && isset ( $_POST ['fields1'] ) && isset ( $_POST ['fields2'] ) && isset ( $_POST ['fields3'] ) && isset ( $_POST ['fields4'] ) && isset ( $_POST ['fields5'] )) {
	
	if (isset ( $_SESSION ["srm_f62014_group_by"] ) && isset ( $_SESSION ['srm_f62014_sort_by'] ))
		unset ( $_SESSION ["srm_f62014_group_by"], $_SESSION ['srm_f62014_sort_by'] );
	$groupbyFields = $_POST ["groupbyFields"];
	if (! empty ( $groupbyFields ) && $groupbyFields !== "" && $groupbyFields !== null && $groupbyFields !== "null") {
		$groupbyFields = explode ( ",", $groupbyFields );
		if (count ( $groupbyFields ) > 0) {
			foreach ( $groupbyFields as $key => $field ) {
				if (! strstr ( $field, "(" ))
					$_SESSION ["srm_f62014_group_by"] [] = $field;
			}
		}
	} else {
		$_SESSION ["srm_f62014_group_by"] = array ();
	}
	
	$form_fields = array ();
	$form_fields [0] = $_POST ['fields1'];
	$form_fields [1] = $_POST ['fields2'];
	$form_fields [2] = $_POST ['fields3'];
	$form_fields [3] = $_POST ['fields4'];
	$form_fields [4] = $_POST ['fields5'];
	$desc = array ();
	for($i = 0; $i < 5; $i ++) {
		$field_name = 'desc' . ($i + 1);
		if (empty ( $_POST [$field_name] ))
			$desc [] = 0;
		else
			$desc [] = 1;
	}
	
	$sort_by = array ();
	$i = 0;
	foreach ( $form_fields as $key => $value ) {
		if ($value !== 'None' && ! strstr ( $value, "(" )) {
			$sort_by [$i] [0] = $value;
			$sort_by [$i] [1] = $desc [$key];
			$i ++;
		}
	}
	$_SESSION ['srm_f62014_sort_by'] = $sort_by;
	
	echo "success";
	exit ();
}