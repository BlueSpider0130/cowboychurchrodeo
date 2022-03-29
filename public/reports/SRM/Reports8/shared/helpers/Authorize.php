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
 * #################################################################################################
 * User Access Control
 * ################################################################################################
 */
get_profile ();
function get_profile() {
	global $admin_login_key, $user_login_key,$security,$members, $allow_only_admin, $sec_Username, $sec_pass, $sec_Username_Field, $sec_pass_Field ;
	
	
		if (isset ( $_SESSION [$admin_login_key] ) && is_array ( $_SESSION [$admin_login_key] ) && validate_admin_profile ( $_SESSION [$admin_login_key] ) === true) {
			return "admin";
		} elseif (isset ( $_SESSION [$user_login_key] ) && is_array ( $_SESSION [$user_login_key] ) && validate_user_profile ( $_SESSION [$user_login_key] ) === true) {
			return "user";
		} elseif (isset ( $_SESSION [$user_login_key] ) && is_array ( $_SESSION [$user_login_key] ) && validate_dbmember_profile ( $_SESSION [$user_login_key] ) === true) {
			return "dbmember";
		} elseif ($security == "" && $members == "" && (isset ( $allow_only_admin ) && $allow_only_admin != "yes") &&  $sec_pass == "" ) {
                        return "public";
                }else {
			//Un authorized
			session_end ();
			header('HTTP/1.0 403 Forbidden');
			header ( "location: login.php" );
			exit ();
		}
	
	
}
function validate_admin_profile($arr) {
	global $admin_username;
	if (isset ( $arr ["role"] ) && strtolower ( $arr ["role"] ) === "admin" && isset ( $arr ["username"] ) && $arr ["username"] === $admin_username && isset ( $arr ["user_agent"] ) && $arr ["user_agent"] === $_SERVER ["HTTP_USER_AGENT"] && isset ( $arr ["ip"] ) && $arr ["ip"] === $_SERVER ["REMOTE_ADDR"]) {
		return true;
	} else {
		return false;
	}
}
function validate_user_profile($arr) {
	global $sec_Username;
	if (isset ( $arr ["role"] ) && strtolower ( $arr ["role"] ) === "user" && isset ( $arr ["username"] ) && $arr ["username"] === $sec_Username && isset ( $arr ["user_agent"] ) && $arr ["user_agent"] === $_SERVER ["HTTP_USER_AGENT"] && isset ( $arr ["ip"] ) && $arr ["ip"] === $_SERVER ["REMOTE_ADDR"]) {
		return true;
	} else {
		return false;
	}
}
function validate_dbmember_profile($arr) {
	if (isset ( $arr ["role"] ) && strtolower ( $arr ["role"] ) === "dbmember" && isset ( $arr ["username"] ) && $arr ["username"] !== "" && isset ( $arr ["user_agent"] ) && $arr ["user_agent"] === $_SERVER ["HTTP_USER_AGENT"] && isset ( $arr ["ip"] ) && $arr ["ip"] === $_SERVER ["REMOTE_ADDR"]) {
		return true;
	} else {
		return false;
	}
}
