<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");
defined('BASEPATH') or define('BASEPATH', 1);
$default = "new";
$disable = "";
require_once ("request.php");
require_once ("lib.php");
require_once 'checkSession.php';
require_once 'services/functions.php';
require_once("../Reports8/shared/helpers/Model/codegniter/file_helper.php");
require_once ("../../HomePage/models/Report.class.php");
require_once("../../HomePage/models/Dashboard.class.php");
$Report_location = "../Reports8/";
$Dashboard = new DashBoard($Report_location);
$categories = array_diff($Dashboard->get_categories(), array("Uncategorized", "legacy"));
$_SESSION["tmp_all_categories"] = $categories;

$categories_options = array();
if (count($categories) < 1) {
    $categories_options = "<option value='no value' selected>No saved categories found!</option>";
    $default = "new";
    $disable = "existing";
} else {
    $categories_options[0] = "<option value='no value' selected>Select a saved category</option>";
    foreach ($categories as $v) {
        if ($v != "Uncategorized" && $v != "legacy") {
            if (isset($_SESSION ['srm_f62014_category']) && $v === $_SESSION ['srm_f62014_category'])
                $categories_options[] = "<option value=$v selected>$v</option>";
            else
                $categories_options[] = "<option value=$v>$v</option>";
        }
        $default = "existing";
    }
}


/*
 * #################################################################################################
 * Security options loading
 * ################################################################################################
 */

if (isset($_SESSION ["srm_f62014_allow_only_admin"]) && strtolower($_SESSION ["srm_f62014_allow_only_admin"]) == "yes") {
    $only_admin = "yes";
    $is_public_access = "no";
    $admin_and_users = "no";
} elseif (((isset($_SESSION ["srm_f62014_security"]) && strtolower($_SESSION ["srm_f62014_security"]) == "enabled") || (isset($_SESSION ["srm_f62014_members"]) && strtolower($_SESSION ["srm_f62014_members"]) == "enabled"))) {
    $is_public_access = "no";
    $only_admin = "no";
    $admin_and_users = "yes";
    // loading the members and security settings
} elseif ((isset($_SESSION ["srm_f62014_is_public_access"]) && strtolower($_SESSION ["srm_f62014_is_public_access"]) === "yes") && (isset($_SESSION ["srm_f62014_allow_only_admin"]) && strtolower($_SESSION ["srm_f62014_allow_only_admin"]) != "yes") && (isset($_SESSION ["srm_f62014_security"]) && strtolower($_SESSION ["srm_f62014_security"]) != "enabled") && (isset($_SESSION ["srm_f62014_members"]) && strtolower($_SESSION ["srm_f62014_members"]) != "enabled")) {
    $is_public_access = "yes";
    $only_admin = "no";
    $admin_and_users = "no";
} else {
    // default case .
    $only_admin = "yes";
    $is_public_access = "no";
    $admin_and_users = "no";
}
$used_language = (isset($_SESSION ['srm_f62014_language']) && !empty($_SESSION ['srm_f62014_language'])) ? $_SESSION ['srm_f62014_language'] : "en";
$save_as_template = (isset($_SESSION ["is_template"]) && $_SESSION ["is_template"] == "enabled") ? "enabled" : "disabled";
$template_name = (isset($_SESSION ["save_template_name"]) && !empty($_SESSION ["save_template_name"])) ? $_SESSION ["save_template_name"] : "";

if (!empty($template_name))
    $save_as_template = "enabled";

if (sessionBe4Step4() === false) {
    header("location: $url?id=1");
    exit();
} else if (sessionBe4Step5() === false) {
    header("location: $url?id=2");
    exit();
}
$_SESSION ['srm_f62014_page_key'] = "step_6";
require_once 'activePages.php';

// set layout and images associated with it
$layout = isset($_SESSION ["srm_f62014_layout"]) ? $_SESSION ["srm_f62014_layout"] : 'AlignLeft';

if (strtolower($layout) == 'alignleft')
    $image = 'layout_align_left1.gif';
else if (strtolower($layout) == 'mobile')
    $image = 'mob-layout.png';

else if (strtolower($layout) == 'stepped')
    $image = 'layout_stepped.gif';
else if (strtolower($layout) == 'block')
    $image = 'layout_block.gif';

else if (strtolower($layout) == 'horizontal')
    $image = 'horizontal.gif';

if (isset($_SESSION ['srm_f62014_style_name']) && !empty($_SESSION ['srm_f62014_style_name']))
    $style_name = $_SESSION ['srm_f62014_style_name'];
