<?php

/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");
$log = "";

debug("Original request");
log_array($_POST);
log_array($_GET);

//require_once ("captcha.php");
$captcha_key = "tmpCaptcha_srm7";
$obj_captcha = false;
/* $obj_captcha = new captcha ( $captcha_key );
  $resut = $obj_captcha->test_captcha ();
  if (! $resut) {
  // captcha is prevented due to technical error
  logging ( " captcha is turned off because:" . $obj_captcha->get_error ()  );
  $obj_captcha = false;
  } else {
  logging ( " captcha is enabled and can be run with no problems. " );
  } */


debug("After intial sanitization");
log_array($_POST);
log_array($_GET);



/*
 * #################################################################################################
 * Logging functions
 * ################################################################################################
 */

/*
 * logging
 *
 * Add a message to the $log variable
 *
 * @param (str) the message to be logged
 */

function logging($str, $type = "") {
    global $log;
    $log .= $str . PHP_EOL;
}

/*
 * logging
 *
 * Add an array to the $log variable
 *
 * @param (arr) the array to be logged
 */

function log_array($arr) {
    if (count($arr) > 0) {
        foreach ($arr as $key => $val) {
            if (!is_array($val)) {
                logging("\n   $key   : $val");
            } else {
                logging("\n   $key is an array: \n ");
                log_array($val);
            }
        }
    }
}

/*
 * #################################################################################################
 * Out put escaping .
 * ################################################################################################
 */
/*
 * escape
 *
 * output escaping of an output.
 *
 * @param (val) the output to be escaped .
 */

function escape($val) {
    $val = str_ireplace("<script>", "", $val);
    if ($val === "&nbsp;" || $val === "&nbsp")
        return $val;
    else
        return htmlentities(strip_tags($val), ENT_QUOTES, "UTF-8");
}

/*
 * #################################################################################################
 * Filtration functions .
 * ################################################################################################
 */
require_once ("Filters.php");

/*
 * #################################################################################################
 * Sanitization functions
 * ################################################################################################
 */

require_once ("Cleaners.php");

/*
 * #################################################################################################
 * SuperGlobal Security functions
 * ################################################################################################
 */
/*
 * remove_unexpected_superglobals
 *
 * Remove the "the unexpected" elements from a superglobal array AND santitized the expected elements according to sanitization types
 *
 * @param (index) the index of the element to be retrieved
 * @param (data_type) set to "int" , "float" , "no_specials" , "email", "string" and "array"
 * @param (global_array) The array to get the element from
 *
 * @return the sanitized variable or false if the index is not set .
 */

function remove_unexpected_superglobals($superGlobal, $allowedKeys) {

    // this function removes any Unexpected keys from super globals
    $integer_keys = array(
        "DebugMode7",
        "start",
        "print",
        'detail',
        "SearchField",
        'cp'
    );
    $email_keys = array(
        "from",
        "to"
    );
    $boolean_keys = array(
        "btnSearch",
        "btnordnarySearch",
        "btnShowAll",
        "btnShowAll2",
        "loginBtn",
        "save",
        "submit"
    );
    $login_keys = array(
        "name",
        "pass"
    );
    $no_specials = array();
    $float_keys = array();
    $arr = array();

    foreach ($superGlobal as $key => $val) {
        if (in_array($key, $allowedKeys) || $key = "RequestToken") {
            // Allowed key
            if (in_array($key, $integer_keys)) {
                // clean int keys
                $arr [$key] = (int) get($key, "int", $superGlobal);
            } elseif (in_array($key, $email_keys)) {
                // clean email keys
                $arr [$key] = get($key, "email", $superGlobal);
            } elseif (in_array($key, $no_specials)) {
                $arr [$key] = get($key, "no_specials", $superGlobal);
            } elseif (in_array($key, $boolean_keys)) {
                $arr [$key] = get($key, "boolean", $superGlobal);
            } elseif (in_array($key, $float_keys)) {
                $arr [$key] = (float) get($key, "float", $superGlobal);
            } elseif (in_array($key, $login_keys)) {

                $arr [$key] = get($key, "login_info", $superGlobal);
            } else {
                // clean strings
                $arr [$key] = get($key, "string", $superGlobal);
            }
        } else {

            // Not allowed super global .bad request .
            unset($superGlobal [$key]);
        }
    }

    return $arr;
}

