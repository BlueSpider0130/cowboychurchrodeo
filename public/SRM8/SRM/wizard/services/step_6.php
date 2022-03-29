<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
// handle super globals
define("DIRECTACESS", 1);
defined('BASEPATH') or define('BASEPATH', 1);
require_once ("../request.php");
$_GET = array();
$_POST = clean_input_array($_POST);
$_ENV = array();
$_FILES = array();
$_COOKIE = array();
require_once 'functions.php';

if (!is_connected() ) {
    echo ( " Must be connected to run this script" );
    exit();
}
$error = false;
// Not authorized access
if (!is_connected() || !isset($_POST ["token"]) || empty($_POST ["token"]) || $_POST ["token"] != $_SESSION ["request_token_wizard"]) {
    echo "Not allowed for security reasons!";
    $error = true;
    exit();
}
/*
 * #################################################################################################
 * 1) Handling style and layout
 * ################################################################################################
 */
$allStyles = array(
    "mobile",
    "blue",
    "default",
    "grey"
);
$allLayouts = array(
    "Mobile",
    "AlignLeft",
    "Horizontal",
    "Stepped",
    "Block",
    "Outline"
);

// set layout, style_name, titles, security options in session
if (isset($_POST ["layout"]) && isset($_POST ["style_name"])) {

    $layout = clean_input($_POST ["layout"]);
    $style = clean_input($_POST ["style_name"]);

    // validating submitted style and layout
    if (!in_array($layout, $allLayouts)) {
        echo "<1stT>- Layout not found";
        $error = true;
        exit();
    } else if (!in_array($style, $allStyles)) {
        echo "<1stT>- Style not found";
        $error = true;
        exit();
    } else if (($layout === "Mobile" && $style !== "mobile") || ($layout !== "Mobile" && $style === "mobile")) {
        echo "<1stT>- Style AND Layout Not Match";
        $error = true;
        exit();
    }
    if ($error === false) {
        $_SESSION ["srm_f62014_layout"] = $layout;
        $_SESSION ["srm_f62014_style_name"] = $style;
    } else {

        // loading defaults
        if (!isset($_SESSION ["srm_f62014_layout"]) || $_SESSION ["srm_f62014_layout"] === "") {
            $_SESSION ["srm_f62014_layout"] = "AlignLeft";
        }
        if (!isset($_SESSION ["srm_f62014_style_name"]) || $_SESSION ["srm_f62014_style_name"] === "") {
            $_SESSION ["srm_f62014_style_name"] = "grey";
        }
    }
} else {
    echo "<1stT>-Style or layout not set";
    $error = true;
    exit();
}

/*
 * #################################################################################################
 * 2) Security
 * ################################################################################################
 */
$access_rules = array(
    "adminOnly",
    "adminAndUsers",
    "public"
);
if (!in_array($_POST ["access"], $access_rules)) {
    echo "invalid access rule!!";
    $error = true;
    exit();
}
$password_hashing = array(
    "none",
    "md5",
    "sha1",
    "sha256"
);
$_SESSION ["srm_f62014_Forget_password"] = "enabled";
if(!isset( $_SESSION ["srm_f62014_is_public_access"]))
 $_SESSION ["srm_f62014_is_public_access"] = "no";

