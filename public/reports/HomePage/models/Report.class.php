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

class Report {

    public $category;
    public $name;
    public $directory_url;
    public $link;
    public $date_created;
    public $is_public;
    public $is_private;
    public $is_admin_only;
    public $is_mobile;
    public $Access_role;
    public $is_legacy_report;

    public function __construct($report_name, $report_url, $directory_date = "", $is_legacy_report = false) {
        if (!$is_legacy_report) {
            //Version 7 report
            $this->is_legacy_report = false;
            $this->name = $report_name;
            $this->directory_url = $report_url;
            require ($this->directory_url . "config.php");
            $this->category = (isset($category) && $category != "") ? $category : "Uncategorized";
            $this->date_created = isset($date_created) ? $date_created : $directory_date;
            $this->is_admin_only = (isset($allow_only_admin) && strtolower(trim($allow_only_admin)) == "yes") ? True : false;
            $this->is_private = ((isset($security) && strtolower(trim($security)) == "enabled") || (isset($members) && strtolower(trim($members)) == "enabled" ) || (isset($sec_pass) && $sec_pass != "")) ? True : false;
            $this->is_public = ($this->is_admin_only || $this->is_private) ? false : true;
            $this->is_mobile = (isset($layout) && strtolower(trim($layout)) == "mobile") ? true : false;

            //handle if config is corrupt
            // in all layouts
            if ($this->is_mobile) {
                $this->link = $this->directory_url . "Mobile.php";
            } else {
                $this->link = $this->directory_url . $this->name . ".php";
            }
            if ($this->is_private) {
                $this->Access_role = '<span class="glyphicon glyphicon-lock"></span><font color="red"> (**Private Report) </font>';
            } elseif ($this->is_admin_only) {
                $this->Access_role = '<span class="glyphicon glyphicon-lock"></span><font color="red"> (**Only Admin can access this report) </font>';
            } else {
                $this->Access_role = '<span class="glyphicon glyphicon-eye-open"></span><font color="red"> **Public Report ("Can be accessed by anyone without any authentication") </font>';
            }
        } else {
            //legacy report
            $this->name = $report_name;
            $this->directory_url = $report_url;
            require ($this->directory_url . "config.php");
            $this->category = "**Legacy reports from a previous version";;
            $this->date_created = $directory_date;
            $this->Access_role = ' <span class="glyphicon glyphicon-history"></span><font color="red"> **Legacy Report (was created by a previous version of Smart Report Maker) </font>';
            $this->link = $this->directory_url . $this->name . ".php";
             $this->is_legacy_report = true;
        }
    }

}