/*
 * get
 *
 * Getting an element from a super global array after sanitizing it according to its data type
 *
 * @param (index) the index of the element to be retrieved
 * @param (data_type) set to "int" , "float" , "no_specials" ,"boolean", "email", "string","lockup", and "array"
 * @param (global_array) The array to get the element from
 * @param(options) and (default) can be used only with the lockup cleaner
 *
 * @return the sanitized variable or false if the index is not set .
 */

function get($index, $data_type = "string", $global_array, $options = array(), $default = "") {


    if (isset($global_array [$index])) {
        $get = $global_array [$index];
    } else {
        return "";
    }

    if ($get) {

        if ($data_type == "int")
            $get = (int) clean_number($get, "int");
        elseif ($data_type == "float")
            $get = (float) clean_number($get, "float");
        elseif ($data_type == "email")
            $get = clean_email($get);
        elseif ($data_type == "no_specials")
            $get = clean_input($get, true);
        elseif ($data_type == "boolean")
            $get = clean_boolean($get);
        elseif ($data_type == "lockup")
            $get = clean_lockup($get, $options, $default);
        elseif (is_array($get))
            $get = clean_array($get);
        elseif ($data_type == "login_info") {

            $get = $get;
        } else
            $get = clean_input($get);
    }

    return $get;
}

/*
 * clean_input_array
 *
 * Sanatize a super global array used in the wizard
 *
 * @param (arr) the super global array to be Sanitized
 *
 * @return the sanitized array .
 */

function clean_input_array($arr) {

    $clean = array();
    foreach ($arr as $k => $v) {
        if (is_array($v))
            $clean[clean_input($k)] = clean_input_array($v);
        else
            $clean[clean_input($k)] = clean_input($v, false, false, true, array("`", "=", ".", "-", "_"));
    }
    return $clean;
}

/*
 * #################################################################################################
 * Encoding and Encryption functions
 * ################################################################################################
 */

/*
 * decode
 *
 * decoding the encoded variable in the config file
 *
 * @param (encoded) the encoded variable.
 */

function decode($encoded) {
    return base64_decode($encoded);
}

/*
 * #################################################################################################
 * Debuging functions & Sending Log by email while trouble shooting .
 * ################################################################################################
 */

/*
 * send_log_info
 *
 * Send $log (contains all logs) variable to the maintanance email address existed in the config file, if the debug URL is provided and a valid maintanace email exists
 * a validation process is done in the function to make sure the maintatnce email and debug URL both are valid before sending .
 * @param ($maintainance_email) the maintanance email.
 */

function send_log_info($maintainance_email) {
    global $log, $_CLEANED, $maintainance_email;

    if (check_debug_mode() === 1) {

        $message = "Hello, " . PHP_EOL;
        $message .= "This message is sent automatically from your own server (based on your request) for troubleshooting a problem in a report generated by a full version of smart report maker installed on your own server. \n \n";
        $message .= "The following is a log of all processes done for generating the report, please send this log via our support system to help our team   understanding the problem(s) correctly .\n \n";
        $message .= PHP_EOL . " ****** The start  of the log  *****" . PHP_EOL . $log . PHP_EOL . "\n *** The End of the log ****  " . PHP_EOL;
        $message .= PHP_EOL . " Please not that : " . PHP_EOL . " In order to stop receiving the same message again please open the config file of the generated report and remove this email address  from the maintainance_email by making it like the following : " . PHP_EOL;
        $message .= PHP_EOL . 'maintainance_email = ""; ';

        @mail($maintainance_email, "Smart Report Engine Troubleshooting", $message);
    }
}

function debug($str, $flush = false) {
    global $maintainance_email, $debug_message;
    if (check_debug_mode() === 1) {

        logging($str);
    } else {

        return false;
    }
}

function check_debug_mode() {


    global $maintainance_email, $url_param, $maintainance_mode;

    
    if ((isset($url_param) && $url_param === 1701) || ($maintainance_mode === "Yes")) {
        if (isset($maintainance_email) && (filter_var($maintainance_email, FILTER_VALIDATE_EMAIL))) {

            return 1;
        } else {
            return false;
        }
    } else {

        return false;
    }
}

?>