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
class Member{
	
	
	
	public function hashpassword($password){
		$salt = sha1(md5($password));
		return hash("sha256", $password . $salt);
	}
	

	
	
	
	public function __construct(){
		
	}
}
