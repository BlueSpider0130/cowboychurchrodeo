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

if(isset($_POST ["subtotal"])&& $_POST["subtotal"]== "enabled" )
{
    if(isset($_SESSION["srm_f62014_group_by"])&& count($_SESSION["srm_f62014_group_by"]) == 0)
    {
        echo "At least One Grouping  should be selected from the 'grouping' step. Otherwise, you can uncheck the 'Allow Subtotals' checkbox ";
        exit ();
    }elseif(isset($_POST["affectedcolumns"]) &&$_POST["affectedcolumns"] == "null" )
    {
        echo "At least One Affected Columns should be selected. Otherwise, you can uncheck the 'Allow Subtotals' checkbox";
        exit ();
    }
    elseif(isset($_POST["selected_function"]) &&$_POST["selected_function"] == "null" )
    {
        echo "At least One Function should be selected or un checked Allow Sub Totals";
        exit ();
    }else{
        $_SESSION["srp_subtotals_enabled"]= true;
        $affected_columns = explode(",", $_POST["affectedcolumns"]);
        $_SESSION["srp_sub_totals"] = array(
            "group_by" => $_POST["group_by"],
            "function" => $_POST["selected_function"],
            "affected_columns" => $affected_columns
        );
       


        echo "success";
	    exit (); 
    }


}
elseif(isset($_POST ["subtotal"])&& $_POST ["subtotal"]== "disabled")
{
    echo "success";
	exit ();
}











?>