<?php
define ( 'BASEPATH', 1 ); // defining the constant of codegniter
if (! defined ( "DIRECTACESS" ))
	exit ( "No direct script access allowed" );
$error = array ();
$success = false;

if (isset ( $_CLEANED ["send"] )) {
	
	$prof_username = isset ( $_CLEANED ["username"] ) ? $_CLEANED ["username"] : "";
	$prof_email = isset ( $_CLEANED ["Email"] ) ? $_CLEANED ["Email"] : "";
	$prof_sec_question_index = isset ( $_CLEANED ["secQuestion"] ) ? $_CLEANED ["secQuestion"] : "";
	$prof_answer = isset ( $_CLEANED ["SecAnswer"] ) ? $_CLEANED ["SecAnswer"] : "";
	$prof_is_fixed_ip = isset ( $_CLEANED ["isFixedIP"] ) ? $_CLEANED ["isFixedIP"] : "";
	$prof_is_home_icon = isset ( $_CLEANED ["ishome"] ) ? $_CLEANED ["ishome"] : "";
	$prof_is_captcha = isset ( $_CLEANED ["iscaptcha"] ) ? $_CLEANED ["iscaptcha"] : "";
	$prof_ip = isset ( $_CLEANED ["ip"] ) ? $_CLEANED ["ip"] : "";
	$prof_home_url = isset ( $_CLEANED ["homeURL"] ) ? $_CLEANED ["homeURL"] : "";
	$prof_is_change_password = "yes";
	
	if ($prof_username == "" || !check_username_formats($prof_username,$max_length_username_new, $min_length_username_new)) {
		$error [] = "Please enter a valid username! A valid username should be between $min_length_username_new and $max_length_username_new alphanumeric characters ";
	}
	
	if ($_CLEANED ["password"] == "" || !check_password_formats ( $_CLEANED ["password"] )) {
		$error [] = "Please enter a valid password! A valid password should be between $minimum_password_length and $maximum_password_length alphanumeric characters";
	}
	
	if ($_CLEANED ["password"] != $_CLEANED ["ConfirmPassword"]) {
		$error [] = "Password confirmation doesn't match password!";
	}
	
	if ($prof_email == "" || ! filter_var ( $prof_email, FILTER_VALIDATE_EMAIL )) {
		$error [] = "Please enter a valid email in the 'Email' field";
	}
	
	if (! is_numeric ( $prof_sec_question_index ) || $prof_sec_question_index < 1 || $prof_sec_question_index >= 7) {
		$error [] = "Please choose a valid security question!";
	}
	
	if ($prof_answer == "" || ! check_is_clean ( $prof_answer ) || strlen ( $prof_answer ) > 100) {
		$error [] = "Please enter a valid answer to the security question!";
	}
	
	$temp_arr = array (
			"yes",
			"no" 
	);
	
	if (! in_array ( strtolower ( trim ( $prof_is_fixed_ip ) ), $temp_arr )) {
		$error [] = "Please enter a valid choice in the 'Allow login only from certain IP addresses' field! ";
	}
	
	if (! in_array ( $prof_is_captcha, $temp_arr )) {
		$error [] = "Please enter a valid choice in the 'Enable captcha' field! ";
	}
	
	if (! in_array ( $prof_is_home_icon, $temp_arr )) {
		$error [] = "Please enter a valid choice in the 'Show a home icon in generated reports' field! ";
	}
	
	if (strtolower ( trim ( $prof_is_fixed_ip ) ) == "yes" && (! filter_var ( $prof_ip, FILTER_VALIDATE_IP ) || $prof_ip == "")) {
		$error [] = "Please enter a valid ip in the 'Admin IP address' Field ";
	}
	
	if (strtolower ( trim ( $prof_is_home_icon ) ) == "yes" &&  $prof_home_url == "") {
		$error [] = "Please enter a valid URL in the 'Home URL' field";
	}
	
	if (empty ( $error )) {
		require_once ("../SRM/Reports8/shared/helpers/Model/codegniter/Common.php");
		$profile->new_username = $prof_username;
		$profile->new_email = $prof_email;
		$profile->new_answer_of_security_question = $prof_answer;
		$profile->new_security_question_index = $prof_sec_question_index;
		$profile->new_is_home = (strtolower ( trim ( $prof_is_home_icon ) ) == "no") ? "no" : "yes";
		$profile->new_is_captcha = (strtolower ( trim ( $prof_is_captcha ) ) == "no") ? "no" : "yes";
		$profile->new_is_fixed_ip = (strtolower ( trim ( $prof_is_fixed_ip ) ) == "yes") ? "yes" : "no";
		$profile->is_change_password = "yes";
	
		$member = new Member ();
		$profile->new_password = $member->hashpassword ( $_CLEANED ["password"] );
		
		if (strtolower ( trim ( $prof_is_fixed_ip ) ) == "yes") {
			$profile->new_admin_ip = $prof_ip;
		} else {
			$profile->new_admin_ip = $profile->get_admin_ip ();
		}
		
		if (strtolower ( trim ( $prof_is_home_icon ) ) == "yes") {
			$profile->new_home_url = $prof_home_url;
		} else {
			$profile->new_home_url = $profile->get_home_url ();
		}
		// checking if a change is done
		if (! is_really_writable ( dirname ( $admin_file ) ) || ! is_writable ( dirname ( $admin_file ) )) {
			
			$error [] = "The admin config file is not writable, please make sure you gave it a 755 permissions";
		} else {
			$result = $profile->update ( true );
			if ($result) {
				$_SESSION [$install_session_key . "_last_view"] = 2;
				// proceed
				$page = $_SERVER['PHP_SELF'];
                                echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
                                exit();
			} else {
				$error [] = "Changes could not be saved!";
			}
		}
		
		// update if a change is done
	}
} else {
	// get original saved data
	$prof_username = "";
	$prof_email = "";
	$prof_sec_question_index = 1;
	$prof_answer = "";
	$prof_is_fixed_ip = "no";
	$prof_is_home_icon = "yes";
	$prof_is_captcha = "yes";
	$prof_ip = $_SERVER ["REMOTE_ADDR"];
	$prof_home_url = $homepage_exact_url;
	$prof_is_change_password = "yes";
}
$_SESSION ["request_token"] = $request_token_value;

