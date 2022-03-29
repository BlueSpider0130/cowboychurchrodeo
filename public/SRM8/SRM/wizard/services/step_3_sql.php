<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
// handling super globals
define ( "DIRECTACESS", 1 );
require_once ("../request.php");
require_once("functions.php");
if (!is_connected() ) {
    echo ( " Must be connected to run this script" );
    exit();
}
$_GET = array ();
if(isset($_POST["validate_sql"])){
$_POST = array("validate_sql"=>$_POST["validate_sql"]);
}elseif(isset($_POST ["continue_sql"])){
 $_POST = array("continue_sql" =>$_POST ["continue_sql"]);  
}elseif(isset($_POST ['selected_view'])){
   $_POST = array('selected_view'=>$_POST ['selected_view']) ;
}else{
  $_POST = array() ;
}
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();

require_once ("../lib.php");
require_once "sessionCleaner.php";

if (isset ( $_POST ['selected_view'] )) {
	$selectedView = $_POST ['selected_view'];
	if ($selectedView === 'None') {
		unset ( $_SESSION ['srm_f62014_view'] );
		echo '';
		exit ();
	}
	$listOfViews = array ();
	$views = $dbHandler->query ( "SHOW FULL TABLES IN `$db` WHERE TABLE_TYPE LIKE 'VIEW'" );
	foreach ( $views as $value )
		$listOfViews [] = $value [0];
	$numOfViews = $dbHandler->get_num_rows ();
	if ($numOfViews > 0 && in_array ( $selectedView, $listOfViews )) {
		$_SESSION ['srm_f62014_view'] = $selectedView;
		$sql = $dbHandler->query ( "SHOW CREATE VIEW `$db`.`" . $selectedView . '`' );
		$sql = $sql [0] [1];
		// $sql = substr($sql , stripos($sql, 'select'));
		$sql = stristr ( $sql, 'select' );
		echo $sql;
	} else {
		echo '';
	}
	exit ();
}

// validate and set sql statement in session
if (isset ( $_POST ["continue_sql"] ) || isset ( $_POST ['validate_sql'] )) {
       
	unsetSessionStartFromDataSource ();
	$sql = (isset ( $_POST ["continue_sql"] )) ? make_valid ( $_POST ['continue_sql'] ) : make_valid ( $_POST ['validate_sql'] );
	
	if (is_valid_select_sql ( $sql ) !== true) {
		echo is_valid_select_sql ( $sql );
                
		exit ();
	}
	
	// if(!empty($sql) && $sql !== '' && !strpos(strtolower($sql), 'order by') && !strpos(strtolower($sql), 'group by') && !strpos(strtolower($sql),'limit'))
	if ( $sql !== '' && ! strpos ( strtolower ( $sql ), 'order by' ) && ! strpos ( strtolower ( $sql ), 'limit' )) {
		
            $result = $dbHandler->query( $sql );
                
                
		list ( $is_connection_failed, $connection_error ) = $dbHandler->is_connection_failed ();
		
		if ($result === false)
			echo " Invalid SQL statement ";
		else if ($is_connection_failed)
			echo " Invalid SQL connection ";
		else {
			$rows = $dbHandler->get_num_rows ();
			$_SESSION ['srm_f62014_sql'] = trim ( str_replace ( ";", "", $sql ) );
			echo "success|$rows";
		}
	} else if (empty ( $sql ) || $sql === '')
		echo "Please enter SQL statement";
	else if (strpos ( strtolower ( $sql ), 'order by' ))
		echo " You don't need to add 'Order By' in the sql statement because sorting options will be specified in a next step!";
		// else if(strpos(strtolower($sql),'group by')) echo " 'group by' is not allowed in the sql statement, it could be done visually in a next step!";
	else if (strpos ( strtolower ( $sql ), 'limit' ))
		echo " You don't need to add 'limit'in the sql statement because paggination options will be specified visually in a next step! ";
	else
		echo " Invalid SQL statement ";
	exit ();
}
function is_valid_select_sql($sql) {
   
	$sql = strtolower ( $sql );
	$must = array (
			"select",
			"from" 
	);
	$forbidden = array (
			"drop ",
			"delete ",
			"insert ",
			"update ",
			"describe ",
			"desc ",
			"show ",
			"create " 
                        
	);
	
	foreach ( $must as $value )
		if (! strstr ( $sql, $value ))
			return '\'' . $value . '\' must be used in sql statement';
	
	foreach ( $forbidden as $value )
		if (strstr ( $sql, $value ))
			return '\'' . $value . '\' is not allowed in the sql statement';
	
	return true;
}