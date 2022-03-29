<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
/*
 * #################################################################################################
 * DB managment and other helper functions
 * ################################################################################################
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");
/*
 * is_connected
 *
 * checking weather or not a connection to the database is established in a previous step
 * *
 * @return true if connected and false otherwise
 */

function is_connected() {
    if (isset($_SESSION ['srm_f62014_validate_key']) && $_SESSION ['srm_f62014_validate_key'] == md5("srm_f62014_valid_1010") && isset($_SESSION ['srm_f62014_host']) && !empty($_SESSION ['srm_f62014_host']) && isset($_SESSION ['srm_f62014_user']) && !empty($_SESSION ['srm_f62014_user']) && isset($_SESSION ['srm_f62014_db']) && !empty($_SESSION ['srm_f62014_db']) && isset($_SESSION ['srm_f62014_pass'])) {
        return true;
    } else {
        return false;
    }
}

/*
 * is_valid_language
 *
 * checking weather an input string is a valid language
 * *
 * @returntrue if it is and false otherwise .
 */

function is_valid_language($str) {
    global $languages_array;
    if ($str == "no")
        return false;
    foreach ($languages_array as $lang) {
        foreach ($lang as $key => $val) {
            if ($key == "name" && $str == $val) {
                return true;
            }
        }
    }
    return false;
}

/*
 * template_Exist
 *
 * checking weather a string is a valid stored template title. it also checked if a template name is already in use
 *
 * @return true if it is and false otherwise .
 */

function template_Exist($template) {
    require_once ("templates.php");
    $all_templates = @get_templates($_SESSION ['srm_f62014_user'], $_SESSION ['srm_f62014_db']);
    if (is_array($all_templates) && count($all_templates) > 0) {
        foreach ($all_templates as $arr) {
            if (isset($arr ["title"]) && $arr ["title"] == $template) {
                return true;
            }
        }
    }
    return false;
}

/*
 * make_valid
 *
 * removing un-nescessary elements from a string to make it a valid sql statment
 *
 * @return the sql statment after processing .
 */

function make_valid($sql) {
    if (get_magic_quotes_gpc())
        $sql = stripslashes($sql);
    $sql = str_replace('"', "'", $sql);
    $sql = str_replace("\r\n", " ", $sql);
    $sql = str_replace("\n", " ", $sql);
    $sql = str_replace(";", "", $sql);
    return $sql;
}

function send_error_response($err_msg) {
    $response ["result"] = "error";
    $response ["errorMessage"] = $err_msg;
    $response = json_encode($response);
    echo $response;
    return;
}

function disconnect() {
   
    $keep = array(
        "admin_access_SRM7",
        "timeout_srm7",
        "request_token_wizard"
    );

    foreach ($_SESSION as $key => $value) {
        if (!in_array($key, $keep)) {

            unset($_SESSION [$key]);
        }
    }
    if (isset($_SESSION["admin_access_SRM7"])) {
        $_SESSION ["srm_f62014_page_key"] = "step_2";
        $_SESSION ["srm_f62014_active_pages"] = array(
            "step_2"
        );
    }

    return true;
}

// check if string empty or equal to not expected value
function CheckVar($str) {
    if (isset($str)) {
        if (empty($str) || $str === "NoValue" || $str === "Please select a value")
            return false;
        else
            return true;
    } else
        return false;
}

// return enabled if checkbox checked
function adjust($string) {
    if (isset($string)) {
        if (empty($string))
            return "";
        else if ($string === "1" || $string === "checked" || $string === "on")
            return "enabled";
        else
            return "";
    } else
        return "";
}

//check if user security is sent in an ajax request in the security step
function is_security_details_sent() {
    if (isset($_POST ["security"]) ||
            (isset($_POST ["sec_pass"]) && $_POST ["sec_pass"] != "")
    ) {

        return true;
    } else {
        //No values was sent at all . 
        return false;
    }
}

//check if members details is sent in an ajax request in the security step
function is_members_details_sent() {
    if (isset($_POST ["members"]) ||
            (isset($_POST ["sec_pass_Field"]) && $_POST ["sec_pass_Field"] != "")
    ) {
        return true;
    } else {
        //No values was sent at all . 
        return false;
    }
}

//check if another account (user or dbmember) beside the admin is created for security step
function is_another_account_created() {
    $is_user_account = (isset($_POST ["security"]) && adjust($_POST ["security"]) == "enabled") ? true : false;
    $is_members = (isset($_POST ["members"]) && adjust($_POST ["members"]) == "enabled") ? true : false;

    if ($is_user_account || $is_members) {
        return true;
    } else {
        return false;
    }
}

?>