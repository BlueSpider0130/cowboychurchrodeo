<?php

/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreports.com/
 *
 */

namespace SRE\Engine;

/**
 * Represents all the options you need for the report that you are creating.
 *
 *  
 */
class ReportOptions {

    private $file_name = '';
    private $chkSearch = 'yes';
    private $language = SRE__DEFAULT_REPORT_LANGUAGE__;
    private $datasource = 'table';
    private $table = array();
    private $fields = array();
    private $relationships = array();
    private $sql = '';
    private $records_per_page = '10';
    private $layout = "alignleft";
    private $style_name = 'blue';
    private $title = '';
    private $header = '';
    private $footer = '';
    private $is_mobile = 'no';
    private $cells = array();
    private $labels = array();
    private $group_by = array();
    private $sort_by = array();
    private $affected_column = "";
    private $function = "";
    private $groupby_column = "";
    private $user = SRE__DEFAULT__USER__;
    private $pass = SRE__DEFAULT__PASS__;
    private $db = SRE__DEFAULT__DB__;
    private $host = SRE__DEFAULT__HOST__;
    private $filter;
    private $formatter;
    private $access_mode;
    private $report_security = Null;

    /**
     * Default Constructor 
     *
     *
     * @param string $access_mode  *The access mode you want of the generated report. The allowable access modes are : * 
      SRE_PUBLIC_REPORT : For public reports with no session checks.
      SRE_PRIVATE_REPORT : For private reports*
     * @see get_file_name()
     * @see SRE_PUBLIC_REPORT
     * @code
     * //creating a public report based on a single table with the name "items"
      use  SRE\Engine as ReportLibrary;
      Require_once("../bootstrap.php");
      try {

      $report = new ReportLibrary\ReportOptions(SRE_PUBLIC_REPORT);
      $report->select_tables(array("items"));
      $report->select_all_fields();
      $engine = new ReportLibrary\CustomEngine($report);
      $report_path = $engine->create_report();
      if ($report_path) {
      echo "Report created successfully <a href='".$report_path. "' /> clicl here </a> ";
      }

      } catch (Exception $e) {
      echo "Error Number: " . $e->getCode() . "  Error Message " . $e->getMessage();
      exit();
      }
     * 
     * @endcode
     * @param string $data_source *data* 
     * @param string $report_name *data*      
     * @return void  
     * 
     *
     *
     * 
     */
    public function __construct($access_mode = SRE_PRIVATE_REPORT, $data_source = SRE_Table, $report_name = "") {




        $this->filter = new Filter();
        $this->formatter = new ConditionalFormatting();
        if ($report_name === "")
            $report_name = time() . rand(0, 1000000);
        $this->file_name = $report_name;
        if ($data_source === SRE_SQL)
            $this->datasource = "sql";
        else
            $this->datasource = "table";

        if ($access_mode === SRE_PUBLIC_REPORT) {
            $this->access_mode = SRE_PUBLIC_REPORT;
        } else {
            $this->access_mode = SRE_PRIVATE_REPORT;
        }
    }

    /*
     * get file name
     */

    public function get_file_name() {
        return $this->file_name;
    }

    public function get_MYSQL_Connection_username() {
        return $this->user;
    }

    public function set_MYSQL_Connection_userName($user = SRE__DEFAULT__USER__) {
        $this->user = $user;
        return $this;
    }

    public function get_MYSQL_Connection_password() {
        return $this->pass;
    }

    public function set_MYSQL_Connection_password($password = SRE__DEFAULT__PASS__) {
        $this->pass = $password;
        return $this;
    }

    public function get_MYSQL_db_name() {
        return $this->db;
    }

    public function set_MYSQL_db_name($db = SRE__DEFAULT__DB__) {
        $this->db = $db;
        return $this;
    }

    public function get_MYSQL_hostname() {
        return $this->host;
    }

    public function set_MYSQL_hostname($host = SRE__DEFAULT__HOST__) {
        $this->host = $host;
        return $this;
    }

    public function get_chkSearch() {
        return $this->chkSearch;
    }

