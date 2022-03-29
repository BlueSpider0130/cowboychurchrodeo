<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
//handle super globals
define("DIRECTACESS",1);
require_once("../request.php");
$_GET= array();
//the password could include dangrous special characters
$_tmp_pass = isset($_POST["pass"])? $_POST["pass"] : "";
$_POST=clean_input_array($_POST);
$_POST["pass"] = $_tmp_pass;
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();


if(!isset($_POST["token"]) || empty($_POST["token"]) || $_POST["token"]!= $_SESSION["request_token_wizard"] ){
	$response = array ();
	$response ["result"] = "error";
	$response ["errorMessage"] = "Not allowed for security reasons!";
	$response = json_encode ( $response );
	echo $response;
	exit();
}
require_once "sessionCleaner.php";
$is_form_valid = 1;
$page_errors = "";


// set connection in session
//Case: Connect 
//******************************************************************************************8
if ( isset ( $_POST ["host"] ) && isset ( $_POST ["user"] ) && isset ( $_POST ["pass"] ) && isset ( $_POST ["db"] )) {
	require_once "../helpers/DatabaseHandler.php";
	
	unsetAllSession ();
	
	$host_name = $_POST ["host"];
	$user_name = $_POST ["user"];
	$password = $_POST ["pass"];
	$db = $_POST ["db"];
	
	if (empty ( $host_name ) || $host_name === '') {
		$page_errors .= "* Please enter host name.";
		$is_form_valid = 0;
	}
	if (empty ( $user_name ) || $user_name === '') {
		$page_errors .= "* Please enter user name.";
		$is_form_valid = 0;
	}
	
	if (empty ( $db ) || $db === '') {
		$page_errors .= "* Please enter the database name.";
		$is_form_valid = 0;
	}
	
	if ($is_form_valid === 1) {
		
		$dbHandler = new DatabaseHandler ( $host_name, $user_name, $password, $db );
		
		list ( $is_connection_failed, $connection_error ) = $dbHandler->is_connection_failed ();
		
		// if(!empty($connection_error)){
		$page_errors .= "# MySQL Connection Error: " . $connection_error;
		// }
		if (! $dbHandler || $is_connection_failed) {
			if (! empty ( $page_errors ))
				$page_errors .= "<br>";
				// debug mode
			$page_errors .= "# Result: Unable to connect to your MySQL server.<br/>";
			// production mode
			// $page_errors .= "# Result: Unable to connect to your MySQL server.";
			
			$response = array ();
			$response ["result"] = "error";
			$response ["errorMessage"] = $page_errors;
			$dbHandler->close_connection ();
			$response = json_encode ( $response );
			echo $response;
			exit ();
		} else {
			// save data in the sessions
			$_SESSION ['srm_f62014_host'] = clean_input ( $host_name );
			$_SESSION ['srm_f62014_user'] = clean_input ( $user_name );
			$_SESSION ['srm_f62014_pass'] = base64_encode ( $password );
			$_SESSION ['srm_f62014_db'] = $db;
			$_SESSION ['srm_f62014_validate_key'] = md5 ( "srm_f62014_valid_1010" );
			$_SESSION ['srm_f62014_db_extension'] = $dbHandler->get_used_extension();
				
			$dbHandler->close_connection ();
		}
		$response = array ();
		$response ["result"] = "success";
		defined('BASEPATH') or define('BASEPATH',1);
		require_once("../../Reports8/shared/helpers/Model/codegniter/file_helper.php");
		require_once("../../Reports8/shared/helpers/Model/safeValue.php");
		require_once("../../Reports8/shared/helpers/Model/Template.php");
		require_once("../../Reports8/shared/helpers/Model/TemplateManger.php");
		$TemplateManager = new  TemplateManager("../../Reports8/",$user_name, $db);
		//getting all templates
		$arr = $TemplateManager->get_all_templates();
		$all_templates = array();
		$new_t = 0;
		foreach($arr as $t){
			$all_templates[$new_t]["title"] = $t->title;
			$all_templates[$new_t]["name"] = $t->dir_name;
			$new_t++;
		}
		$response ["templates"] = $all_templates ;
		$_SESSION ["all_templates"] = $all_templates;
		$response = json_encode ( $response );
		echo $response;
	} else {
		$response = array ();
		$response ["result"] = "error";
		$response ["errorMessage"] = $page_errors;		
		$response = json_encode ( $response );
		echo $response;
	}
	
	exit ();
}


// set database and data source in session

//Case: Continue
//******************************************************************************************8
require_once "../lib.php";
if (isset ( $_POST ['dataSource'] ) &&   isset ( $_SESSION ["srm_f62014_validate_key"]) && $_SESSION ["srm_f62014_validate_key"] ==md5 ( "srm_f62014_valid_1010" )  ) {
	
	unsetSessionStartFromDataSource();
	
	$datasources = array (
			"table",
			"sql" 
	);
	$data_source = $_POST ['dataSource'];
	if ($data_source == '' || !in_array($data_source, $datasources) ) {
		if (! empty ( $page_errors )) $page_errors .= "<br>";
		$page_errors .= "* Please select a valid data source.";
		$is_form_valid = 0;
	}
	if ($is_form_valid === 1) {
		
		$_SESSION ['srm_f62014_datasource'] = $data_source;
		$response ["result"] = "success";
			$response ["errorMessage"] = $page_errors;
			$response = json_encode ( $response );
			echo $response;
		exit ();
	} else{
		$response ["result"] = "error";
			$response ["errorMessage"] = $page_errors;
			$response = json_encode ( $response );
			echo $response;
			
	}
	
	exit ();
}