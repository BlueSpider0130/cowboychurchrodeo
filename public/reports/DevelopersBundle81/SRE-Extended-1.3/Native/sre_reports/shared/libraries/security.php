<?php
/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreports.com/
 *
 */
if (! defined ( "DIRECTACESS" ))
	exit ( "No direct script access allowed" );

/*
 * #################################################################################################
 * User Access Control
 * ################################################################################################
 */

$session_validator = new SessionValidation($access_mode,$redirect_login_page,$session_validation_login_keys);
$session_validator->validate();