    public function set_chkSearch($allow = true) {
        if ($allow === false) {
            $this->chkSearch = "no";
        } else {
            $this->chkSearch = "yes";
        }
        return $this;
    }

    public function get_language() {
        return $this->language;
    }

    public function set_language($language) {
        if (in_array(strtolower($language), json_decode(SRE__ALLOWED_REPORT_LANGUAGES__))) {
            $this->language = strtolower($language);
        } else {
            $this->language = SRE__DEFAULT_REPORT_LANGUAGE__;
        }
        return $this;
    }

    public function get_datasource() {
        return $this->datasource;
    }

    public function set_datasource($datasource) {
        $this->datasource = $datasource;
        return $this;
    }

    public function get_table() {
        return $this->table;
    }

    public function select_tables($selected_tables) {
        $trimmed_selected_table = array_map("trim", $selected_tables);
        $this->table = $trimmed_selected_table;
        return $this;
    }

    public function get_tables_filters() {
        return $this->filter->get_tables_filters();
    }

    public function filter_between($table, $column, $first_param, $second_param, $parameters_type = SRE_NUMBER) {
        $this->filter->between($table, $column, $first_param, $second_param, $parameters_type);
        return $this;
    }

    public function filter_more($table, $column, $param, $is_or_equal = false, $parameters_type = SRE_NUMBER) {
        $this->filter->more($table, $column, $param, $is_or_equal, $parameters_type);
        return $this;
    }

    public function filter_less($table, $column, $param, $is_or_equal = false, $parameters_type = SRE_NUMBER) {
        $this->filter->less($table, $column, $param, $is_or_equal, $parameters_type);
        return $this;
    }

    public function filter_equal($table, $column, $param, $parameters_type = SRE_NUMBER) {
        $this->filter->equal($table, $column, $param, $parameters_type);
        return $this;
    }

    public function filter_not_equal($table, $column, $param, $parameters_type = SRE_NUMBER) {
        $this->filter->not_equal($table, $column, $param, $parameters_type);
        return $this;
    }

    public function filter_like($table, $column, $param) {
        $this->filter->like($table, $column, $param);
        return $this;
    }

    public function filter_not_like($table, $column, $param) {
        $this->filter->not_like($table, $column, $param);
        return $this;
    }

    public function get_fields() {
        return $this->fields;
    }

    public function select_fields($selected_fields) {
        $trimmed_selected_fields = array_map("trim", $selected_fields);
        $this->fields = $trimmed_selected_fields;
        return $this;
    }

    public function select_all_fields() {

        $this->fields = array("*");
        return $this;
    }

    public function get_relationships() {
        return $this->relationships;
    }

    public function add_relationship($parent_table, $primary_key, $child_table, $forign_key) {

        if (!empty($parent_table) && !empty($primary_key) && !empty($child_table) && !empty($forign_key)) {
            $rule = "`" . strtolower($parent_table) . "`.`" . strtolower($primary_key) . "` = `" . strtolower($child_table) . "`.`" . strtolower($forign_key) . "`";
        }
        if (!empty($rule) && !array_search($rule, $this->relationships)) {

            $this->relationships[] = $rule;
        }
    }

    public function get_sql() {
        return $this->sql;
    }

    public function set_sql($sql) {
        $this->sql = $sql;
        return $this;
    }

    public function get_records_per_page() {
        return $this->records_per_page;
    }

    public function set_records_per_page($records_number = 25) {
        $this->records_per_page = $records_number;
        return $this;
    }

    public function get_layout() {
        return $this->layout;
    }

    public function set_layout($layout) {
        $layouts = array("AlignLeft",
            "Block",
            "Stepped",
            "Outline",
            "Horizontal",
            "mobile");
        $layout = strtolower($layout) === "mobile" ? "mobile" : ucfirst(strtolower($layout));
        if (in_array($layout, $layouts))
            $this->layout = $layout;
        else
            $this->layout = _DEFAULT_LAYOUT_;
        $this->is_mobile = $this->layout === "mobile" ? "yes" : "no";
        return $this;
    }

