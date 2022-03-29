<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
define ( "DIRECTACESS", "true" );
if (! file_exists ( "admin.php" )) {
	die ( "Access is currently denied!" );
}
require_once ("admin.php");
require_once ("../helpers/Model/codegniter/ci_input.php");
require_once ("../config/general_config.php");
require_once ("../helpers/session.php");
require_once ("../helpers/Model/safeValue.php");
require_once ("../helpers/Authenticate.php");
require_once ("../helpers/Model/Member.php");
$admin_login_key = "admin_access_srm7";
$encrypted_text = "";

/*
 * #################################################################################################
 * hANDLING SUPER GLOBALS
 * ################################################################################################
 */

$_CLEANED = remove_unexpected_superglobals ( $_POST, array (
		"RegestrationEmail",
		"securityAnswer",
		"token",
		"submitBtn",
		"captcha" 
) );

$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();

// check the modes
// is admin loged in
if (isset ( $_SESSION [$admin_login_key] ) && is_array ( $_SESSION [$admin_login_key] )) {
	$ananymous_access = false;
} else {
	$ananymous_access = true;
}

$error = array ();
$page_url = basename ( $_SERVER ['PHP_SELF'] );
$is_valid = true;
$is_sent = false;

if (isset ( $allow_captcha ) && strtolower ( $allow_captcha ) != "yes") {
	$obj_captcha = false;
}

/*
 * #################################################################################################
 * hANDLING hashed key
 * ################################################################################################
 */
if (isset ( $_CLEANED ["submitBtn"] )) {
	$member = new Member ();
	$posted_password = (isset ( $_CLEANED ["token"] )) ? $_CLEANED ["token"] : '';
	// accepting incoming data, providing defaults
	if ($ananymous_access) {
		
		// there are permissions to access this form
		
		$posted_email = (isset ( $_CLEANED ["RegestrationEmail"] )) ? $_CLEANED ["RegestrationEmail"] : '';
		$posted_security_answer = (isset ( $_CLEANED ["securityAnswer"] )) ? $_CLEANED ["securityAnswer"] : '';
		
		$posted_captcha_word = isset ( $_CLEANED ["captcha"] ) ? $_CLEANED ["captcha"] : '';
		$security_check = check_login_request_security ( "", $posted_captcha_word, $obj_captcha, false );
		if ($security_check === "secure") {
			
			// checking formats of username
			if (! check_is_email ( $posted_email )) {
				$is_valid = false;
				$error [] = "Invalid  Email Formats";
			} else {
				// The Authentication checking
				
				if ($posted_email === $admin_email && $posted_security_answer === $admin_security_answer) {
					// make sure password is a valid password formats
					if (check_password_formats ( $posted_password )) {
						$is_sent = true;
						$encrypted_text = $member->hashpassword ( $posted_password );
						// hash
					} else {
						$is_valid = false;
						$error [] = "Invalid new password formats, please enter a more strong password!";
					}
				} else {
					$is_valid = false;
					$error [] = "The Admin email or the answer to the security question is not matching the saved records!";
				}
			}
		} else {
			$is_valid = false;
			$error [] = "captcha is not correct";
		}
	} else {
		// case admin access
		if (check_password_formats ( $posted_password )) {
			$is_sent = true;
			$encrypted_text = $member->hashpassword ( $posted_password );
			// hash
		} else {
			$is_valid = false;
			$error [] = "Invalid new password formats, please enter a more strong password!";
		}
	}
}

?>
<!DOCTYPE html>
<html>

<head>

<meta charset='UTF-8' />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Smart Report Maker Forgot Password</title>

<link type='text/css' rel='stylesheet'
	href='../Js/bootstrap/css/bootstrap.min.css' />



<style>
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000;
	direction: ltr;
	/* background-image: url(../../layout/images/cream_pixels.png); */
	background-repeat: repeat;
}

.securityQuestion {
	font-size: 14px;
	color: #ff0000;
	font-weight: bold;
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
                            <img src="../images/icons/logo.jpg" class="app-logo"
					alt='Logo picture' />
			</div>
		</div>
		<!-- .header -->


		<div id='formContainer'>
			<form role="form" action="<?php echo basename($page_url);?>" method="post">
                             <?php if($ananymous_access){?>
				<div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="username">User name</label> -->
						<div id='usernameContainer' class="left-inner-addon">
							<i class="glyphicon glyphicon-user"></i> <input type="text"
								class="form-control" name="RegestrationEmail"
								value="<?php if(isset($_CLEANED["RegestrationEmail"]))  echo $_CLEANED["RegestrationEmail"];?>"
								placeholder="Admin Email"> <span id="usernameFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
				<div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="username">User name</label> -->
						<div id='usernameContainer' class="left-inner-addon">
							<span class="securityQuestion"> <?php echo $admin_security_questions[$admin_security_question_index - 1]; ?> </span>

						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>

				<div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="username">User name</label> -->
						<div id='usernameContainer' class="left-inner-addon">
							<input type="text" class="form-control" name="securityAnswer"
								value="<?php if(isset($_CLEANED["securityAnswer"]))  echo $_CLEANED["securityAnswer"];?>"
								placeholder="Answer To The Security Question"> <span
								id="securityAnswerFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
				<?php } ?>
				
				<div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="username">User name</label> -->
						<div id='usernameContainer' class="left-inner-addon">
							<i class="glyphicon glyphicon-lock"></i> <input type="password"
								class="form-control" name="token" value=""
								placeholder="New Password"> <span id="tokenFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
				
			
                               <?php if($obj_captcha && $ananymous_access){?>
			    <div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>

					<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<div class="left-inner-addon">

							<img src="../images/captcha_image.php" width="359" height="40"
								alt="CAPTCHA">
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
								id="captcha" name="captcha" placeholder="Captcha"> <span
								id="captchaFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
			<?php } ?>   
                    <div class='row'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">

						<button id='loginBtn' name="submitBtn"
							class='btn btn-info btn-lg btn-block'>Submit</button>
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
				
				<?php
												}
												if ($is_sent) {
													?>
             <div class='row'>
			<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
			<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
				<br />
				<div class="alert alert-success">

					<div>

						<strong class="securityQuestion">Please copy the following
							encrypted value : </strong> <strong> <br />
						<b><u><?php echo $encrypted_text;	?></u></b><br /></strong>
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


                        <script src='../Js/jquery-2.2.3.min.js'></script>
                        <script src='../Js/bootstrap/bootstrap/js/bootstrap.min.js'></script>

</body>

</html>