else{
    $_SESSION ['srm_f62014_style_name'] = "default";
    $style_name = "default";
    
}

// read styles from styles directory
$styles = "";
$all_styles = array(
    "blue",
    "default",
    "grey"
);
if (strtolower($layout) != "mobile") {
    foreach ($all_styles as $style) {

        $formatted_css_name = $style;

        if ($style_name === $formatted_css_name)
            $styles .= "<option value='$formatted_css_name' selected>" . $formatted_css_name . "</option>";
        else
            $styles .= "<option value='$formatted_css_name'>" . $formatted_css_name . "</option>";
    }
}else{
     $styles .= "<option value='mobile' selected> Mobile </option>";
}

// get tables and columns for security panel
$mydb = clean_input($_SESSION ["srm_f62014_db"]);
$tables = $dbHandler->query("show tables from `$mydb`");

$securityTablesInfo = array();
foreach ($tables as $table) {
    $columns = $dbHandler->query("show columns from `" . $table [0] . "`");
   
    foreach ((array)$columns as $column)
        $securityTablesInfo [$table [0]] [] = $column [0];
}

// this display option in select elements
function display_options($string) {
    global $_SESSION, $tables;
     $selected = "";
    echo "<option value='NoValue'> Please select a value </option>";
    if ($string === "sec_table") {
        foreach ($tables as $row) {
            if (isset($_SESSION ["srm_f62014_sec_table"]) && $row [0] === $_SESSION ["srm_f62014_sec_table"])
                $selected = "selected";
            echo "<option $selected value='" . $row [0] . "'>" . $row [0] . "</option>";
            $selected = "";
        }
    } else if (isset($_SESSION ["srm_f62014_sec_table"]) && CheckVar($_SESSION ["srm_f62014_sec_table"]) && $string === "sec_Username_Field") {
        if (CheckVar($_SESSION ["srm_f62014_sec_Username_Field"]))
            LoadFields($_SESSION ["srm_f62014_sec_Username_Field"]);
        else
            LoadFields("");
    } else if (isset($_SESSION ["srm_f62014_sec_table"]) &&CheckVar($_SESSION ["srm_f62014_sec_table"]) && $string === "sec_pass_Field") {
        if (CheckVar($_SESSION ["srm_f62014_sec_pass_Field"]))
            LoadFields($_SESSION ["srm_f62014_sec_pass_Field"]);
        else
            LoadFields("");
    }else if (isset($_SESSION ["srm_f62014_sec_table"]) && CheckVar($_SESSION ["srm_f62014_sec_table"]) && $string === "sec_email_field") {
        if (CheckVar($_SESSION ['srm_f62014_sec_email_field']))
            LoadFields($_SESSION ['srm_f62014_sec_email_field']);
        else
            LoadFields("");
    }
}

// set columns for select elements
function LoadFields($selectedField) {
    global $securityTablesInfo;
    $fields = $securityTablesInfo [$_SESSION ["srm_f62014_sec_table"]];
    foreach ($fields as $key => $field) {
        if ($field == $selectedField)
            $selected = "selected";
        else
            $selected = "";
        echo "<option $selected value='" . $field . "'>" . $field . "</option>";
    }
}