function validateURL($URL) {
	$pattern_1 = "/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
	$pattern_2 = "/^(www)((\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
	if(preg_match($pattern_1, $URL) || preg_match($pattern_2, $URL)){
		return true;
	} else{
		return false;
	}
}
?>
<div class="panel-body text-center">
	<?php
	
	if (! empty ( $error )) {
		$alert = "** ";
		$alert .= implode ( "<br/>** ", $error );
		
		?>
					<div class="alert alert-danger" role="alert">

		<p><?php echo $alert;?></p>
	</div>
				<?php }?>
				
			

	<form method="post"
		action="<?php echo basename($_SERVER["PHP_SELF"]) ?>">
		<input type="hidden" id="request_token" name="request_token"
			value="<?php echo $request_token_value;?>" />
		<fieldset class="form-horizontal" id="inputs">

			<legend style="text-align: left; color: blue;">Admin Login
				information</legend>
			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Username</label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="text" class="form-control" id="username"
							name="username" placeholder="Username"
							value="<?php echo $prof_username;?>"> <span
							class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#username-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="username-help">Specify the
						admin username you'd like to use to access the admin area. Must be
						at least 8 characters.</span>
				</div>
			</div>








			<div class="row form-group pass">
				<label for="username" class="control-label col-sm-4"> Password</label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="password" class="form-control" id="password"
							name="password" placeholder="password"> <span
							class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#password-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="password-help">Specify a
						strong password to access the admin area.</span>
				</div>
			</div>

			<div class="row form-group pass">
				<label for="username" class="control-label col-sm-4">Confirm
					Password</label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="password" class="form-control" id="ConfirmPassword"
							name="ConfirmPassword" placeholder="Confirm Password"> <span
							class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#ConfirmPassword-help" class="btn btn-info"
								type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="ConfirmPassword-help">Type
						the same password again.</span>
				</div>
			</div>

			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Email</label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="text" class="form-control" id="Email" name="Email"
							placeholder="Email" value="<?php echo $prof_email;?>"> <span
							class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#Email-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="Email-help">Enter the email
						address where you want admin notifications to be sent..</span>
				</div>
			</div>

			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Security
					Question</label>
				<div class="col-sm-8">
					<div class="input-group">
						<Select class="form-control" id="SecQuestion" name="secQuestion"
							placeholder="security Question">
							<?php
							$i = 1;
							foreach ( $admin_security_questions as $q ) {
								$option = "<option value='$i' ";
								if ($prof_sec_question_index == $i)
									$option .= " selected ";
								$option .= ">$q</option>";
								echo $option;
								$i ++;
							}
							?>
						</Select> <span class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#secQuestion-help" class="btn btn-info"
								type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="Email-help">Select a security
						question to be used when resetting your password.</span>
				</div>
			</div>

			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">The answer of
					the security question</label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="text" class="form-control" id="SecAnswer"
							name="SecAnswer" placeholder="Answer of the security question"
							value="<?php echo $prof_answer;?>"> <span class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#SecAnswer-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="SecAnswer-help">Enter the
						answer of your security question.</span>
				</div>
			</div>
			<legend style="text-align: left; color: blue;">Settings</legend>
			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Allow login
					only from certain IP addresses? (only for the admin)</label>
				<div class="col-sm-8">
					<div class="input-group">
						<select class="form-control" id="isFixedIP" name="isFixedIP"
							placeholder="Allow admin login only from certain IP addresses">
							<option value="no"
								<?php if(strtolower(trim($prof_is_fixed_ip)) == "no") echo "selected";  ?>>No</option>
							<option value="yes"
								<?php if(strtolower(trim($prof_is_fixed_ip)) == "yes") echo "selected";  ?>>Yes</option>
						</select> <span class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#isFixedIP-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="isFixedIP-help">Admin login
						can be allowed only from a certain IP address, for security
						reasons. Please use this feature only if you are using a fixed IP
						address.</span>
				</div>
			</div>

			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Admin IP
					address</label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="text" class="form-control" id="ip" name="ip"
							placeholder="Admin IP address" value="<?php echo $prof_ip;?>"> <span
							class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#IP-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="IP-help">Admin will be able
						to login only from this ip address.</span>
				</div>
			</div>

			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Show a home
					icon in generated reports? (only for admin)</label>
				<div class="col-sm-8">
					<div class="input-group">
						<select class="form-control" id="ishome" name="ishome">
							<option value="no"
								<?php if(strtolower(trim($prof_is_home_icon)) == "no") echo "selected";  ?>>Hide
								the home icon</option>
							<option value="yes"
								<?php if(strtolower(trim($prof_is_home_icon)) == "yes") echo "selected";  ?>>Show
								the home icon</option>
						</select> <span class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#ishome-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="ishome-help">Should the admin
						see a home icon on generated reports to ease navigation between
						generated reports. Please be aware that admin can access any
						report public or private with his login information (username and
						password)</span>
				</div>
			</div>

			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Home URL</label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="text" class="form-control" id="homeURL"
							name="homeURL" placeholder="Home URL"
							value="<?php echo $prof_home_url;?>"> <span
							class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#home-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="home-help">The URL to which
						the admin will be redirected when clicking the 'home' icon in any
						generated report (note that admin can access all reports without
						further authentications).</span>
				</div>
			</div>

			<div class="row form-group">
				<label for="username" class="control-label col-sm-4">Enable captcha
					? (for all users)</label>
				<div class="col-sm-8">
					<div class="input-group">
						<select class="form-control" id="iscaptcha" name="iscaptcha">
							<option value="no"
								<?php if($prof_is_captcha == "no") echo " selected "?>>Disable
								Captcha</option>
							<option value="yes"
								<?php if($prof_is_captcha == "yes") echo " selected "?>>Enable
								Captcha</option>
						</select> <span class="input-group-btn">
							<button data-toggle="collapse" tabindex="-1"
								data-target="#iscaptcha-help" class="btn btn-info" type="button">
								<i class="glyphicon glyphicon-info-sign"></i>
							</button>
						</span>
					</div>
					<span class="help-block collapse" id="iscaptcha-help">It's very
						recommended to keep the captcha enabled for security reasons!</span>
				</div>
			</div>

			<div class="col-sm-offset-3 col-sm-6">
				<button class="btn btn-default" id="send" name="send" value="send"
					type="submit" tabindex="-1">
					Continue <i class="glyphicon glyphicon-chevron-right"></i>
				</button>

			</div>

</div>