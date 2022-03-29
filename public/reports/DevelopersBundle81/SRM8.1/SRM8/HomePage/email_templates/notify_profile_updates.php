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
$request_time = date('Y/m/d H:i:s');
$host = $_SERVER ['HTTP_HOST'];
$uri = rtrim ( dirname ( $_SERVER ['PHP_SELF'] ), '/\\' );
$http = isset ( $_SERVER ['HTTPS'] ) ? 'https://' : 'http://';
$extra = "index.php";
$homepage_exact_url = $http . $host . $uri . "/" . $extra;
$Ip = $_SERVER["REMOTE_ADDR"];
$update_profile_message = "Hello," . PHP_EOL;
$update_profile_message .= "***This is an automatic confirmation message sent to confirm that your smart report maker admin profile was  successfully updated ***" . PHP_EOL ;
$update_profile_message .= "Here is the log of the profile updating request : " .PHP_EOL;
$update_profile_message .= " - Update Request Time :  $request_time".PHP_EOL;
$update_profile_message .= " - The request was done through the 'Update profile' page of smart report maker which is installed in $homepage_exact_url :" . PHP_EOL;
$update_profile_message .= " - The updating request was recieved from the following IP address : $Ip" . PHP_EOL;