$json = json_encode($securityTablesInfo); // send data(tables and columns) to client side to make less loading
// get default value for titles
function get_default_value($var) {
    if (!isset($_SESSION ["srm_f62014_records_per_page"]))
        $_SESSION ["srm_f62014_records_per_page"] = 25;

    if ($var == 'txt_report_title')
        $s_var = 'srm_f62014_title';
    else if ($var == 'txt_report_header')
        $s_var = 'srm_f62014_header';
    else if ($var == 'txt_report_footer')
        $s_var = 'srm_f62014_footer';
    else if ($var == 'txt_report_name')
        $s_var = 'srm_f62014_file_name';
    else if ($var == 'template')
        $s_var = 'save_template_name';
    else if ($var == 'txt_records_per_page')
        $s_var = 'srm_f62014_records_per_page';

    if (isset($_SESSION [$s_var]) && !empty($_SESSION [$s_var]))
        return $_SESSION [$s_var];
    else
        return "";
}
?>
<div id="tabs" class="container col-xs-12">
    <!-- -->
    <!-- Nav tabs nav nav-tabs -->
    <ul class="" style="font-size: 12px;">
        <li class="active"><a id="layout-nav" href="#layout" data-toggle="tab"><span
                    class="glyphicon glyphicon-list"></span> Appearance</a></li>
        <li class="active"><a id="security-nav" href="#securityPanel"
                              data-toggle="tab"><span class="glyphicon glyphicon-lock"></span>
                Security</a></li>
        <li class="active"><a id="titles-nav" href="#titlesPanel"
                              data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span>
                Titles</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="layout">
            <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post"
                  onsubmit="return false;">
                <!-- Please select the report layout -->
                <div class="row">
                    <div class="col-xs-1"></div>
                    <div id="layout-error-container" class="col-xs-10"></div>
                    <div class="col-xs-1"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1"></div>
                    <div class="col-xs-3"
                         style="margin: 0px; padding: 0px; border-bottom: 1px solid #dfdfdf;">Layout</div>
                    <div class="col-xs-8"></div>
                </div>
                <div class="row">
                    <div class="col-xs-1"></div>
                    <div class="col-xs-3">


                        <div class="row">
                            <div class="radio">
                                <label> <input type="radio" name="option" id="AlignLeft"
                                               value="AlignLeft" /> Align Left
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="radio">
                                <label> <input type="radio" name="option" id="Stepped"
                                               value="Stepped" /> Stepped
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="radio">
                                <label> <input type="radio" name="option" id="Block"
                                               value="Block" /> Block
                                </label>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="radio">
                                <label> <input type="radio" name="option" id="Mobile"
                                               value="Mobile" /> Mobile <!--onclick="document['img_layout'].src= 'images/mob-layout.png';"-->
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="radio">
                                <label> <input type="radio" name="option" id="Horizontal"
                                               value="Horizontal" />Horizontal <span
                                               style="color: red; font-size: 10px;">(new)*</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <img src="includes/images/<?php echo $image; ?>" width="233"
                             height="210" id="img_layout" name="img_layout"
                             class="img-thumbnail" />

                    </div>
                    <div class="col-xs-2">
                        <a href="" id="layoutHelp" onclick="return false;"> <img
                                src="includes/images/help.png" width="15" height="15" border="0">
                        </a>
                    </div>
                </div>

                <hr />

                <div class="row" style="margin-top: -10px;">
                    <div class="col-xs-1" style="margin-left: -10px;"></div>
                    <div class="form-group col-xs-5">
                        <label for="style_name">Styles</label>
                        <div class="left-inner-addon">
                            <i class="glyphicon glyphicon-fire"></i> <select id="style_name"
                                                                             name="style_name" class="form-control">
                                <!--  onChange="refresh()" -->
                                <?php echo $styles; ?>
                            </select>
                        </div>
                    </div>
                    <div class="help-container-i col-xs-6">
                        <a href="" id="styleHelp" onClick="return false;"> <img
                                src="includes/images/help.png" width="15" height="15" border="0">
                        </a>
                    </div>

                </div>



                <!-- .row (style_name) -->

            </form>
        </div>
        <div class="tab-pane" id="securityPanel" style="min-height: 250px;">
            <form name="secForm" id="secForm" role="form"
                  action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post"
                  onsubmit="return false;">

                <input type='hidden' id='csrfToken' name='csrfToken'
                       value='<?php echo $request_token_value; ?>' />

                <div class="row">
                    <div class="col-xs-1"></div>

                    <div id="error-container" class="col-xs-10"></div>
                    <div class="col-xs-1"></div>
                </div>

                <!-- -->
                <div class="row">
                    <a class="cr-hand" id="mainSettings"><span
                            class="glyphicon glyphicon-view"></span> <span
                            class="glyphicon glyphicon-eye-open"></span> Who can access the
                        generated report ?</a>
                </div>

                <div class="row" id="adminOnly">
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-11">
                            <div>
                                <label class="lblsecurity"> <input class="radiosecurity" type="radio"
                                                                   value="adminOnly" name="access"
                                                                   <?php if (strtolower($only_admin) === "yes") echo "checked"; ?> />
                                    Only the admin (which is you) can access this report .
                                </label>
                            </div>
                            <div>
                                <label class="lblsecurity"> <input class="radiosecurity" type="radio"
                                                                   id="adminAndUsers" value="adminAndUsers" name="access"
                                                                   <?php if (strtolower($admin_and_users) == "yes") echo "checked"; ?> />
                                    The admin (which is you) and other registered users can access
                                    this report after authentication.
                                </label>
                            </div>
                            <div>
                                <label class="lblsecurity"> <input class="radiosecurity" type="radio" value="public"
                                                                   name="access"
                                                                   <?php if (strtolower($is_public_access) === "yes" && strtolower($only_admin) != "yes" && strtolower($admin_and_users) != "yes") echo "checked"; ?> />
                                    This report is public (anyone on the Internet can access this
                                    report, without authentication) .
                                </label>
                            </div>
                            <br />
                            <div id="advanced" style="display: none;">
                                <div class="row">
                                    <a class="cr-hand" id="secOptions-controller"><span
                                            id="status-icon" class="glyphicon glyphicon-play font-xs"></span>
                                        <span class="glyphicon glyphicon-lock"></span> User Account</a>
                                </div>
                                <div class="row" id="secOptions" style="display: none;">
                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="col-xs-11">
                                            <div class="checkbox">
                                                <label> <input type="checkbox" id="security" name="security"
                                                               class="security" 
                                                               <?php
                                                               if (isset($_SESSION["srm_f62014_security"]) && $_SESSION["srm_f62014_security"] === "enabled")
                                                                   echo " checked ";
                                                               ?> /> Allow a user (other than the admin) to access this
                                                    report using the following information
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="security">User Name</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-user"></i> <input type="text"
                                                                                                data-disable-controller="security" class="form-control"
                                                                                                value="<?php if (isset($_SESSION["srm_f62014_sec_Username"])) echo $_SESSION["srm_f62014_sec_Username"]; ?>"
                                                                                                name="sec_Username" id="sec_Username"?>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="adminUserHelp" onClick="return false;"> <img
                                                    src="includes/images/help.png" width="15" height="15"
                                                    border="0">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="sec_pass">Password</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-lock"></i> <input
                                                    type="password" data-disable-controller="security"
                                                    class="form-control"
                                                    value="<?php // if(isset($_SESSION["srm_f62014_sec_pass"])) echo $_SESSION["srm_f62014_sec_pass"];  ?>"
                                                    name="sec_pass" id="sec_pass"?>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="adminPassHelp" onClick="return false;"> <img
                                                    src="includes/images/help.png" width="15" height="15"
                                                    border="0">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="sec_email">Notification Email</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-envelope"></i> <input
                                                    type="text" data-disable-controller="security"
                                                    class="form-control"
                                                    value="<?php if (isset($_SESSION["srm_f62014_sec_email"])) echo $_SESSION["srm_f62014_sec_email"]; ?>"
                                                    id="sec_email" name="sec_email"?>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="adminEmailHelp" onClick="return false;"> <img
                                                    src="includes/images/help.png" width="15" height="15"
                                                    border="0">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <!--  -->

                                <div class="row">
                                    <a class="cr-hand" id="memberLogin-controller"><span
                                            id="status-icon1" class="glyphicon glyphicon-play font-xs"></span>
                                        <span class="glyphicon glyphicon-user"></span>Saved Members
                                        Login Details</a>
                                </div>
                                <div class="row" id="memberLogin" style="display: none;">
                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="col-xs-11">
                                            <div class="checkbox">
                                                <label> <input type="checkbox" id="members" name="members"
                                                               class="security"