if (isset($_POST ["access"]) && strtolower($_POST ["access"]) === "adminonly") {
     $_SESSION ["srm_f62014_is_public_access"] = "no";
    /*
     * #################################################################################################
     * 2-1) Case admin Only
     * ################################################################################################
     */
    // validate security is disabled and no related value sent
    if (is_security_details_sent() === true) {
        echo "<2stT> Access rule is set to 'admin Only' yet there is specification related 'User Account' in the request";
        $error = true;
        exit();
    } elseif (is_members_details_sent() === true) {
        echo "<2stT> Access rule is set to 'admin Only' yet there is specification related to 'Saved Members' in the request";
        $error = true;
        exit();
    } else {

        $_SESSION ["srm_f62014_allow_only_admin"] = "yes";
        $_SESSION ["srm_f62014_security"] = "";
        $_SESSION ["srm_f62014_members"] = "";
        $_SESSION ["srm_f62014_is_public_access"] = "no";
          $_SESSION ["srm_f62014_security"] = "";
        $_SESSION ["srm_f62014_sec_Username"] = "";
        $_SESSION ["srm_f62014_sec_email"] = "";
        $_SESSION ["srm_f62014_members"] = "";
        $_SESSION ["srm_f62014_sec_table"] = "";
        $_SESSION ["srm_f62014_sec_Username_Field"] = "";
        $_SESSION ["srm_f62014_sec_pass_Field"] = "";
        $_SESSION ["srm_f62014_sec_pass_hash_type"] = "";
        $_SESSION ['srm_f62014_sec_email_field'] = "";
    }
} elseif (isset($_POST ["access"]) && strtolower($_POST ["access"]) === "adminandusers") {
     $_SESSION ["srm_f62014_is_public_access"] = "no";
    /*
     * #################################################################################################
     * 2-2) Case admin and user
     * ################################################################################################
     */
    // either member or security account must be sent .
    require_once ("../../../HomePage/models/Profile.class.php");
    $profile = new Profile($admin_file);
    $admin_user_name = $profile->get_username();
    if (is_another_account_created() == false) {
        echo "<2stT> Access rule is set to " . "'admin and users'" . " yet there is No settings for " . "'User Account'" . " or 'saved members!' .";
        $error = true;
        exit();
    } elseif ((isset($_POST ["security"])) && (adjust($_POST ["security"]) == "enabled") && (isset($_POST ["sec_Username"]) && strtolower(trim($_POST ["sec_Username"])) == strtolower(trim($admin_user_name)))) {
        echo "<2stT>The user name of the 'User Account' can't be the same as the admin username!";
        $error = true;
        exit();
    } elseif ((isset($_POST ["security"])) && (adjust($_POST ["security"]) == "enabled") && (isset($_POST ["sec_email"]) && strtolower(trim($_POST ["sec_email"])) == strtolower(trim($profile->get_email())))) {
        echo "<2stT>The email of the 'User Account' can't be using this email address";
        $error = true;
        exit();
    } elseif ((isset($_POST ["security"])) && (adjust($_POST ["security"]) == "enabled") && (!isset($_POST ["sec_Username"]) || $_POST ["sec_Username"] == "" || !check_username_formats($_POST ["sec_Username"], $max_length_username_new, $min_length_username_new))) {
        echo "<2stT>The user name of the 'User Account' is empty or invalid! A valid username should be between $min_length_username_new and $max_length_username_new alphanumeric characters.";
        $error = true;
        exit();
    } elseif ((isset($_POST ["security"])) && (adjust($_POST ["security"]) == "enabled") && (!isset($_POST ["sec_pass"]) || $_POST ["sec_pass"] == "" || !check_password_formats($_POST ["sec_pass"]))) {
        echo "<2stT>The password of the 'User Account' is empty or invlid! A valid password should be between $minimum_password_length and $maximum_password_length alphanumeric characters with at least one uppercase letter and one number . ";
        $error = true;
        exit();
    } elseif ((isset($_POST ["security"])) && (adjust($_POST ["security"]) == "enabled") && (isset($_POST ["sec_Username"])) && (isset($_POST ["sec_pass"])) && (strtolower(trim($_POST ["sec_pass"])) == strtolower(trim($_POST ["sec_Username"])))) {
        echo "<2stT>The username and password of the 'User Account' can't be the same";
        $error = true;
        exit();
    } elseif ((isset($_POST ["security"])) && (adjust($_POST ["security"]) == "enabled") && (!isset($_POST ["sec_email"]) || $_POST ["sec_email"] == "")) {
        echo "<2stT>The Email of the 'User Account' is empty! ";
        $error = true;
        exit();
    } elseif ((isset($_POST ["security"])) && (adjust($_POST ["security"]) == "enabled") && (!check_is_email($_POST ["sec_email"]))) {
        echo "<2stT>The Email of the 'User Account' is not valid! ";
        $error = true;
        exit();
    } elseif ((isset($_POST ["members"])) && (adjust($_POST ["members"]) == "enabled") && (!isset($_POST ["sec_table"]) || !isset($_POST ["sec_Username_Field"]) || !isset($_POST ["sec_email_field"]) || !isset($_POST ["sec_pass_Field"]) || !isset($_POST ["sec_pass_hash_type"]) || $_POST ["sec_table"] == "" || $_POST ["sec_Username_Field"] == "" || $_POST ["sec_pass_Field"] == "" || $_POST ["sec_pass_hash_type"] == "" || $_POST ["sec_email_field"] == "")) {
        echo "<2stT> One or more of the 'saved Members' attributes are missing!";
        $error = true;
        exit();
    } elseif ((isset($_POST ["members"])) && (adjust($_POST ["members"]) == "enabled") && (!in_array(strtolower($_POST ["sec_pass_hash_type"]), $password_hashing))) {
        echo "<2stT> Unexpected password hashing type in the 'Saved Members' details!";
        $error = true;
        exit();
    } elseif ((isset($_POST ["members"])) && (adjust($_POST ["members"]) == "enabled") && (!check_is_clean($_POST ["sec_table"]) || !check_is_clean($_POST ["sec_Username_Field"]) || !check_is_clean($_POST ["sec_pass_Field"]) || !check_is_clean($_POST ["sec_email_field"]))) {
        echo "<2stT> One or more of the 'saved Members' attributes are not valid!";
        $error = true;
        exit();
    } elseif ((isset($_POST ["members"])) && (adjust($_POST ["members"]) == "enabled") && ($_POST ["sec_pass_Field"] == $_POST ["sec_Username_Field"] || $_POST ["sec_Username_Field"] == $_POST ["sec_email_field"] || $_POST ["sec_email_field"] == $_POST ["sec_pass_Field"])) {
        echo "<2stT> One or more of the 'saved Members' attributes are not unique!!";
        $error = true;
        exit();
    } else {
        $_SESSION ["srm_f62014_allow_only_admin"] = "no";
        $_SESSION ["srm_f62014_is_public_access"] = "no";
        // save the user account in the session
        if (isset($_POST ["security"]) && adjust($_POST ["security"]) == "enabled") {
            $_SESSION ["srm_f62014_security"] = "enabled";
            $_SESSION ["srm_f62014_sec_Username"] = $_POST ["sec_Username"];
            require_once ("../../Reports8/shared/helpers/Model/Member.php");
            $member = new Member ();
            $_SESSION ["srm_f62014_sec_pass"] = $member->hashpassword($_POST ["sec_pass"]);
            $_SESSION ["srm_f62014_sec_email"] = $_POST ["sec_email"];
        } else {
            $_SESSION ["srm_f62014_security"] = "";
            $_SESSION ["srm_f62014_sec_Username"] = "";
            $_SESSION ["srm_f62014_sec_pass"] = "";
            $_SESSION ["srm_f62014_sec_email"] = "";
        }
        if (isset($_POST ["members"]) && adjust($_POST ["members"]) == "enabled") {
            $_SESSION["srm_f62014_members"] = "enabled";
            $_SESSION ["srm_f62014_sec_table"] = clean_input($_POST ["sec_table"]);
            $_SESSION ["srm_f62014_sec_Username_Field"] = clean_input($_POST ["sec_Username_Field"]);
            $_SESSION ["srm_f62014_sec_pass_Field"] = clean_input($_POST ["sec_pass_Field"]);
            $_SESSION ["srm_f62014_sec_pass_hash_type"] = clean_input($_POST ["sec_pass_hash_type"]);
            $_SESSION ['srm_f62014_sec_email_field'] = clean_input($_POST ["sec_email_field"]);
        } else {
            $_SESSION["srm_f62014_members"] = "";
            $_SESSION ["srm_f62014_sec_table"] = "";
            $_SESSION ["srm_f62014_sec_Username_Field"] = "";
            $_SESSION ["srm_f62014_sec_pass_Field"] = "";
            $_SESSION ["srm_f62014_sec_pass_hash_type"] = "";
            $_SESSION ['srm_f62014_sec_email_field'] = "";
        }
    }
} elseif (isset($_POST ["access"]) && strtolower($_POST ["access"]) === "public") {
    /*
     * #################################################################################################
     * 2-3) case public report
     * ################################################################################################
     */

    if (is_security_details_sent() === true) {
        echo "<2stT> Access rule is set to 'public report' yet there is specification related to 'User Account' in the request";
        $error = true;
        exit();
    } elseif (is_members_details_sent() === true) {
        echo "<2stT> Access rule is set to 'public' yet there is specification related to 'Saved Members' in the request";
        $error = true;
        exit();
    } else {
        $_SESSION ["srm_f62014_allow_only_admin"] = "no";
        $_SESSION ["srm_f62014_security"] = "";
        $_SESSION ["srm_f62014_sec_Username"] = "";
        $_SESSION ["srm_f62014_sec_email"] = "";
        $_SESSION ["srm_f62014_members"] = "";
        $_SESSION ["srm_f62014_sec_table"] = "";
        $_SESSION ["srm_f62014_sec_Username_Field"] = "";
        $_SESSION ["srm_f62014_sec_pass_Field"] = "";
        $_SESSION ["srm_f62014_sec_pass_hash_type"] = "";
        $_SESSION ['srm_f62014_sec_email_field'] = "";
        $_SESSION ["srm_f62014_is_public_access"] = "yes";
    }
} else {
    /*
     * #################################################################################################
     * 2-4) Case Other
     * ################################################################################################
     */
    echo "<2stT> The Access rule are not set correctly, please specify who can access the generated report.";
    $error = true;
    exit();
}

