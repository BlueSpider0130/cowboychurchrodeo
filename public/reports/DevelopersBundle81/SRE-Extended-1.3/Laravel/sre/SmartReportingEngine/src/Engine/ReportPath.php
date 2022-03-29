<?php
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */

namespace Sre\SmartReportingEngine\src\Engine;

class ReportPath
{

    //put your code here
    private $is_cli = false;
    private $http;
    private $uri;
    private $host;
    private $current_physical_path;

    public function __construct()
    {
        $this->is_cli = (php_sapi_name() === 'cli') ? true : false;
        $this->current_physical_path =  $this->get_corrected_physical_path();
    }
    // to engine/engine.php
    private function get_corrected_physical_path()
    {
        if (stristr(__DIR__, "vendor")) {
            $path_array = explode("/vendor", __DIR__);
            return  $path_array[0] . "/" . Constants::SRE__Product_DIR__ . "/" . Constants::SRE__SRE_DIR__ . "/" . Constants::SRE__Engin__DIR__;
        } else {
            return __DIR__;
        }
    }
    //i.e //i.e http://mysqlreports.com/src/Reports7/repSunglasses/repSunglasses.php

    public function get_report_url($report_name, $is_iframe = false)
    {
        $report_directory_url = $this->get_report_directory_url($report_name);
        if ($is_iframe) {
            return $report_directory_url . "Tablet" . ".php";
        } else {
            return $report_directory_url . $report_name . ".php";
        }
    }

    //i.e http://mysqlreports.com/src/Reports7/repSunglasses/

    public function get_report_directory_url($report_name)
    {
        $this->http = $this->get_http();
        $this->host = $this->get_host();
        $this->uri = $this->get_report_uri($report_name);
        return $this->http . str_replace("//", "/", ($this->host . "/" . $this->uri . "/"));
    }

    public function get_report_uri($report_name)
    {
        return $this->get_report_uri_using_document_root($report_name);
    }
    // copy begin ******************************************

    // i.e C:\xampp\htdocs\SmartReportingLibrary\src\Core/
    public function get_core_directory_physical_path()
    {
        $path = str_replace("/" . Constants::SRE__Engin__DIR__, "/" . Constants::SRE__CORE__DIR__, $this->current_physical_path);

        $path = str_replace("\\" . Constants::SRE__Engin__DIR__, "\\" . Constants::SRE__CORE__DIR__, $path);
        $path = rtrim($path, "/") . "/";

        return $path;
    }

    // i.e C:\xampp\htdocs\SmartReportingLibrary/Reports/
    public function get_report_directory_physical_path()
    {
        return public_path() . "/sre_reports/";
    }
    // copy ended  *****************************
    private function get_report_uri_using_document_root($report_name)
    {
        $fixed_part = Constants::SRE__REPORTS__DIR__ . "/" . $report_name;
        if (!$this->is_cli) {
            $physical_path_to_current_location = dirname(rtrim($this->current_physical_path, '/\\'));
            $physical_path_to_current_location = str_replace('\\', '/', $physical_path_to_current_location);
            $document_root_path = str_replace('\\', '/', $_SERVER["DOCUMENT_ROOT"]);

            $changed_part = str_replace($document_root_path ,"",str_replace('\\', '/', public_path()));
        } else {
            $dir_arr = explode("\\", $this->current_physical_path);
            $index = array_search("htdocs", $dir_arr);
            $changed_part = $dir_arr[$index + 1];
        }

        $changed_part = trim(str_ireplace(Constants::SRE__Product_DIR__ . "/" . Constants::SRE__SRE_DIR__ . "/" . Constants::SRE__Engin__DIR__, "", $changed_part), "/");
        $changed_part = trim(str_ireplace(Constants::SRE__Product_DIR__ . "/" . Constants::SRE__SRE_DIR__, "", $changed_part), "/");
        $changed_part = trim(str_ireplace(Constants::SRE__SRE_DIR__ . "/" . Constants::SRE__Engin__DIR__, "", $changed_part), "/");

        $changed_part = trim(str_ireplace("/" . Constants::SRE__SRE_DIR__, "", $changed_part), "/");

        $changed_part = trim(str_ireplace("/" . Constants::SRE__Engin__DIR__, "", $changed_part), "/");

        return $changed_part . "/" . $fixed_part;
    }

    private function get_http()
    {
        if (!$this->is_cli) {
            $http = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            return $http;
        } else {
            return "http://";
        }
    }

    private function get_host()
    {
        if (!$this->is_cli) {
            return $_SERVER['HTTP_HOST'];
        } else {
            return "localhost";
        }
    }

    // i.s /src


    private function get_request_uri()
    {
        if (!$this->is_cli) {
            return $_SERVER["REQUEST_URI"];
        } else {
            return "/SmartReportingLibrary/src/Engine/";
        }
    }
}
