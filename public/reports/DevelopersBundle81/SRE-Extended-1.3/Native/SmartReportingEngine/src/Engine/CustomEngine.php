<?php

/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */

namespace SRE\Engine;


class CustomEngine extends Engine {

    private $ReportOptions;

    public function __construct($ReportOptions) {
        $this->_obj_report_path = new \SRE\Engine\ReportPath();
        $this->_reports_parent_directory_physical_path = $this->_obj_report_path->get_report_directory_physical_path();
        $this->ReportOptions = $ReportOptions;
        $this->set_file_name($this->ReportOptions->get_file_name());
        $this->_generated_report_directory_physical_path = $this->_reports_parent_directory_physical_path . $this->_file_name . "/";

        if ($this->ReportOptions->get_layout() == 'Mobile') {
            $this->ReportOptions->set_is_mobile(true);
        }
        $this->set_connection_user($this->ReportOptions->get_MYSQL_Connection_username());
        $this->set_connection_pass($this->ReportOptions->get_MYSQL_Connection_password());
        $this->set_connection_db_name($this->ReportOptions->get_MYSQL_db_name());
        $this->set_connection_host($this->ReportOptions->get_MYSQL_hostname());

        $this->set_category("Dynamic Report by the API");
        $this->set_date_created(date("F j, Y, g:i a"));
        $this->set_language($this->ReportOptions->get_language());
        if (extension_loaded("pdo"))
            $this->set_db_extension("pdo");
        else
            $this->set_db_extension("mysqli");
        $this->set_fields($this->ReportOptions->get_fields());
        $this->set_fields2($this->ReportOptions->get_fields());
        $this->set_records_per_page($this->ReportOptions->get_records_per_page());
        $this->set_layout($this->ReportOptions->get_layout());
        $this->set_style_name($this->ReportOptions->get_style_name());
        $this->set_title($this->ReportOptions->get_title());
        $this->set_header($this->ReportOptions->get_header());
        $this->set_footer($this->ReportOptions->get_footer());
        $this->_access_mode = $this->ReportOptions->get_access_mode();
        $report_security = $this->ReportOptions->get_report_security_options();
        if (!is_null($report_security)) {
            $this->_login_page = $report_security->get_Login_page();
            $this->_log_out_page = $report_security->get_Logout_page();
            $this->_session_name = $report_security->get_session_name();
            $this->_session_validation_login_keys = $report_security->get_session_validation_login_keys();
        }


        $this->set_is_mobile($this->ReportOptions->get_is_mobile());
        $this->set_cells($this->ReportOptions->get_cells());
        $this->set_conditional_formating($this->ReportOptions->get_conditional_formating());
        $this->set_labels($this->ReportOptions->get_labels());
        $this->set_group_by($this->ReportOptions->get_grouping());
        $this->set_datasource($this->ReportOptions->get_datasource());
        $this->set_sort_by($this->ReportOptions->get_sort_by());
        if (strtolower($this->ReportOptions->get_datasource()) == "sql") {
            $this->set_sql($this->ReportOptions->get_sql());
            $this->set_chkSearch("no");
        } else {
            $this->set_table($this->ReportOptions->get_table());
            $this->set_tables_filters($this->ReportOptions->get_tables_filters());
            $this->set_relationships($this->ReportOptions->get_relationships());
            $this->set_chkSearch($this->ReportOptions->get_chkSearch());
        }
    }

    //! @cond
    public function get_access_mode() {
        return $this->_access_mode;
    }

    public function get_login_page() {
        return $this->_login_page;
    }

    public function get_logout_page() {
        return $this->_log_out_page;
    }

    public function get_session_validation_rules() {
        return $this->_session_validation_login_keys;
    }

    public function get_session_name() {
        return $this->_session_name;
    }

}

?>