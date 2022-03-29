<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
define ( "DIRECTACESS", "true" );require_once ("request.php");
require_once ("../shared/config/email_templates.php");
require_once ("../shared/helpers/Authenticate.php");


/*
 * #################################################################################################
 * hANDLING SUPER GLOBALS
 * ################################################################################################
 */

$_CLEANED = remove_unexpected_superglobals ( $_POST, array (
		"RegestrationEmail",
		
		"forgotBtn",
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
$is_sent = false;

if (isset ( $allow_captcha ) && strtolower ( $allow_captcha ) != "yes") {
	$obj_captcha = false;
}

/*
 * #################################################################################################
 * hANDLING hashed key
 * ################################################################################################
 */
if (isset ( $_CLEANED ["forgotBtn"] )) {
	// accepting incoming data, providing defaults
	$allowed_roles = get_allowed_roles ();
	
	if (empty ( $allowed_roles )) {
		$is_valid = false;
		$error [] = $lang_no_permissions_error;
	} else {
		// there are permissions to access this form
		
		$posted_email = (isset ( $_CLEANED ["RegestrationEmail"] )) ? $_CLEANED ["RegestrationEmail"] : '';
		
		$posted_hash = (isset ( $_CLEANED ['RequestToken'] )) ? $_CLEANED ['RequestToken'] : '';
		$posted_captcha_word = isset ( $_CLEANED ["captcha"] ) ? $_CLEANED ["captcha"] : '';
		$security_check = check_login_request_security ( $posted_hash, $posted_captcha_word, $obj_captcha );
		if ($security_check === "secure") {
			
			// checking formats of username
			if (! check_is_email ( $posted_email )) {
				$is_valid = false;
				$error [] = $lang_invalid_email_formating;
			} else {
				// The Authentication checking
				
				$profile = find_email ( $posted_email );
				if (is_array ( $profile ) && $profile !== false && (strtolower ( $profile ["role"] ) === "admin" || strtolower ( $profile ["role"] ) === "user" || strtolower ( $profile ["role"] ) === "dbmember")) {
					// send reset password instructions
					if (send_instructions ( $profile ["role"], $posted_email, $profile ["username"] )) {
						$is_sent = true;
					} else {
						$is_valid = false;
						$error [] = $SMTP_lang;
					}
				} else {
					$is_valid = false;
					$error [] = $lang_Auth_error_email;
				}
			}
		} else {
			$is_valid = false;
			$error [] = $security_check;
		}
	}
}

// save the request token in the session

$_SESSION ["request_token" . "Login"] = $request_token_value;


?>
<!DOCTYPE html>
<html
	<?php
	
	if ($language == "he" || $language == "ar") {
		echo "dir = 'rtl'";
	}
	
	?>>

<head>

<meta charset='UTF-8' />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Smart Report Maker Forgot Password</title>

<link type='text/css' rel='stylesheet'
	href='../shared/Js/bootstrap/css/bootstrap.min.css' />



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
				<img src="../shared/images/icons/logo.jpg" class="app-logo"
					alt='Logo picture' />
			</div>
		</div>
		<!-- .header -->


		<div id='formContainer'>
			<form role="form" action="<?php echo basename($page_url);?>" method="post">

				<div class='row'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="form-group col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<!-- <label for="username">User name</label> -->
						<div id='usernameContainer' class="left-inner-addon">
							<i class="glyphicon glyphicon-user"></i> <input type="text"
								class="form-control" id="name" name="RegestrationEmail"
								value="<?php if(isset($_CLEANED["RegestrationEmail"]))  echo $_CLEANED["RegestrationEmail"];?>"
								placeholder="<?php echo $lang_regestration_email;?>"> <span
								id="usernameFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
			
                               <?php if($obj_captcha){?>
			    <div class='row' style='margin-bottom: 10px;'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>

					<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<div class="left-inner-addon">

							<img src="../shared/images/captcha_image.php" width="359"
								height="40" alt="CAPTCHA">
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
								placeholder="<?php echo escape($security_code_lang); ?>"> <span
								id="captchaFeedback"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
				</div>
			<?php } ?>   
                    <div class='row'>
					<div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
					<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
						<input type="hidden" name="RequestToken"
							value="<?php echo $request_token_value; ?>" />

						<button id='loginBtn' name="forgotBtn"
							class='btn btn-info btn-lg btn-block'><?php echo $lang_send_instructions; ?></button>
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

						<strong><?php echo $lang_instructions_sent;	?></strong>
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


			<script src='../shared/Js/jquery-2.2.3.min.js'></script>
			<script src='../shared/Js/bootstrap/js/bootstrap.min.js'></script>

</body>

</html>