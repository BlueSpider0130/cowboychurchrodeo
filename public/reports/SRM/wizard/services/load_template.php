<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
define("DIRECTACESS",1);
defined('BASEPATH') or define('BASEPATH',1);
require_once("../request.php");
$_GET= array();
$_POST=clean_input_array($_POST);
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();
require_once("functions.php");
$response = array ();
$response ["result"] = "";
$response ["errorMessage"] = "";
require_once("../../Reports8/shared/helpers/Model/codegniter/file_helper.php");
require_once("../../Reports8/shared/helpers/Model/safeValue.php");
require_once("../../Reports8/shared/helpers/Model/Template.php");
require_once("../../Reports8/shared/helpers/Model/TemplateManger.php");
$TemplateManager = new  TemplateManager("../../Reports8/",$_SESSION ['srm_f62014_user'],$_SESSION ['srm_f62014_db']);
//getting all templates
$all_templates  = $TemplateManager->get_all_templates();


if (! isset ( $_POST ["token"] ) || empty ( $_POST ["token"] ) || $_POST ["token"] != $_SESSION ["request_token_wizard"]) {
	send_error_response ( " Not allowed for security reasons!" );
	exit ();
} 

elseif (! is_connected ()) {
	send_error_response ( " Must be connected to load a saved template" );
	exit ();
}

// Must be saved templates for the username in session

elseif (! is_array ( $all_templates ) && empty ( $all_templates )) {
	send_error_response ( " No saved templates" );
	exit ();
}

// recieve and validate the sent parameters the action should be load or unload
elseif (empty ( $_POST ["template"] ) || ! $TemplateManager->is_exist($_POST ["template"],false)) {
	send_error_response ( "  Template dosn't exist" );
	exit ();
}

// template is avalid template name and user has permission to access that particular template

elseif (empty ( $_POST ["action"] ) || ! in_array ( $_POST ["action"], array (
		"load",
		"unload" 
) )) {
	send_error_response ( " Action is not recognized" );
	exit ();
} 

else {
	// load or unload a template
	if ($_POST ["action"] == "load")
		$result =$TemplateManager-> load_template( $_POST ["template"] );
	else
		$result = $TemplateManager-> Un_load_template();
	
	if ($result == true) {
			if ($_POST ["action"] == "load")
			 $_SESSION ['template_value'] = $_POST["template"];
		
			
		$response ["result"] = "success";
	} else {
		$response ["result"] = "error";
		$response ["errorMessage"] = "template can't be loaded";
	}
	$response = json_encode ( $response );
	echo $response;
	exit ();
}
	


?>