<?php if (isset($_SESSION["srm_f62014_members"]) && $_SESSION["srm_f62014_members"] === "enabled") echo "checked"; ?> />Allow
                                                    members (whom usernames and passwords are saved in your Db)
                                                    to login to this report.
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="sec_table">Members Table</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-list-alt"></i> <select
                                                    data-disable-controller="members" class="form-control"
                                                    id="sec_table" name="sec_table">
<?php display_options("sec_table"); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="memberTableHelp" onClick="return false;"> <img
                                                    src="includes/images/help.png" width="15" height="15"
                                                    border="0">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="sec_Username_Field">UserName Field</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-user"></i> <select
                                                    data-disable-controller="members" class="form-control"
                                                    id="sec_Username_Field" name="sec_Username_Field">
<?php display_options("sec_Username_Field"); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="memberUserHelp" onClick="return false;"> <img
                                                    src="includes/images/help.png" width="15" height="15"
                                                    border="0">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="sec_pass_Field">Password Field</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-lock"></i> <select
                                                    data-disable-controller="members" class="form-control"
                                                    id="sec_pass_Field" name="sec_pass_Field">
<?php display_options("sec_pass_Field"); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="memberPassHelp" onClick="return false;"> <img
                                                    src="includes/images/help.png" width="15" height="15"
                                                    border="0">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="sec_pass_hash_type">Password Hash Type</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-lock"></i> <select
                                                    data-disable-controller="members" class="form-control"
                                                    id="sec_pass_hash_type" name="sec_pass_hash_type">
                                                    <option value="none"
