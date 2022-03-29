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
	$encryption_url = str_replace("HomePage","SRM/Reports8/shared/config/enc.php",$homepage_exact_directory);
        $encryption_url = str_replace("enc.php/","enc.php",$encryption_url);
	$request_time = date('Y/m/d H:i:s');
	$path_to_admin_file = "/SRM/Reports8/shared/config/admin.php";
	$admin_reset_message = "Hello," . PHP_EOL;
	$admin_reset_message .= "***This is an automatic message sent according to your request  to reset the 'Admin' password ***" . PHP_EOL . "The request was sent at $request_time" . PHP_EOL;
        $admin_reset_message .= "**Please note that your password has two forms" .PHP_EOL ." - The plain / unencrypted form :  is the one that the admin should use when login".PHP_EOL."- The encrypted form:  is only used to  save your password in the the system files securely." .PHP_EOL;
	$admin_reset_message .= PHP_EOL."The following steps are for resetting the admin password:" . PHP_EOL;
	$admin_reset_message .= "1) Please open the following link : $encryption_url " . PHP_EOL;
	$admin_reset_message .= "2) You should be asked to enter both your regestered admin email address, and an answer to your saved security question, then you will be able  to enter the new password . The page will then print  its encrypted value, please copy this value, it will be used later." . PHP_EOL;
	
	$admin_reset_message .= "3) Connect to your published version of smart report maker via FTP and download only the following PHP file : $path_to_admin_file " . PHP_EOL;
	$admin_reset_message .= "4) Open the downloaded php file , using any text editor like notpad or notepad++." . PHP_EOL;
	$admin_reset_message .= '5) In the openned file, please search for  $admin_password , you should find it assigned to an encrypted value ( a long list of numbers and letters) like this :  $admin_password = "c89eefe899b65fef69bdd7291b06e35ea80"; '. PHP_EOL. "Please replace the existed encrypted value with the one which you copied in step 2, like the following".PHP_EOL. '$admin_password = "The new encrypted value you got from step 2 goes here";  ' .PHP_EOL;
	$admin_reset_message .= "6) Save the modified file then upload it  back to your site in the exact same location (i.e  $path_to_admin_file) " . PHP_EOL;
	$admin_reset_message .= "Finally, Test the new password (with its plain form ) on the login screen. If it doesn't work, check that you've followed these instructions exactly." .PHP_EOL;
