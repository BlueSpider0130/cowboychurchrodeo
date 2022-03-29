<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");
/*
 * #################################################################################################
 * Sessions and Request Settings
 * ################################################################################################
 */
$custom_logo="";
$show_mobile_layout = "no";
$limited_time_session = "no"; // session has an expiration time .
$session_timeout = 7200; // The maximum time a report is left idle in seconds
$proxy_detect = "no";
$pdf_export = 1; //1 TCPDF   2 ezpdf   3 DOMPDF
/*
 * #################################################################################################
 * Report Tool bar settings
 * ################################################################################################
 */
$allow_print_view = "yes"; // show print icon in the menu of the report
$allow_export = "yes"; // show export icon in the menu of the report
$allow_change_style = "yes"; // show change style icon in the menu of the report
$allow_change_layout = "yes"; // show change layout icon in the menu of the report
$allow_email = "yes"; // show send email icon on the menu of the report
$chkSearch = 'yes'; // show the search box in the report
$allow_delete_filter = "yes"; //delete filter to show popup again 
$allow_request_token_login = "yes"; // validate a token when login
$automatic_mobile_view = "yes"; //if any layout is loaded from a mobile screen it should be the mobile layout . 
$prevent_overwrite_existing_tables = "yes"; //if turn to no will overwrite existing reports when creating a new report with the same name
$languages_array = array(
    "Arabic" => "ar",
    "English" => "en",
    "French" => "fr",
    "German" => "de",
    "spanish" => "es"
);

/*
 * #################################################################################################
 * Validating login info
 * ################################################################################################
 */
$maximum_password_length = 16; // new and existed passwords
$minimum_password_length = 8; // new and exited passwords
$max_length_username_new = 20; // Any new username created by smart report maker (admin or user)
$min_length_username_new = 3; // Any new username created by smart report maker (admin or user)
$max_length_username_existed = 25; // already saved usernames in the user's DB of members
$min_length_username_existed = 3; // already saved usernames in the user's DB of members

$existed_username_allowed_specials = array(
    "@",
    "_",
    "-",
    ".",
    "&",
    "%",
    "$",
    "#",
    "!",
    "=",
    "+",
    "/",
    "[",
    "]"
); // accepted special characters in existed usernames
// overright any report configuration in this area

/*
 * #################################################################################################
 * Reset password emails
 * ################################################################################################
 */
$admin_security_questions = array(
    "What is the middle name of your mother?",
    "What is the name of your pet?",
    "In what year was your father born?",
    "What was the name of your high school?",
    "What is the name of your favorite childhood friend?",
    "What was your childhood nickname?"
);
/*
 * #################################################################################################
 * wizard and dashboard settings
 * ################################################################################################
 */
$help_file = "../help.pdf";