<?php if (isset($_SESSION["srm_f62014_sec_pass_hash_type"]) &&$_SESSION["srm_f62014_sec_pass_hash_type"] === 'none') echo 'selected'; ?>>None</option>
                                                    <option value="md5"
                                                            <?php if (isset($_SESSION["srm_f62014_sec_pass_hash_type"]) &&$_SESSION["srm_f62014_sec_pass_hash_type"] === 'md5') echo 'selected'; ?>>MD5</option>
                                                    <option value="sha1"
                                                            <?php if (isset($_SESSION["srm_f62014_sec_pass_hash_type"]) &&$_SESSION["srm_f62014_sec_pass_hash_type"] === 'sha1') echo 'selected'; ?>>SHA1</option>
                                                    <option value="sha256"
                                                            <?php if (isset($_SESSION["srm_f62014_sec_pass_hash_type"]) &&$_SESSION["srm_f62014_sec_pass_hash_type"] === 'sha256') echo 'selected'; ?>>sha256</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="memberPassHashTypeHelp"
                                               onClick="return false;"> <img src="includes/images/help.png"
                                                                          width="15" height="15" border="0">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-6">
                                            <label for="sec_email_field">Email Field</label>
                                            <div class="left-inner-addon">
                                                <i class="glyphicon glyphicon-lock"></i> <select
                                                    data-disable-controller="members" class="form-control"
                                                    id="sec_email_field" name="sec_email_field">
<?php display_options("sec_email_field"); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="help-container col-xs-5">
                                            <a href="" id="memberEmailHelp" onClick="return false;"> <img
                                                    src="includes/images/help.png" width="15" height="15"
                                                    border="0">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row" id="note">
                                        <div class="col-xs-1"></div>
                                        <div class="form-group col-xs-10">

                                        </div>
                                        <div class="col-xs-1"></div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                    </div>
                </div>

        </div>




        </form>
    </div>
    <div class="tab-pane" id="titlesPanel">

        <form id="titlesForm" name="titlesForm"
              action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post"
              onsubmit="return false;">
            <!-- Report Settings -->
            <div class="row">
                <div class="col-xs-1"></div>
                <div id="titles-error-container" class="col-xs-10"></div>
                <div class="col-xs-1"></div>
            </div>
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <label for="txt_report_title">Report Title</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-edit"></i> <input
                            name="txt_report_title" class="form-control" type="text"
                            id="txt_report_title"
                            value="<?php echo get_default_value('txt_report_title') ?>" />
                    </div>
                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="rTitleHelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>
            </div>
            <br />
            <div class="row form-group col-xs-12" style="margin-top: -10px;">
                <div class="col-xs-1" style="margin-left: -10px;"></div>
                <div class="form-group col-xs-5">
                    <input type="radio" name="categoryOption" value="existing" <?php if ($default == "existing") echo "checked"; if ($disable == "existing") echo "disabled"; ?>/>
                    <label for="style_name">Existing Category</label>

                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-fire"></i> <select id="existing_category_name"
                                                                         name="existing_category_name" class="form-control"  <?php if ($disable == "existing") echo "disabled" ?>>
                            <!--  onChange="refresh()" -->
<?php
foreach ($categories_options as $option)
    echo $option;
