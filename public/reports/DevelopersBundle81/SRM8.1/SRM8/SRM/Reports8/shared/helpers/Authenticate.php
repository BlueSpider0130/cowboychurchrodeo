<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (! defined ( "DIRECTACESS" ))
	exit ( "No direct script access allowed" );
	
	/*
 * check_login_request_security
 *
 * Check the hashed token sent by the login is correct, also check the captcha
 *
 * @param (posted_hash) the token value sent through the login form
 * @param (posted_captcha_word) the posted captcha word
 * @param (obj_captcha) the capthca obect which can become false if captcha is disabled
 *
 * @return "secure" if every thing correct, else it returned the specific error message matching the case.
 */
function check_login_request_security($posted_hash = "", $posted_captcha_word, $obj_captcha, $is_validate_request_token = true) {
	global $invalid_request_lang, $invalid_code_lang, $allow_request_token_login;
	// checking the hash key of the request
	$request_token = "request_token";
	
	
	
	if ($is_validate_request_token && (isset ( $_SESSION [$request_token . "Login"] )) && (strtolower ( $allow_request_token_login ) == "yes") && ($posted_hash == "" || $posted_hash !== $_SESSION [$request_token . "Login"])) {
		$error_request = isset($invalid_request_lang)? $invalid_request_lang : "Invalid Request, please refresh the page and try again!";
		return $error_request;
	} elseif ($obj_captcha != false && isset ( $_SESSION ["tmpCaptcha_srm7"] ) && ! $obj_captcha->check_code ( trim ( $posted_captcha_word ) )) {
		$error_captcha = isset($invalid_code_lang) ? $invalid_code_lang : "Captcha is not correct!";
		return $error_captcha;
	} else {
		return "secure";
	}
}

/*
 * authenticate_login_info
 *
 * This is the Authentication function, that checks a certan login info after cleaning it and see if it fits a valid profile. could be used for a report and a dashboard
 *
 * @param (cleaned_username) the username to be validated, it should be cleaned before passwing it to this function
 * @param (cleaned_passwor) the password (without hashing) to be validated, it should be cleaned before passwing it to this function
 * @param (allowed_roles) An array of the roles that has the right to access this report / dashboard
 *
 *
 * @return a boolean according to the validation result .
 */
function authenticate_login_info($cleaned_username, $cleaned_password, $allowed_roles) {
	global $DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME,$admin_username, $admin_password, $sec_Username, $sec_pass, $db_extension, $sec_table, $sec_Username_Field, $sec_pass_Field, $sec_pass_hash_type, $ip, $fixed_ip_address, $admin_ip;
	if (is_array ( $allowed_roles ) && ! empty ( $allowed_roles )) {
		foreach ( $allowed_roles as $role ) {
			if (strtolower ( $role ) === "admin") {
				// validate admin profile
				if (isset($fixed_ip_address) && strtolower($fixed_ip_address) == "yes" )
					$admin = new Admin ( $admin_username, $admin_password, true, $admin_ip );
				else
					$admin = new Admin ( $admin_username, $admin_password,false,"" );
				if ($admin->authenticate ( $cleaned_username, $cleaned_password ) === true) {
					return "admin";
				}
			} elseif (strtolower ( $role ) === "user") {
				$user = new User ( $sec_Username, $sec_pass );
				if ($user->authenticate ( $cleaned_username, $cleaned_password ) === true) {
					return "user";
				}
			} elseif (strtolower ( $role ) === "dbmember") {
				// connection object
				$dbHandler = new DatabaseHandler ( $DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, false, strtolower ( $db_extension ) );
				if ($dbHandler && ! $dbHandler->is_connection_failed ()) {
					$DbMember = new DbMember ( $sec_table, $sec_Username_Field, $sec_pass_Field, $sec_pass_hash_type, $dbHandler );
					if ($DbMember->authenticate ( $cleaned_username, $cleaned_password ) === true) {
						return "dbmember";
					}
				}
			}
		}
		// not a valid user account
		return false;
	} else {
		// not a valid roles to validate
		return false;
	}
}

/*
 * get_allowed_roles
 *
 * check the configuration of a report and see which roles are allowed to access it
 *
 * @return array of allowed roles that can access a certain report.
 */
