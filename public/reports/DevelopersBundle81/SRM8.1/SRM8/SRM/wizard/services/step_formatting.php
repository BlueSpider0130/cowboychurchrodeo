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
ob_start();
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
$data = json_decode ( file_get_contents ( 'php://input' ), true );
if (isset ( $data ["cells"] )) {
	list ( $result, $message ) = validate_cells_array ( $data ["cells"], $_SESSION ["srm_f62014_fields"] );
	if ($result === 1) {
		$cells = $data ["cells"];
		$_SESSION ["srm_f62014_cells"] = $cells;
	}
ob_end_clean();
echo $message;
exit();
}

if (isset ( $data ["formatting"] )) {
	list ( $result, $message ) = validate_formatting_array ( $data ["formatting"], $_SESSION ["srm_f62014_fields"] );
	if ($result === 1) {
		$formatting = $data ["formatting"];
		$_SESSION ["srm_f62014_conditional_formating"] = $formatting;
	}
ob_end_clean();
echo $message;
exit();
}


function validate_cells_array($cells, $cols) {
	// validate cells array
	$allTypes = array (
			"value",
			"image",
			"stars",
			"link",
			"bit",
			"country",
			"append-r",
			"append-l" 
	);
	$indexes = array (
			"column",
			"cellType",
			"appendedText" 
	);
	
	foreach ( $cells as $cell ) {
		
		foreach ( $cell as $k => $v ) {
			if (in_array ( $k, $indexes )) {
				if ($k == "column" && ! in_array ( $v, $cols )) {
					return array (
							0,
							$v . " is an invalid column names" 
					);
				} elseif ($k == "cellType" && ! in_array ( $v, $allTypes )) {
					return array (
							0,
							$v . " is an invalid cell type" 
					);
				} elseif ($k == "appendedText" && strstr ( $cell ["cellType"], "append" ) && empty ( $v )) {
					return array (
							0,
							"The cell of " . $cell ["column"] . "has the type of append or prepend a text yet the appended text is empty " 
					);
				}
			} else {
				return array (
						0,
						"invalid array indexes" 
				);
			}
		}
	}
	
	return array (
			1,
			"success" 
	);
}
function validate_formatting_array($formatting, $cols) {
	$filters = array (
			"equal",
			"notequal",
			"more",
			"less",
			"moreorequal",
			"lessorequal",
			"between",
			"contain",
			"notcontain",
			"beginwith",
			"endwith" 
	);
	
	$indexes = array (
			"filter",
			"column",
			"filterValue1",
			"filterValue2",
			"color" 
	);
	
	foreach ( $formatting as $rule ) {
		foreach ( $rule as $k => $v ) {
			if (in_array ( $k, $indexes )) {
				if ($k == "column" && ! in_array ( $v, $cols )) {
					return array (
							0,
							$v . " is an invalid column names" 
					);
				} elseif ($k == "filter" && ! in_array ( $v, $filters )) {
					return array (
							0,
							$v . " is an invalid filter" 
					);
				}
			} else {
				return array (
						0,
						"invalid array indexes" 
				);
			}
		}
	}
	
	return array (
			1,
			"success" 
	);
}	