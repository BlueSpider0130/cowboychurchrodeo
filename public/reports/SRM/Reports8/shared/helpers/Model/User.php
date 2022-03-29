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
class User extends Member {
	private $user_saved_username;
	private $user_saved_password;
	public function __construct($user_saved_username, $user_saved_password) {
		$this->user_saved_username = $user_saved_username;
		$this->user_saved_password = $user_saved_password;
	}
	public function authenticate($submitted_user, $submitted_password) {
		$hashed_password = $this->hashpassword ( $submitted_password );
		
		if ($submitted_user === $this->user_saved_username && $hashed_password === $this->user_saved_password) {
			
			return true;
		} else {
			
			return false;
		}
	}
}