?>
                        </select>

                    </div>
                </div>

                <div class="form-group col-xs-5">
                    <input type="radio" name="categoryOption" value="new" <?php if ($default == "new") echo "checked"; ?> />
                    <label for="style_name">New Category</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-fire"></i> <input id="Category_new_name"
                                                                        name="Category_new_name" class="form-control" type="text">
                    </div>
                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="CategoryHelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>

            </div>


            <br />
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <label for="txt_report_title">Report Language</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-edit"></i> <select
                            name="txt_report_language" class="form-control"
                            id="txt_report_language">

                        </select>
                    </div>
                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="rLanguageHelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>
            </div>
            <br />


            <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <input type="checkbox" id="saveTemplate"  checked/> <label
                        for="saveTemplate">Save this report as a template</label>


                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="rIstemplate" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>

            </div>





            <div id="template_frame" class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">

                    <label for="template" disabled>Template Name</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-edit"></i> <input name="template"
                                                                        type="text" id="template" class="form-control"
                                                                        value="<?php echo get_default_value('template') ?>" />

                    </div>
                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="rtemplate" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <label for="txt_report_footer">Report Footer</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-edit"></i>
                        <textarea name="txt_report_footer" class="form-control" rows="2"
                                  id="txt_report_footer"><?php echo get_default_value('txt_report_footer') ?></textarea>
                    </div>
                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="rFooterHelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0"
                            align="absmiddle">
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <label for="txt_report_header">Report Header</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-edit"></i>
                        <textarea name="txt_report_header" class="form-control" rows="2"
                                  id="txt_report_header"><?php echo get_default_value('txt_report_header') ?></textarea>
                    </div>
                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="rHeaderHelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <label for="txt_report_name">Report name</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-edit"></i> <input
                            name="txt_report_name" type="text" id="txt_report_name"
                            class="form-control"
                            value="<?php echo get_default_value('txt_report_name') ?>" />
                    </div>
                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="rNameHelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <label for="txt_records_per_page">Records per page</label>
                    <div class="left-inner-addon">
                        <i class="glyphicon glyphicon-edit"></i> <input
                            name="txt_records_per_page" type="text" id="txt_records_per_page"
                            class="form-control"
                            value="<?php echo get_default_value('txt_records_per_page') ?>">
                    </div>
                </div>

                <div class="help-container-i col-xs-1">
                    <a href="" id="rPPHelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>
            </div>
        </form>

    </div>
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-4">
            <button name="btn_back" id="btn_back" class="btn btn-sunny btn-block">
                <span class="icon glyphicon glyphicon-backward"></span><span
                    class="separator"></span> Back
            </button>
        </div>
        <div class="col-xs-3"></div>
        <div class="col-xs-4">
            <button name="continue" id="btn_cont" class="btn btn-sunny btn-block">
                <span class="icon glyphicon glyphicon-flag"></span><span
                    class="separator"></span> Finish
            </button>
        </div>
    </div>
    <!-- .row (navigation buttons) -->
</div>
<!-- to complete index tags -->
</div>

