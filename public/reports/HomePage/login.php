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
define ( "DIRECTACESS", "true" );
require_once ("request.php");
require_once ("../SRM/Reports8/shared/helpers/Authenticate.php");


/*
 * #################################################################################################
 * hANDLING SUPER GLOBALS
 * ################################################################################################
 */

$_CLEANED = remove_unexpected_superglobals ( $_POST, array (
		"name",
		"pass",
		"loginBtn",
		"request_token",
		"captcha" 
) );

$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();
$error = array ();
$page_url = basename ( $_SERVER ['PHP_SELF'] );
$is_valid = true;

if ( trim(strtolower ($profile->get_is_captcha()) ) != "yes") {
	$obj_captcha = false;
}

/*
 * #################################################################################################
 * hANDLING hashed key
 * ################################################################################################
 */
if (isset ( $_CLEANED ["loginBtn"] )) {
	
	$allowed_roles = "admin";
	
	// there are permissions to access this form
	
	$posted_password = (isset ( $_CLEANED ['pass'] )) ? $_CLEANED ['pass'] : '';
	$posted_username = (isset ( $_CLEANED ['name'] )) ? $_CLEANED ['name'] : '';
	
	$posted_hash = (isset ( $_CLEANED ["request_token"] )) ? $_CLEANED ["request_token"] : '';
	$posted_captcha_word = isset ( $_CLEANED ["captcha"] ) ? $_CLEANED ["captcha"] : '';
	$security_check = check_login_request_security ( $posted_hash, $posted_captcha_word, $obj_captcha );
	if ($security_check === "secure") {
		
		// checking formats of username
		if ($posted_username == ""||  strtolower($posted_username) == "admin"|| ! check_username_formats ( $posted_username, $max_length_username_new, $min_length_username_new )) {
			$is_valid = false;
			
			$error [] = "Formatting error : wrong username";
		} // checking formats of passwords
elseif ($posted_password == "" ||! check_password_formats ( $posted_password )) {
			$is_valid = false;
			$error [] = "Formatting error : wrong password";
		} else {
			// The Authentication checking
			
			$cleaned_username = clean_input ( $posted_username );
			$cleaned_password = clean_input ( $posted_password );
			
			
			$is_fixed_ip = (strtolower ( trim ( $profile->get_is_fixed_ip () ) ) == "yes") ? true : false;
			$ip = ($is_fixed_ip == true) ? $profile->get_admin_ip () : "";
			$admin = new Admin ( $profile->get_username (), $profile->get_current_password (), $is_fixed_ip, $ip );
			
			if ($admin->authenticate ( $cleaned_username, $cleaned_password ) === true) {
				
				// add profile in the session
				
				$session_key = $admin_login_key;
				
				// loading the profile in the admin session
				$_SESSION [$session_key] = array (
						"username" => $cleaned_username,
						"role" => "admin",
						"ip" => $_SERVER ['REMOTE_ADDR'],
						"user_agent" => $_SERVER ['HTTP_USER_AGENT'] 
				);
				// Redirect
				ob_end_clean();
				header ( "Location: index.php?v=1&&request_token=$request_token_value" );
				exit ();
			} else {
				$is_valid = false;
				$error [] = "Authentication error! Please enter valid username and password ";
			}
		}
	} else {
		$is_valid = false;
		$error [] = $security_check;
	}
}

// save the request token in the session

$_SESSION ["request_tokenLogin"] = $request_token_value;

if (! $is_valid && ! empty ( $error ))
	header ( 'HTTP/1.0 403 Forbidden' );
    ob_end_flush();
?>
<!DOCTYPE html>
<html>

<head>

<meta charset='UTF-8' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="X-XSS-Protection" content=1>

<title>Smart Report Maker Login Page</title>

<link type='text/css' rel='stylesheet' href='Js/bootstrap.min.css' />



<style>
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000;
	direction: ltr;
	/* background-image: url(../../layout/images/cream_pixels.png); */
	background-repeat: repeat;
}