    public function get_style_name() {
        return $this->style_name;
    }

    public function set_style_name($style_name) {
        $this->style_name = strtolower($style_name);
        return $this;
    }

    public function get_title() {
        return $this->title;
    }

    public function set_title($title) {
        $this->title = $title;
        return $this;
    }

    public function get_header() {
        return $this->header;
    }

    public function set_header($header) {
        $this->header = $header;
        return $this;
    }

    public function get_footer() {
        return $this->footer;
    }

    public function set_footer($footer) {
        $this->footer = $footer;
        return $this;
    }

    public function get_is_mobile() {
        return $this->is_mobile;
    }

    public function get_cells() {

        return $this->cells;
    }

    protected function set_cells($column, $type, $appended_text = "") {
        $allTypes = array(
            "value",
            "image",
            "stars",
            "link",
            "bit",
            "country",
            "append-r",
            "append-l"
        );
        if (!empty($column) && !empty($type) && in_array($type, $allTypes)) {

            if (!empty($appended_text) && (strstr($type, "append"))) {
                $type .= "-$appended_text";
            }
            $this->cells[$column] = $type;
        }
        return $this;
    }

    public function format_image_column($column) {
        $this->set_cells($column, "image");
        return $this;
    }

    public function format_rating_column($column) {
        $this->set_cells($column, "stars");
        return $this;
    }

    public function format_country_flag_column($column) {
        $this->set_cells($column, "country");
        return $this;
    }

    public function format_check_box_column($column) {
        $this->set_cells($column, "bit");
        return $this;
    }

    public function format_prefix_text_to_column($column, $appended_text) {
        $this->set_cells($column, "append-l", $appended_text);
        return $this;
    }

    public function format_suffix_text_to_column($column, $appended_text) {
        $this->set_cells($column, "append-r", $appended_text);
        return $this;
    }

    public function format_link_column($column) {
        $this->set_cells($column, "link");
        return $this;
    }

    public function get_conditional_formating() {
        return $this->formatter->get_rules();
    }

    public function conditional_format_between($column, $parameter1, $parameter2, $color = "#ff0000") {
        $this->formatter->add_rule($column, "between", $parameter1, $color, $parameter2);
        return $this;
    }

    public function conditional_format_more($column, $parameter1, $is_or_equal = false, $color = "#ff0000") {
        $operator = $is_or_equal === true ? "more_or_equal" : "more";
        $this->formatter->add_rule($column, $operator, $parameter1, $color);
        return $this;
    }

    public function conditional_format_less($column, $parameter1, $is_or_equal = false, $color = "#ff0000") {
        $operator = $is_or_equal === true ? "less_or_equal" : "less";
        $this->formatter->add_rule($column, $operator, $parameter1, $color);
        return $this;
    }

    public function conditional_format_equal($column, $parameter1, $color = "#ff0000") {

        $this->formatter->add_rule($column, 'equal', $parameter1, $color);
        return $this;
    }

    public function conditional_format_not_equal($column, $parameter1, $color = "#ff0000") {

        $this->formatter->add_rule($column, 'not_equal', $parameter1, $color);
        return $this;
    }

    public function conditional_format_contain($column, $contained_text, $color = "#ff0000") {

        $this->formatter->add_rule($column, "contain", $contained_text, $color);
        return $this;
    }

    public function conditional_format_begin_with($column, $beginned_with_text, $color = "#ff0000") {

        $this->formatter->add_rule($column, "begin_with", $beginned_with_text, $color);
        return $this;
    }

    public function conditional_format_end_with($column, $ended_with_text, $color = "#ff0000") {
        $this->formatter->add_rule($column, "end_with", $ended_with_text, $color);
        return $this;
    }

    public function get_labels() {

        return $this->labels;
    }

    public function label($column, $label) {
        if (!empty($column) && !empty($label) && is_string($column)) {

            $this->labels[$column] = $label;
        }
        return $this;
    }

    public function get_grouping() {

        return $this->group_by;
    }

