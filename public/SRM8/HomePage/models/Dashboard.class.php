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

class DashBoard {

    var $all_categories = array();
    var $all_reports = array();
    var $reports_location;
    var $legacy_reports_location;

    private function get_reports() {
        $reports = array();
        $not_reports = array(
            "shared",
            "index.html",
            "index.php"
        );

        $arr = get_dir_file_info($this->reports_location);

        foreach ($arr as $report) {
            if (!in_array($report ["name"], $not_reports) && strstr($report ["name"], "rep")) {

                $url = $this->reports_location . $report ["name"] . "/";
                if (file_exists($url . "init.php") && file_exists($url . "config.php")) {
                    $report = new Report($report ["name"], $url, date('m/d/Y', $report ["date"]));
                    $reports[] = $report;
                }
            }
        }

        return $reports;
    }

    public function __construct($reports_path) {
        $this->reports_location = $reports_path;
        $this->legacy_reports_location = str_replace("Reports8", "reports", $this->reports_location);
        $this->all_reports = $this->get_reports();
        $this->all_categories = $this->get_categories();
        if ($this->is_legacy_reports()) {
            $this->all_categories[] = "**Legacy reports from a previous version";
        }
    }

    public function get_reports_count() {
        return count($this->all_reports);
    }

    public function get_categories() {

        $categories = array();

        foreach ($this->all_reports as $report) {
            if (!in_array($report->category, $categories))
                $categories [] = $report->category;
        }

        return $categories;
    }

    public function get_categories_count() {
        return count($this->all_categories);
    }

    public function get_reports_per_category($category) {
        $reports = array();

        foreach ($this->all_reports as $report) {
            if ($category == $report->category)
                $reports [] = $report;
        }

        return $reports;
    }

    public function is_report_exists($entity) {
        if (strlen($entity) > 30) {

            return false;
        }
        if (!check_is_clean($entity)) {

            return false;
        }
        foreach ($this->all_reports as $report) {

            if ($report->name == $entity && (file_exists($this->reports_location . $report->name) || file_exists($this->legacy_reports_location . $report->name))) {
                return true;
            }
        }

        return false;
    }

    public function delete_report($entity,$is_legacy = "no") {
        if ($is_legacy != "yes" && ($entity != "" || $entity != "." || $entity != "/" || $entity != "shared" || $entity != "..")) {

            delete_files($this->reports_location . $entity);
        } 
        
        elseif ($is_legacy === "yes" && ($entity != "pdf" || $entity != "index.html" || $entity != "helpers" || $entity != "" || $entity != "." || $entity != "/" || $entity != "shared" || $entity != "..")) {
            delete_files($this->legacy_reports_location . $entity);
        }else{
            return false;
        } 
    }

   

    public function is_legacy_reports() {
        $legacy_report_found = false;
        if (file_exists($this->legacy_reports_location)) {
            $not_reports = array(
                "shared",
                "index.html",
                "index.php",
                "pdf",
                "helpers",
            );
            $arr = get_dir_file_info($this->legacy_reports_location);
            foreach ($arr as $report) {
                if (!in_array($report ["name"], $not_reports) && strstr($report ["name"], "rep")) {
                    $url = $this->legacy_reports_location . $report ["name"] . "/";
                    if (file_exists($url . "config.php")) {
                        $report = new Report($report ["name"], $url, date('m/d/Y', $report ["date"]), True);
                        $this->all_reports[] = $report;
                        $legacy_report_found = true;
                    }
                }
            }
            return $legacy_report_found;
        } else {
            return false;
        }
    }
   

}
