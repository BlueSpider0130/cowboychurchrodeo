<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
ob_start();
$is_demo = false;
define ( "DIRECTACESS", 1 );

$all_views = array (
		"Home",
		"profile",
		"logout" 
);
// includes
require_once ("request.php");

$_CLEANED = remove_unexpected_superglobals ( $_POST, array (
		"username",
		"isChangePassword",
		"old_password",
		"password",
		"ConfirmPassword",
		"Email",
		"secQuestion",
		"SecAnswer",
		"isFixedIP",
		"ip",
		"ishome",
		"homeURL",
		"iscaptcha" ,
		"request_token",
		"save"
) );

$_CLEANED = array_merge ( $_CLEANED, remove_unexpected_superglobals ( $_GET, array ("v","request_token","del","legacy") ) );
$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();






// routing

if (isset ( $_CLEANED ['v'] ) && isset ( $_CLEANED ["request_token"] ) && check_numeric_parameter ( $_CLEANED ["v"] ) && $_CLEANED ["v"] < 4 && $_CLEANED ["v"] > 0) {
	//new valid request
	$view_index = ( int ) $_CLEANED ['v'];
	$_SESSION ["loaded_view_SRM7"] = $view_index ;
} elseif (isset ( $_SESSION ["loaded_view_SRM7"] )) {
	//case not a valid request, stay in current request
	$view_index = ( int ) $_SESSION ["loaded_view_SRM7"];
} else {
	$view_index = 1;
}

if (is_numeric ( $view_index ) && $view_index > 0 && $view_index < 4)
	$current_view = $all_views [$view_index - 1];
else
	$current_view = "Home";
	// check view file exists otherwise load home
if (! file_exists ( "views/" . $current_view . ".php" ))
	$current_view = "Home";

?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Report Maker | Home Page</title>
<script type="text/javascript" src="Js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="Js/bootstrap.min.js"></script>
<!-- <link href="medi2.css" rel="stylesheet" type="text/css"> -->
<link href="styles/general.min.css" rel="stylesheet" type="text/css">
<link href="styles/main.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-8 col-xs-push-2">

				<div class="all_records pull-right" style="margin: 10px;">
				
				<?php
				require_once ("menu.php");
				require_once ("views/" . $current_view . ".php");
				ob_end_flush();
				?>
				
	
				</div>
			</div>
		</div>
	</div>


</body>

<?php if($current_view === "profile") {?>
<script type="text/javascript" src="Js/profile.js"> </script>

<?php } elseif($current_view === "Home"){?>
<script type="text/javascript" src="Js/home.js"> </script>
<?php }?>
	
</script>

</html>