/*
 * #################################################################################################
 * General Settings
 * ################################################################################################
 */

// language

$lang = (isset($_POST ["lang"]) && !empty($_POST ["lang"])) ? $_POST ["lang"] : "";
if ($lang == "" || !in_array($_POST ["lang"], $languages_array)) {
    echo '<3stT>- Please select a valid language.';
    $error = true;
    exit();
} else {
    $_SESSION ['srm_f62014_language'] = $lang;
}

require_once ("../../Reports8/shared/helpers/Model/codegniter/file_helper.php");
require_once ("../../Reports8/shared/helpers/Model/safeValue.php");
require_once ("../../Reports8/shared/helpers/Model/Template.php");
require_once ("../../Reports8/shared/helpers/Model/TemplateManger.php");
$TemplateManager = new TemplateManager("../../Reports8/", $_SESSION ['srm_f62014_user'], $_SESSION ['srm_f62014_db']);
$is_save_template = (isset($_POST ["isTemplate"]) && !empty($_POST ["isTemplate"]) && $_POST ["isTemplate"] == "enabled") ? "enabled" : "disabled";
$template_name = (isset($_POST ["TemplateName"]) && !empty($_POST ["TemplateName"])) ? clean_input($_POST ["TemplateName"]) : "";
if (!in_array($_POST ["isTemplate"], array(
            "enabled",
            "disabled"
        ))) {
    echo '<3stT>- ' . $_POST ["isTemplate"] . ' is an unrecognized template option! .';
    $error = true;
    exit();
} elseif ($is_save_template == "enabled" && empty($template_name)) {
    echo '<3stT>- Add a template name or Uncheck the "Save report as a template" box .';
    $error = true;
    exit();
} elseif ($is_save_template != "enabled" && !empty($template_name)) {
    echo '<3stT>- Check the "Save report as a template" or delete the template name .';
    $error = true;
    exit();
} elseif ($TemplateManager->is_exist($template_name, true)) {
    echo '<3stT>- A template with this name already exists, please choose another name for your template.';
    $error = true;
    exit();
} else {
    // save template in a session
    $_SESSION ["srm_f62014_save_template_name"] = clean_input($template_name);
    $_SESSION ["srm_f62014_is_template"] = $is_save_template;
}