function get_allowed_roles() {
	global $allow_only_admin, $admin_accessFrom_userLogin, $sec_Username, $sec_pass, $security, $sec_Username_Field, $sec_pass_Field;
	// case only admin is allowed
	if (strtolower ( $allow_only_admin ) === "yes") {
		if (strtolower ( $admin_accessFrom_userLogin ) === "yes")
			return array (
					"admin" 
			);
		else
			return array ();
	} else {
		// case Not restricted to only admin
		$arr = array ();
		if (strtolower ( $admin_accessFrom_userLogin ) === "yes") {
			$arr [] = "admin";
		}
		
		// case user
		if ($sec_Username !== "" || $security !== "" || $sec_pass !== "") {
			$arr [] = "user";
		}
		
		// case Dbmember
		if ($sec_Username_Field !== "" || $sec_pass_Field !== "") {
			$arr [] = "dbmember";
		}
		
		return $arr;
	}
}

/*
 * find_email
 *
 * check if the submitted email address belongs to the admin, user , dbmember or not reconnized
 *
 * @return associative array two elements "role" => the role who ownes the email (admin, user or dbmember) and "username" of the user that own the email . if the email is not found it returns false
 */
function find_email($posted_email) {
	global $admin_email, $admin_username, $sec_Username, $sec_email;
	$profile = array ();
	$member_user = find_dbmember_email ( $posted_email );
	if ($posted_email === $admin_email) {
		$profile ["role"] = "admin";
		$profile ["username"] = $admin_username;
		return $profile;
	} elseif ($posted_email === $sec_email) {
		$profile ["role"] = "user";
		$profile ["username"] = $sec_Username;
		return $profile;
	} elseif ($member_user !== false) {
		$profile ["role"] = "dbmember";
		$profile ["username"] = $member_user;
		return $profile;
	} else {
		// email is not found
		return false;
	}
}

/*
 * find_dbmember_email
 *
 * check if the submitted email address belongs to an existing dbmember
 *
 * @return the username of the dbmember that own the email . if the email is not found it should returns false
 */
function find_dbmember_email($posted_email) {
	global $DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME,$db_extension, $sec_table, $sec_email_field, $sec_Username_Field;
	$dbHandler = new DatabaseHandler ( $DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, false, strtolower ( $db_extension ) );
	$params = array (
			$dbHandler->sanitize_values ( trim ( $posted_email ) ) 
	);
	$sql = "select`" . $sec_Username_Field . "` from `" . $sec_table . "` where `" . $sec_email_field . "`=? ";
	$results = $dbHandler->query ( $sql, 'ASSOC', $params, 's' );
	
	if (! $results)
		return false; // invalid sql query
	if ($dbHandler->get_num_rows () == 1) {
		// get the username
		$username = $results [0] ["username"];
		$dbHandler->close_connection ();
		return $username;
	} else {
		// member dosn't exist
		$dbHandler->close_connection ();
		return false;
	}
}
function send_instructions($role, $posted_email, $username) {
	global $admin_email, $admin_reset_message, $user_reset_message, $autoresponder_message, $dbmember_reset_message;
	if (strtolower ( $role ) === "admin") {
		
		if (@mail ( $admin_email, "Reset smart report maker admin password", $admin_reset_message )) {
			return true;
		} else {
			return false;
		}
	} elseif (strtolower ( $role ) === "user") {
		
		if (@mail ( $admin_email, "Reset a smart report maker user password", $user_reset_message )) {
			@mail ( $posted_email, "Reset password instructions are sent", $autoresponder_message );
			return true;
		} else {
			return false;
		}
	} elseif ((strtolower ( $role ) === "dbmember")) {
		$message = str_replace ( "{{member_email}}", $posted_email, $dbmember_reset_message );
		$message = str_replace ( "{{member_user}}", $username, $message );
		$message = str_replace ( "{{database_name}}", DB_NAME, $message );
		
		if (@mail ( $admin_email, "Reset a smart report maker member password", $message )) {
			@mail ( $posted_email, "Reset password instructions are sent", $autoresponder_message );
			return true;
		} else {
			return false;
		}
	}
}
