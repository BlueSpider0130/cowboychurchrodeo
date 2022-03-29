 <?php
 /**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
error_reporting(0);
define ( "DIRECTACESS", "true" );
require_once("../../Reports8/shared/helpers/session.php");
if(!isset($_SESSION["admin_access_SRM7"])){
    die("Invalid request. Not loggedin!");
}
$is_exit = (isset($_POST["stay"]))? false : true;
if($is_exit){
session_end();
}else{
	//disconnect without exit 
	require_once("functions.php");
	disconnect();
}
?>