.img {
	max-width: 100%;
	height: auto;
}

#tabs-2 {
	position: relative;
}

.wizard-tbl {
	position: relative;
}

.wizard-tbl td {
	height: 42px;
	padding-left: 5px;
}

.app-logo {
	margin-top: 5px;
	margin-right: auto;
	margin-bottom: 5px;
	margin-left: auto;
	max-width: 100%;
	height: auto;
}

#formContainer {
	margin-top: 40px;
}

.left-inner-addon {
	position: relative;
}

.left-inner-addon input {
	height: 40px;
	padding-left: 30px;
}

.left-inner-addon i {
	position: absolute;
	padding: 13px 12px;
	pointer-events: none;
}

.popover-content {
	color: #4e4e4e;
	font-size: 14px;
	font-family: "Lobster", Georgia, Times, serif;
	letter-spacing: 1px;
}

#forgetPass {
	margin-top: 25px;
}

#usernameFeedback, #passwordFeedback, #retrievePassFeedback {
	top: 3px;
}
</style>



</head>

<body>

	<div class='container'>

		<div class="header">
			<div style="text-align: center">
				<img src="../SRM/Reports8/shared/images/icons/logo.jpg"
					class="app-logo" alt='Logo picture' />
			</div>
		</div>
		<!-- .header -->


		<div id='formContainer'>
			<form role="form" action="<?php echo basename($page_url);?>"
				method="post">

				<div class='row'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="username">User name</label> -->
						<div id='usernameContainer' class="left-inner-addon">
							<i class="glyphicon glyphicon-user"></i> <input type="text"
								class="form-control" id="name" name="name"
								value="<?php if(isset($_CLEANED["name"]))  echo $_CLEANED["name"];?>"
								placeholder="<?php echo "Username"?>"> <span
								id="usernameFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
				<div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="password">Password</label> -->
						<div id='passwordContainer' class="left-inner-addon">
							<i class="glyphicon glyphicon-lock"></i> <input type="password"
								class="form-control" id="pass" name="pass" AUTOCOMPLETE='OFF'
								placeholder="<?php echo "Password";?>"> <span
								id="passwordFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
                               <?php if($obj_captcha){?>
			    <div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>

					<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<div class="left-inner-addon">

							<img src="../SRM/Reports8/shared/images/captcha_image.php"
								width="359" height="40" alt="CAPTCHA">
						</div>


					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
				<div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="captcha">captcha</label> -->
						<div id='passwordContainer' class="left-inner-addon">
							<i class=""></i> <input type="text" class="form-control"
								id="captcha" name="captcha"
								placeholder="<?php echo "Please enter the security code"; ?>"> <span
								id="captchaFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
			<?php } ?>   
                    <div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<input type="hidden" name="request_token"
							value="<?php echo $request_token_value; ?>" />

						<button id='loginBtn' name="loginBtn"
							class='btn btn-info btn-lg btn-block'><?php echo "Login"; ?></button>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
				<div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<div class="text-left">

							<a href="Forgot_password.php" class="forgot-password"><u><?php echo "Forgot Password" ?></u></a>
						</div>

					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>


			</form>
		</div>
           
            <?php
												
												if (! $is_valid) {
													// just formatting wise
													if (isset ( $error [0] ))
														$error [0] = "** " . $error [0];
													
													?>
             <div class='row'>
			<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
			<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
				<br />
				<div class="alert alert-danger alert-dismissable">

					<div>

						<strong><?php echo implode('<br /> **  ', $error);	?></strong>
					</div>
				</div>
			</div>
		</div>
				
				<?php } ?>


            <div class='row'>
			<div class="col-lg-1"></div>
			<div class="col-lg-10">
				<hr style='height: 1px; background: #dfdfdf;' />
			</div>
			<div class="col-lg-1"></div>
		</div>
		<div>
			<!-- container -->


                        <script src='Js/jquery-2.2.3.min.js'></script>
			<script src='Js/bootstrap.min.js'></script>

</body>

</html>