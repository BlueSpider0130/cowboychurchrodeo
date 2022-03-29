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
class Admin extends Member {
	private $admin_saved_username;
	private $admin_saved_password;
	private $is_fixed_ip;
	private $admin_fixed_ip;
	public function __construct($admin_saved_username, $admin_saved_password, $is_fixed_ip = false, $ip = "") {
		if($admin_saved_username =="" || $admin_saved_password == ""){
			die("Sorry, Can't currently access the system !");
		}
		$this->admin_saved_username = $admin_saved_username;
		$this->admin_saved_password = $admin_saved_password;
		$this->is_fixed_ip = $is_fixed_ip;
		if (filter_var ( $ip, FILTER_VALIDATE_IP ))
			$this->admin_fixed_ip = $ip;
	}
	public function authenticate($submitted_user, $submitted_password) {
		$hashed_password = $this->hashpassword ( $submitted_password );
		if ($submitted_user === $this->admin_saved_username && $hashed_password === $this->admin_saved_password) {
			
			
			if (! $this->is_fixed_ip) {
			
				return true;
			} elseif ($this->is_fixed_ip && $this->get_ip () === $this->admin_fixed_ip) {
				
				return true;
			} else {
				
				return false;
			}
		} else {
			return false;
		}
	}
	private function get_ip() {
		$remoteaddr = $_SERVER ["REMOTE_ADDR"];
		$xforward = isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] ) ? $_SERVER ["HTTP_X_FORWARDED_FOR"] : "";
		if (empty ( $xforward )) {
			// user is NOT using proxy
			return $remoteaddr;
		} else {
			// user is using proxy
			return $_SERVER ["HTTP_X_FORWARDED_FOR"];
		}
	}
}








