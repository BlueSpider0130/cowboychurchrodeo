<?php

use Sre\SmartReportingEngine\src\Engine\Constants;
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */

/*
 * #################################################################################################
 * User Access Control
 * ################################################################################################
 */

$session_validator = new SessionValidation($access_mode,$redirect_login_page,$session_validation_login_keys);
$session_validator->validate();