</div>
</div>
</div>
<script>
    var layout = "<?php echo $layout; ?>";
    var stylesExceptMobile = ["blue", "default", "grey"];
    var secTablesInfo = <?php echo $json; ?>;


    var selected_access_rule = "";
    $(function() {
        $("#tabs").tabs();
        //show and hide the advanced div in the security pannel
        advanced_pannel_apperance_control();
        $(".lblsecurity").change(advanced_pannel_apperance_control);
        $(".lblsecurity").mousedown(function() {
            selected_access_rule = $(".radiosecurity:checked").val();
            
        });



<?php
if ($save_as_template = "enabled" && !empty($template_name)) {
    ?>
            $("#saveTemplate").attr('checked', 'checked');
            $("#template_frame").show();


    <?php
} 
    ?>
           
        var languages = <?php echo json_encode($languages_array); ?>;



        var counter = 0;
        var used_language = <?php echo "'" . $used_language . "'"; ?>;
        if (used_language == "no")
            used_language = "en";
     
        $.each(languages, function(key, value) {




            $("#txt_report_language")
                    .append($("<option></option>")
                            .attr("value", value.replace(/[&\/\\#,+();$~%.'":*?<>{}]/g, ''))
                            .text(key.replace(/[&\/\\#,+();$~%.'":*?<>{}]/g, '')));



        });

        $("#txt_report_language").val(used_language).attr("selected", "selected");



        $("#saveTemplate").click(function() {
            if ($("#saveTemplate").is(':checked'))
                //$( "#template" ).prop( "disabled", false );
                $("#template_frame").show();
            else
                //$( "#template" ).prop( "disabled", true );
                $("#template_frame").hide();

        });


        $("#" + layout).prop("checked", true);

        $("#page-header").empty();
        $("#page-header").append('<div id="img-container"><img src="includes/images/appearance.png" width="70" height="70"/></div>');
        $("#page-header").append('<div id="text-container"><h4>Apperance</h4>Choose how report seem</div>');

        $("#layout-nav").click(function() {

            $("#page-header").empty();
            $("#page-header").append('<div id="img-container"><img src="includes/images/appearance.png" width="70" height="70"/></div>');
            $("#page-header").append('<div id="text-container"><h4>Apperance</h4>Choose how report seem</div>');

        });

        $("#security-nav").click(function() {

            $("#page-header").empty();
            $("#page-header").append('<div id="img-container"><img src="includes/images/security.png" width="70" height="70"/></div>');
            $("#page-header").append('<div id="text-container"><h4>Security</h4>Set Security options to your report</div>');

        });

        $("#titles-nav").click(function() {

            $("#page-header").empty();
            $("#page-header").append('<div id="img-container"><img src="includes/images/titles.png" width="70" height="70"/></div>');
            $("#page-header").append('<div id="text-container"><h4>Titles</h4>Set Titles for your report</div>');

        });
        // -------------------------------------- accordion in security panel-------------------------------
        $("#secOptions-controller").mousedown(function() {
            
            if ($("#secOptions").css("display") === "none")
            {
                $("#status-icon").removeClass("glyphicon-play");
                $("#status-icon").addClass("caret");
                $("#secOptions").show();
                $("#status-icon1").removeClass("caret");
                $("#status-icon1").addClass("glyphicon-play");
                $("#memberLogin").hide();
                $("#status-icon2").removeClass("caret");
                $("#status-icon2").addClass("glyphicon-play");
                $("#forgetPass").hide();
            } else {
                $("#status-icon").removeClass("caret");
                $("#status-icon").addClass("glyphicon-play");
                $("#secOptions").hide();
            }
        });

        //$("#secOptions-controller").trigger("mousedown");

        $("#memberLogin-controller").mousedown(function() {
           
            if ($("#memberLogin").css("display") === "none")
            {
                $("#status-icon1").removeClass("glyphicon-play");
                $("#status-icon1").addClass("caret");
                $("#memberLogin").show();
                $("#status-icon").removeClass("caret");
                $("#status-icon").addClass("glyphicon-play");
                $("#secOptions").hide();
                $("#status-icon2").removeClass("caret");
                $("#status-icon2").addClass("glyphicon-play");

            } else {
                $("#status-icon1").removeClass("caret");
                $("#status-icon1").addClass("glyphicon-play");
                $("#memberLogin").hide();
            }
        });


        // -------------------------------------------------------------------------------

        $("#Mobile").click(function() {
            $("#img_layout").attr("src", "includes/images/mob-layout.png");
            $("#style_name").empty();
            $("#style_name").append("<option value='mobile' selected>mobile</option>");
        });

        $("#AlignLeft").click(function() {
            $("#img_layout").attr("src", "includes/images/layout_align_left2.gif");
            setStyleOptions();
        });
        $("#Stepped").click(function() {
            $("#img_layout").attr("src", "includes/images/layout_stepped.gif");
            setStyleOptions();
        });
        $("#Block").click(function() {
            $("#img_layout").attr("src", "includes/images/layout_block.gif");
            setStyleOptions();
        });
      
        $("#Horizontal").click(function() {
            $("#img_layout").attr("src", "includes/images/horizontal.gif");
            setStyleOptions();
        });



        // disabled system in security panel
        $('[data-disable-controller]').each(function() {
            
            var controllerId = $(this).data("disableController");
            var element = $(this);
            //intial value
            $(this).prop("disabled", !($("#" + controllerId).prop('checked') === true));
            //disable and clear text
            $("#" + controllerId).click(function() {
              
                element.prop("disabled", !($(this).prop('checked') == true));
                if ($(this).prop('checked') !== true)
                    element.val("");

            });
        });

        $("#sec_table").bind("change", function()
        {
            var tableName = $(this).val();
            var columns = secTablesInfo[tableName];
            $("#sec_Username_Field").empty();
            $("#sec_pass_Field").empty();
            $("#sec_email_field").empty();
            $("#sec_Username_Field").append("<option selected  value='NoValue'> Please select a value </option>");
            $("#sec_pass_Field").append("<option selected  value='NoValue'> Please select a value </option>");
              $("#sec_email_field").append("<option selected  value='NoValue'> Please select a value </option>");
            for (var i = 0; i < columns.length; i++) {
                $("#sec_Username_Field").append("<option value='" + columns[i] + "''>" + columns[i] + "</option>");
                $("#sec_pass_Field").append("<option value='" + columns[i] + "''>" + columns[i] + "</option>");
                  $("#sec_email_field").append("<option value='" + columns[i] + "''>" + columns[i] + "</option>");
            }
            ;
        });

        // -------------------------------------------------------------------------------------------

        $("#btn_cont").mousedown(function() {
            $("#error-container").empty();
            $("#layout-error-container").empty();
            $("#titles-error-container").empty();

            var token = $("#csrfToken").val();
            var chosenLayout = $("input[name=option]:radio:checked").val();
            var lang = $("#txt_report_language").val();
            var category_option = $("input[name=categoryOption]:radio:checked").val();
            
            var Category_new_name = $("#Category_new_name").val();
            
            var selected_Category = $("#existing_category_name").val();
           
            if (category_option == "existing" && (selected_Category == "" || selected_Category == 'no value')) {
                $("#titles-nav").trigger("click");
                $("#titles-error-container").append("<div class='alert alert-danger'>" + "Please select a category" + "</div>");
                return;
            }
            var all_categories = <?php echo json_encode($categories); ?>;
            
            if (category_option == "new" && (Category_new_name == "")) {
                $("#titles-nav").trigger("click");
                $("#Category_new_name").val("");
                $("#titles-error-container").append("<div class='alert alert-danger'>" + "Please add a new category" + "</div>");
                return;
            }
            var isTemplate = $("#saveTemplate").is(':checked') ? "enabled" : "disabled";
            var templateName = $("#template").val();
            var chosenStyle = $("#style_name").val();
            var secValues = $("#secForm").serialize();
            var titlesValues = $("#titlesForm").serialize();
            $.ajax({
                url: "services/step_6.php",
                type: "post",
                data: "token=" + token + "&lang=" + lang + "&isTemplate=" + isTemplate + "&TemplateName=" + templateName + "&layout=" + chosenLayout + "&style_name=" + chosenStyle + "&" + secValues + "&" + titlesValues + "&category_option=" + category_option + "&selected_category=" + selected_Category + "&new_category=" + Category_new_name,
                success: function(data) {
                    data = data.trim();
                    $("#error-container").empty();
                    $("#layout-error-container").empty();
                    $("#titles-error-container").empty();
                    if (data === "success") {

                        location.assign("engine/common.php");
                        SwitchStatusDone();
                    } else {
                        modifier = data.substring(0, 6);
                        data = data.substring(6, data.length);
                        if (modifier === "<1stT>") {
                            $("#layout-nav").trigger("click");
                            $("#layout-error-container").append("<div class='alert alert-danger'>" + data + "</div>");
                        } else if (modifier === "<3stT>") {
                            $("#titles-nav").trigger("click");
                            $("#titles-error-container").append("<div class='alert alert-danger'>" + data + "</div>");
                        } else {
                            $("#security-nav").trigger("click");
                            $("#error-container").append("<div class='alert alert-danger'>" + data + "</div>");
                        }
                        SwitchStatusError();
                    }
                },
                error: function() {
                    alertify.error("error");
                }
            });
        });

        $("#btn_back").mousedown(function() {
            backToPage("5");
        });


        $('input[name=option]').change(function() {
           
            $('#security').prop('checked', false);
            $('#security').prop('disabled', false);
            $('#members').prop('checked', false);
            $('#members').prop('disabled', false);

            $('#sec_Username').prop('disabled', false);
            $('#sec_pass').prop('disabled', false);
            $('#error-container').empty();

        });



    });

    function advanced_pannel_apperance_control() {
        //change color of selected option
     


        var selected_radio = $(this).closest('form').find('input[type="radio"]:checked');
        
        if (selected_radio.val() == "public") {
            if (confirm("Are you sure you want this report to be public, where any one on the internet can access it WITHOUT authentication ?") == false) {
                $(".radiosecurity[value=" + selected_access_rule + "]").prop('checked', 'checked');
                return;
            }
        }

        $('label').css('color', '#000');
        $('label').css("font-weight", "normal");
        selected_radio.closest('label').css('color', 'blue');
        selected_radio.closest('label').css("font-weight", "Bold");

        if ($("#adminAndUsers").is(':checked')) {
            $("#advanced").show();            
            $('[data-disable-controller]').prop("disabled", false);
        } else {
            $("#advanced").hide();
            $('#security').prop('checked', false);
            $('[data-disable-controller]').prop("disabled", true);
            $("#members").prop('checked', false);
        }
    }

    function setStyleOptions()
    {
        var isSelected = "";
        $("#style_name").empty();
        for (var i = 0; i < stylesExceptMobile.length; i++)
        {
            if (i === 2)
                isSelected = "selected";
            else
                isSelected = "";
            $("#style_name").append("<option value='" + stylesExceptMobile[i] + "'" + isSelected + ">" + stylesExceptMobile[i] + "</option>");
        }
    }

    /*function confirm_public(){
     if (confirm('Are you sure you want this report to be a public report, where anyone on the Internet can access it, without authentication ? ') != false) {
     return false;
     }
     }*/

</script>