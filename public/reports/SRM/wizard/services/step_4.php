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
$_GET = array ();
$_POST = clean_input_array ( $_POST );
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();
require_once("functions.php");
if (!is_connected() ) {
    echo ( " Must be connected to run this script" );
    exit();
}

require_once "sessionCleaner.php";

// set selected field/columns in session
if (isset ( $_POST ["selFields"] )) {

	unsetSessionStartFromColumns ();
	$selectedFields = explode ( ",", $_POST ["selFields"] );

	if (is_array ( $selectedFields )) {
		if (count ( $selectedFields ) > 0 && $selectedFields [0] !== "" && ! empty ( $selectedFields [0] ) && $selectedFields [0] !== null && $selectedFields [0] !== "null") {
			$_SESSION ["srm_f62014_fields"] = $selectedFields;
			$_SESSION ["srm_f62014_fields2"] = $selectedFields;
			echo "success";
		} else
			echo "error";
	} else
		echo "error";
	exit ();
}

$functions = array (
		"sum",
		"avg",
		"min",
		"max",
		"count" 
);
// set statistical option in session
if (isset ( $_POST ["func"] ) && isset ( $_POST ["affectedColumn"] ) && isset ( $_POST ["groupbyColumn"] )) {
	if (isset ( $_SESSION ["srm_f62014_statestical"] ) && $_SESSION ["srm_f62014_statestical"] === 1) {
		unset ( $_SESSION ["srm_f62014_statestical"], $_SESSION ["srm_f62014_function"], $_SESSION ["srm_f62014_affected_column"], $_SESSION ["srm_f62014_groupby_column"] );
		echo 'unset_success';
		exit ();
	}
	
	$function = (in_array ( $_POST ["func"], $functions )) ? $_POST ["func"] : '';
	$affectedColumn = $_POST ["affectedColumn"];
	$groupbyColumn = $_POST ["groupbyColumn"];
	
	if (! empty ( $function ) && ! empty ( $affectedColumn ) && ! empty ( $groupbyColumn ) && $function !== "" && $function !== null && $function !== "null" && $affectedColumn !== "" && $affectedColumn !== null && $affectedColumn !== "null" && $groupbyColumn !== "" && $groupbyColumn !== null && $groupbyColumn !== "null" && $affectedColumn !== $groupbyColumn) {
		$_SESSION ["srm_f62014_statestical"] = 1;
		$_SESSION ["srm_f62014_function"] = $function;
		$_SESSION ["srm_f62014_affected_column"] = $affectedColumn;
		$_SESSION ["srm_f62014_groupby_column"] = $groupbyColumn;
		echo "success";
	} else {
		if (empty ( $function ) || $function !== "" || $function !== null || $function !== "null")
			echo "error1";
		else if (empty ( $affectedColumn ) || $affectedColumn !== "" || $affectedColumn !== null || $affectedColumn !== "null")
			echo "error2";
		else if (empty ( $groupbyColumn ) || $groupbyColumn !== "" || $groupbyColumn !== null || $groupbyColumn !== "null")
			echo "error3";
		else if ($affectedColumn === $groupbyColumn)
			echo "error4";
		else
			echo "error";
	}
	exit ();
}
// set labels in session
if (isset ( $_POST ["labels"] ) && $_POST ["labels"] === "true") {
	
	if (isset ( $_SESSION ['srm_f62014_labels'] ))
		unset ( $_SESSION ['srm_f62014_labels'] );
	$success = 0;
	if (isset ( $_SESSION ["srm_f62014_fields"] ) && is_array ( $_SESSION ["srm_f62014_fields"] )) {
		foreach ( $_SESSION ["srm_f62014_fields"] as $key => $field ) {
			// $POST_field = trim($_POST['lbl_' . str_replace(array('.', ' '), array('0x', 'x0Space'), $field)]);
			if (isset ( $_POST ['lbl_' . $field] )) {
				$POST_field = trim ( $_POST ['lbl_' . $field] );
			} else if (isset ( $_POST ['lbl_' . str_replace ( array (
					'.',
					' ' 
			), array (
					'_',
					'_' 
			), $field )] )) {
				$POST_field = trim ( $_POST ['lbl_' . str_replace ( array (
						'.',
						' ' 
				), array (
						'_',
						'_' 
				), $field )] );
			} else {
				$POST_field = $field;
			}
			
			if ($POST_field !== '' && ! empty ( $POST_field ) && $POST_field !== null) {
				$_SESSION ['srm_f62014_labels'] [$field] = $POST_field;
				$success = 1;
			} else {
				echo $field;
				$success = 0;
				break;
			}
		}
	} else
		echo "error";
	if ($success === 1)
		echo "success";
	exit ();
}
	