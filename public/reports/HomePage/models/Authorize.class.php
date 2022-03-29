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
class Authorize {
	private $profile;
	public function __construct($objProfile) {
		
		$this->profile = $objProfile;
	}
	public function validate($validated_login_key) {
		$arr = $validated_login_key;
		
		
		if (isset ( $arr ["role"] ) && strtolower ( $arr ["role"] ) === "admin" && isset ( $arr ["username"] ) && $arr ["username"] === $this->profile->get_username () && isset ( $arr ["user_agent"] ) && $arr ["user_agent"] === $_SERVER ["HTTP_USER_AGENT"] && isset ( $arr ["ip"] ) && $arr ["ip"] === $_SERVER ["REMOTE_ADDR"]) {
			return 1;
		} else {
			return false;
		}
	}
}