<?php
ob_start();
define ( "DIRECTACESS", 1 );

$all_views = array (
		"home",
	
		"profile",
		"confirmation"
);
// includes
require_once ("request.php");

$_CLEANED = remove_unexpected_superglobals ( $_POST, array (
		"username",
		
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
		"send"
) );

$_CLEANED = array_merge ( $_CLEANED, remove_unexpected_superglobals ( $_GET, array ("go") ) );
$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();





// routing


if (isset ( $_SESSION[$install_session_key."_last_view"]))   {
	//new valid request
	$last_view= ( int ) $_SESSION[$install_session_key."_last_view"];
    $current_view_index = $last_view + 1;
    $current_view = $all_views [$current_view_index - 1];
}	
else{
	$current_view = "home";
}
	// check view file exists otherwise load home
if (! file_exists ( "views/" . $current_view . ".php" ))
	$current_view = "home";

	if($current_view == "confirmation" && isset ( $_SESSION[$install_session_key."_last_view"]) && $_SESSION[$install_session_key."_last_view"] == 2){
	//by the 3rd step a confirmation file must be in place
		if (!file_exists ( $admin_file ) || filesize ( $admin_file ) == 0) {
			die ( "Error 405 : Installation process failed!" );
		}
		
	}else{
		//if you in 1st or second step then there must be no admin file on place
		if (file_exists ( $admin_file ) && filesize ( $admin_file ) > 1) {
			die ( "Error 401 : System is already installed" );
		}
		
		
		
	}

?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Report Maker | Install Page</title>
<script type="text/javascript" src="../HomePage/Js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../HomePage/Js/bootstrap.min.js"></script>
<!-- <link href="../HomePage/medi2.css" rel="stylesheet" type="text/css"> -->
<link href="../HomePage/styles/general.min.css" rel="stylesheet" type="text/css">
<link href="../HomePage/styles/main.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-8 col-xs-push-2">

				<div class="all_records pull-right" style="margin: 10px;">
                                    <a target="_blank" class="hidden-xs" href="<?php if(isset($help_file)) echo $help_file;?>" ><i class="glyphicon glyphicon-question-sign"></i> User Guide</a>
				
				
				</div>
				<br/>
				
				
				<?php
				
				require_once ("views/" . $current_view . ".php");
				ob_end_flush();
				?>
				
	
				</div>
			</div>
		</div>
	</div>


</body>


	


</html>