    public function set_grouping($group_by_array) {
        $trimmed_grouped_by_array = array_map("trim", $group_by_array);
        $this->group_by = $trimmed_grouped_by_array;
        return $this;
    }

    public function get_sort_by() {

        return $this->sort_by;
    }

    public function sort_by($column, $order = 0) {
        if (!empty($column) && is_string($column)) {
            if ($order === 1)
                $temp = array($column, "1");
            else
                $temp = array($column, "0");

            if (empty($this->sort_by)) {
                $this->sort_by[0] = $temp;
            } else {
                $key = array_search($temp, $this->sort_by);
                if ($kay) {
                    $this->sort_by[$key] = $temp;
                } else {
                    $this->sort_by[] = $temp;
                }
            }
        }
        return $this;
    }

    public function security_init($login_page = SRE__DEFAULT_LOGIN_PAGE_, $logout_page = SRE__DEFAULT_LOGOUT_PAGE_, $session_name = SRE_DEFAULT_SESSION_NAME) {
        if ($this->access_mode == SRE_PUBLIC_REPORT) {
            Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["55"], 55);
            throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["55"], 55);
        } else {
            $this->report_security = new ReportSecurity($this->access_mode, $login_page, $logout_page, $session_name);
        }
        return $this;
    }

    public function security_check_session_saved_user_key($session_user_key, $check_if_numeric = false) {
        if ($this->validate_key($session_user_key, __METHOD__) && $this->validate_access_mode()) {
            if (!is_null($this->report_security))
                $this->report_security->check_session_saved_user_key($session_user_key, $check_if_numeric);
            else {
                Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 44);
                throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 44);
            }
        }
        return $this;
    }

    public function security_check_session_saved_group_key($session_group_key, $allowed_group_array) {
        if ($this->validate_key($session_group_key, __METHOD__) && $this->validate_access_mode()) {
            if (!is_null($this->report_security))
                $this->report_security->check_session_saved_group_key($session_group_key, $allowed_group_array);
            else {
                Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 45);
                throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 45);
            }
        }
        return $this;
    }

    public function security_check_session_saved_ip_key($session_ip_key) {
        if ($this->validate_key($session_ip_key, __METHOD__) && $this->validate_access_mode()) {
            if (!is_null($this->report_security))
                $this->report_security->check_session_saved_ip_key($session_ip_key);
            else {
                Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 46);
                throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 46);
            }
        }
        return $this;
    }

    public function security_check_session_saved_client_agent_key($session_client_agent_key) {
        if ($this->validate_key($session_client_agent_key, __METHOD__) && $this->validate_access_mode()) {
            if (!is_null($this->report_security))
                $this->report_security->check_session_saved_client_agent_key($session_client_agent_key);
            else {
                Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 49);
                throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 49);
            }
        }
        return $this;
    }

    public function security_check_session_saved_exact_value_key($session_key, $correct_value) {
        if ($this->validate_key($session_key, __METHOD__) && $this->validate_access_mode()) {
            if (!is_null($this->report_security))
                $this->report_security->check_session_saved_exact_value_key($session_key, $correct_value);
            else {
                Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 47);
                throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 47);
            }
        }
        return $this;
    }

    public function security_check_session_saved_exact_data_type_key($session_key, $correct_data_type) {
        if ($this->validate_key($session_key, __METHOD__) && $this->validate_access_mode()) {
            if (!is_null($this->report_security))
                $this->report_security->check_session_saved_exact_data_type_key($session_key, $correct_data_type);
            else {
                Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 48);
                throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["44"], 48);
            }
        }
        return $this;
    }

    public function get_access_mode() {
        return $this->access_mode;
    }

    public function get_report_security_options() {
        return $this->report_security;
    }

    private function validate_key($session_key, $method) {
        if (empty($session_key) || strstr($session_key, " ")) {
            Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["54"], 54);
            throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["54"] . "'  " . $method . " '", 54);
        } else
            return true;
    }

    private function validate_access_mode() {

        if ($this->access_mode == SRE_PUBLIC_REPORT) {
            Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["51"], 51);
            throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["51"], 51);
        } else
            return true;
    }

}
