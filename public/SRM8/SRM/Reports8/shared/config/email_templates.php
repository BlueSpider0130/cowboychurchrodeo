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
$encryption_url = str_replace($file_name."/","shared/config/enc.php",$report_exact_directory);
$request_time = date('Y/m/d H:i:s');
$path_to_admin_file = "/SRM/Reports8/shared/config/admin.php";
$admin_reset_message = "Hello," . PHP_EOL;
$admin_reset_message .= "***This is an automatic message sent according to your request  to reset the 'Admin' password ***" . PHP_EOL . "The request was sent at $request_time" . PHP_EOL;
$admin_reset_message .= "**Please note that the same password has two forms" .PHP_EOL ." 1- The plain / unencrypted form :  is the one that the admin should use when login".PHP_EOL."2- The encrypted form  :  is only used to  save the password in the the system files in a secure way." .PHP_EOL;
$admin_reset_message .= PHP_EOL."The following steps are for resetting the admin password:" . PHP_EOL;
$admin_reset_message .= "1) Please open the following link : $encryption_url " . PHP_EOL;
$admin_reset_message .= "2) You should be asked to enter both your regestered admin email address, and an answer to your saved security question, then you will be able  to enter the new password . The page will then print  its encrypted value, please copy this value, it will be used later." . PHP_EOL;

$admin_reset_message .= "3) Connect to  your published version of smart report maker via FTP and download only the following PHP file : $path_to_admin_file " . PHP_EOL;
$admin_reset_message .= "4) Open the downloaded php file , using any text editor like notpad or notepad++." . PHP_EOL;
$admin_reset_message .= '5) In the openned file, please search for  $admin_password , you should find it assigned to an encrypted value ( a long list of numbers and letters) like this :  $admin_password = "c89eefe899b65fef69bdd7291b06e35ea80"; '. PHP_EOL. "Please replace the existed encrypted value with the one which you copied in step 2, like the following".PHP_EOL. '$admin_password = "The new encrypted value you got from step 2 goes here";  ' .PHP_EOL; 
$admin_reset_message .= "6) Save the modified file then upload it  back to your site in the exact same location (i.e  $path_to_admin_file) " . PHP_EOL;
$admin_reset_message .= "Finally, Test the new password (with its plain form ) on the login screen. If it doesn't work, check that you've followed these instructions exactly." .PHP_EOL;

//need the $sec_username and the report url
$user_reset_message = "Hello," . PHP_EOL;
$user_reset_message .= "***This is an automatic message sent according to a request from the user : $sec_Username  to reset the saved password of the following report: $report_exact_url " . PHP_EOL. "The request was sent at $request_time" . PHP_EOL;
$user_reset_message .= "**Please note that the same password has two forms" .PHP_EOL ." -The plain / unencrypted form :  is the one that the user should use when login to the report.".PHP_EOL."-The encrypted form:  is only used to be save the password in the the system files securly." .PHP_EOL;
$user_reset_message .= "Assuming that only the user $sec_Username forgot his password, and the admin login credentials are NOT forgotten (if that's not the case please get back to the login page, hit forgot password then please provide the admin email address instead)";
$user_reset_message .= " Please do the following steps to reset this user's password:" . PHP_EOL;
$user_reset_message .= "1) Login to smart report maker as an 'admin'.  " . PHP_EOL;
$user_reset_message .= "2) After login please open the following link  : $encryption_url, then you will be able  to enter the new password which you want to use for the requested user. The page will then print  its encrypted value, please copy this value, it will be used later." . PHP_EOL;

$user_reset_message .= "3) Connect to your published version of smart report maker via FTP and download only the 'config.php' which should be found at ".str_replace("http","ftp",$report_exact_directory).  PHP_EOL;
$user_reset_message .= "4) Open the downloaded php file , using any text editor like notpad or notepad++." . PHP_EOL;
$user_reset_message .= '5) In the openned file, please search for  $sec_pass , you should find it assigned to an encrypted value like this :  $sec_pass = "5fef69bdd7291b06e35ea80"; '. PHP_EOL. "Please replace the existed encrypted value with the one you copied in step 2, like the following".PHP_EOL. '$sec_pass = "The new encrypted value you got from step 2 goes here";  ' .PHP_EOL;
$user_reset_message .= "6) Save the modified file then upload it  back to your site in the exact same location (i.e  ".str_replace("http","ftp",$report_exact_directory).") " . PHP_EOL;
$user_reset_message .= "7) User should now be able to login to the report at $report_exact_url using the username and the new password  (with its plain form of course) ". PHP_EOL;

//tokens needed in this message  : {{database_name}}, {{member_email}} and {{member_user}}
$dbmember_reset_message = "Hello," . PHP_EOL;
$dbmember_reset_message.= "***This is an automatic message sent according to a request from  a member saved on your table $sec_table  to reset his saved password in order to access the report : $report_exact_url  ***" . PHP_EOL. "The request was sent at $request_time" . PHP_EOL;
$dbmember_reset_message .= "Assuming that only the user: {{member_user}} whose regestered email: {{member_email}}, forgot his password, and the admin login credentials are not forgotten, (if that's not the case please get back to the login page, hit forgot password then please provide the admin email address instead)";
$dbmember_reset_message .= " Please do the following steps  for resetting this user's password:" . PHP_EOL;
$dbmember_reset_message .= "1) Please login to phpmyadmin in your hosting control panel .". PHP_EOL ." ***Note: use phpMyAdmin at your own risk. If you doubt your ability to use it safely, please seek further advice from your hosting provider ".PHP_EOL ;
$dbmember_reset_message .= "2) A list of databases will appear. Click your users database." . PHP_EOL;
$dbmember_reset_message .= "3) All the tables in your database will appear. If not, click Structure. " . PHP_EOL;
$dbmember_reset_message.= "4) Look for the $sec_table in the Table column" . PHP_EOL;
$dbmember_reset_message .= "5) Locate the username {{member_user}} under $sec_Username_Field.".PHP_EOL;
$dbmember_reset_message .= "6) Click edit (may look like a pencil icon in some versions of phpMyAdmin) " . PHP_EOL;
$dbmember_reset_message .= "7) The password of the user should appear under the column $sec_pass_Field, and it should be in an encrypted form ". PHP_EOL;

$dbmember_reset_message .= "8) Delete the existed password for this user and write a new one under the $sec_pass_Field column. make sure to encrypt the password before saving it using $sec_pass_hash_type ". PHP_EOL;
$dbmember_reset_message .= "9) Click the 'Go' button to the bottom right" .PHP_EOL;
$dbmember_reset_message .= "Finally, Test the new password on the login screen. If it doesn't work, check that you've followed these instructions exactly." .PHP_EOL;

$autoresponder_message = "Hello," . PHP_EOL;
$autoresponder_message .="***This is an automatic message sent according to a request from  you  to reset your smart report maker password, so if you didn't make this request please contact the admin  ***" . PHP_EOL. "The request was sent at $request_time" . PHP_EOL;
$autoresponder_message .= "We sent the password reset steps to the admin. " .PHP_EOL;