if (!is_numeric($_POST ['txt_records_per_page'])) {
    echo '<3stT>- Record per page must be numeric.';
    $error = true;
    exit();
}
$txt_report_title = (isset($_POST ['txt_report_title'])) ? clean_input($_POST ['txt_report_title']) : "";
$txt_report_header = (isset($_POST ['txt_report_header'])) ? clean_input($_POST ['txt_report_header']) : "";
$txt_report_footer = (isset($_POST ['txt_report_footer'])) ? clean_input($_POST ['txt_report_footer']) : "";
$txt_records_per_page = (isset($_POST ['txt_records_per_page'])) ? clean_input($_POST ['txt_records_per_page']) : "";
$txt_report_name = (isset($_POST ['txt_report_name'])) ? clean_input($_POST ['txt_report_name']) : "";
$category_option = (isset($_POST["category_option"]) && (strtolower($_POST["category_option"]) == "new" || strtolower($_POST["category_option"]) == "existing")) ? strtolower($_POST["category_option"]) : "";
$selected_Category = (isset($_POST["selected_category"]) && $_POST["selected_category"] != "") ? $_POST["selected_category"] : "";
$new_category = (isset($_POST["new_category"]) && $_POST["new_category"] != "" ) ? $_POST["new_category"] : "";
if (empty($txt_report_name) || $txt_report_name === "") {
    echo "<3stT>- Please enter report name";
    $error = true;
    exit();
} else if ($prevent_overwrite_existing_tables != "no" && file_exists("../../Reports8/rep" . $txt_report_name . "/config.php")) {
    echo "<3stT>- A report with this name already exists, please choose another name for your report";
    $error = true;
    exit();
} elseif ($category_option == "" || !in_array(strtolower($category_option), array("new", "existing"))) {
    echo "<3stT>-Please select an existing category or add a new one";
    $error = true;
    exit();
} elseif (strtolower($category_option) == "new" && isset($_SESSION["tmp_all_categories"]) && in_array(strtolower($category_option), $_SESSION["tmp_all_categories"])) {
    echo "<3stT>-The added new category is already exists";
    $error = true;
    exit();
} elseif ($selected_Category == "" && $new_category == "") {
    echo "<3stT>-Please select an existing category or add a new one";
    $error = true;
    exit();
} else {

    $_SESSION ['srm_f62014_date_created'] = date("d-M-Y H:i:s");
    $_SESSION ['srm_f62014_title'] = $txt_report_title;
    $_SESSION ['srm_f62014_header'] = $txt_report_header;
    $_SESSION ['srm_f62014_footer'] = $txt_report_footer;
    $_SESSION ['srm_f62014_file_name'] = $txt_report_name;
    $_SESSION ['srm_f62014_records_per_page'] = $txt_records_per_page;
    $_SESSION ['srm_f62014_chkSearch'] = ($_SESSION ["srm_f62014_datasource"] === 'table') ? "Yes" : "";
    if (strtolower($category_option) == "new") {
        $_SESSION ['srm_f62014_category'] = $new_category;
    } else {
        $_SESSION ['srm_f62014_category'] = $selected_Category;
    }
}

if ($error == false) {
    echo "success";
}
exit();
