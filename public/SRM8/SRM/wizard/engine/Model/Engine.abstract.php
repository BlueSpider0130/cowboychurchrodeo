<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");

abstract class Engine {

    private $_file_name = '';
    private $_report_path = "";
    private $_category = "";
    protected $_reports_directory;
    private $_date_created = '';
    private $_maintainance_email = '';
    private $_images_path = 'images/';
    private $_headers_output_escaping = "Yes";
    private $_default_page_size = "A3";
    private $_output_escaping = "Yes";
    private $_thumnail_max_width = '40';
    private $_thumnail_max_height = '50';
    private $_show_real_size_image = '';
    private $_show_realsize_in_popup = '1';
    private $_chkSearch = 'yes';
    private $_language = "en";
    private $_db_extension = 'pdo';
    private $_datasource = 'table';
    private $_table = array();
    private $_tables_filters = array();
    private $_fields = array();
    private $_relationships = array();
    private $_sql = '';
    private $_fields2 = array();
    private $_records_per_page = '10';
    private $_layout = "AlignLeft";
    private $_style_name = 'blue';
    private $_title = '';
    private $_header = '';
    private $_footer = '';
    private $_allow_only_admin = "yes";
    private $_is_public_access = "no";
    private $_sec_Username = '';
    private $_sec_pass = '';
    private $_security = '';
    private $_sec_email = '';
    private $_members = '';
    private $_sec_table = '';
    private $_sec_Username_Field = '';
    private $_sec_pass_Field = '';
    private $_sec_email_field = "";
    private $_sec_pass_hash_type = '';
    private $_Forget_password = '';
    private $_is_mobile = '';
    private $_cells = array();
    private $_conditional_formating = array();
    private $_labels = "";
    private $_group_by = array();
    private $_sort_by = array();
    private $statestical_affected_column = "";
    private $statestical_function = "";
    private $statestical__groupby_column = "";
    private $connection_db_user = "";
    private $connection_db_pass = "";
    private $connection_db_host = "";
    private $connection_db_name = "";
    private $is_template = "";
    private $save_template_name = "";
    private $_filters_grouping = '';
    private $_sub_totals = array();
    private $_sub_totals_enabled = "";
    abstract protected function validate_request();

    abstract protected function clear_sessions();



    /*
     * RecursiveMkdir
     *
     * Creates the report folder
     *
     * @param (path) path of the report directory
     */

    protected function RecursiveMkdir($path) {
        if (!file_exists($path)) {
            $this->RecursiveMkdir(dirname($path));
            if (mkdir($path, 0755)) {
                return true;
            } else {
                throw new Exception("Error 603: Could not create directory $path, permission denied");
            }
        }
    }

    /**
     * create_report_structure()
     * This function copy necessary files to create the report
     *    * 
     */
    protected function create_report_structure() {
      //  copy("Core/images", $this->_report_path . "/images/");
        copy("Core/auto_load.php", $this->_report_path . "/auto_load.php");
        copy("Core/ChangeLayout.php", $this->_report_path . "/ChangeLayout.php");
        copy("Core/ChangeStyle.php", $this->_report_path . "/ChangeStyle.php");
      //  copy("Core/Detailed-view.php", $this->_report_path . "/Detailed-view.php");
        copy("Core/email_report.php", $this->_report_path . "/email_report.php");
        copy("Core/Forgot_password.php", $this->_report_path . "/Forgot_password.php");
        copy("Core/index.html", $this->_report_path . "/index.html");
        copy("Core/login.php", $this->_report_path . "/login.php");
        copy("Core/logout.php", $this->_report_path . "/logout.php");
        copy("Core/Mobile.php", $this->_report_path . "/Mobile.php");
        copy("Core/Mobile_Detect.php", $this->_report_path . "/Mobile_Detect.php");
        copy("Core/report_index.php", $this->_report_path . "/" . $this->_file_name . ".php");
        copy("Core/request.php", $this->_report_path . "/request.php");
        copy("Core/Tablet.php", $this->_report_path . "/Tablet.php");
        copy("Core/filter.php", $this->_report_path . "/filter.php");

    }

    /**
     * create_init_file
     * This function creates the init file which stores the connection to the database
     * 
     */
    protected function create_init_file() {
        $fp = fopen($this->_report_path . "/init.php", "w+");
        if ($fp) {
            if (fwrite($fp, '<?php' . PHP_EOL)) {
                fwrite($fp, 'if (! defined("DIRECTACESS")) exit("No direct script access allowed"); ' . PHP_EOL);
                fwrite($fp, '$DB_HOST = "' . $this->connection_db_host . '";' . PHP_EOL);
                fwrite($fp, '$DB_USER = "' . $this->connection_db_user . '";' . PHP_EOL);
                fwrite($fp, '$DB_PASSWORD = decode( "' . $this->connection_db_pass . '");' . PHP_EOL);
                fwrite($fp, '$DB_NAME = "' . $this->connection_db_name . '";' . PHP_EOL);
                fclose($fp);
            } else {
                throw new Exception('Error 606: can not write in the init configuration file of the generated report, permission denied. Please make sure to give 755 permissions to the following directory' . PHP_EOL);
            }
        } else {
            throw new Exception('Error 607: can not create the init configuration file of the generated report, permission denied. Please make sure to give 755 permissions to the following directory' . PHP_EOL);
        }
    }

    /**
     * create_report_config
     * 
     * This function creates the config file of the report
     * 
     */
    protected function create_report_config() {
        $fp = fopen($this->_report_path . "/config.php", "w+");
        if ($fp) {
            if (fwrite($fp, '<?php' . PHP_EOL)) {

                if ($this->_title == "") {
                    fwrite($fp, "//Untitled Report," . $this->_date_created . PHP_EOL);
                } else {
                    fwrite($fp, "//" . $this->_title . "," . $this->_date_created . PHP_EOL);
                }
                fwrite($fp, 'if (! defined("DIRECTACESS")) exit("No direct script access allowed"); ' . PHP_EOL);

                fwrite($fp, '$file_name = "' . $this->_file_name . '";' . PHP_EOL);
                $this->write_customization_settings($fp);
                $this->write_wizard_settings($fp);
                fclose($fp);
            } else {
                throw new Exception('Error 604: can write in the configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the following directory' . PHP_EOL);
            }
        } else {
            throw new Exception('Error 605: can not create the configuration file of the generated report, permission denied! Please make sure to give 755 permissions to the following directory' . PHP_EOL);
        }
    }

    /**
     * write_customization_settings
     * This private function is called by the create_report_config() and write the customization settings
     * @param type $fp is a refrence to the config file
     */
    private function write_customization_settings($fp) {
        fwrite($fp, '//  customization settings' . PHP_EOL);

        fwrite($fp, '$template_title = "' . $this->save_template_name . '";' . PHP_EOL);
        fwrite($fp, '$category = "' . $this->_category . '";' . PHP_EOL);
        fwrite($fp, '$date_created = "' . $this->_date_created . '";' . PHP_EOL);
        fwrite($fp, '$maintainance_email = "";' . PHP_EOL);
        fwrite($fp, '$images_path = "' . $this->_images_path . '";' . PHP_EOL);
        fwrite($fp, '$headers_output_escaping = "' . $this->_headers_output_escaping . '";' . PHP_EOL);
        fwrite($fp, '$default_page_size = "' . $this->_default_page_size . '";' . PHP_EOL);
        fwrite($fp, '$output_escaping = "' . $this->_output_escaping . '";' . PHP_EOL);
        fwrite($fp, '$thumnail_max_width = "' . $this->_thumnail_max_width . '";' . PHP_EOL);
        fwrite($fp, '$thumnail_max_height = "' . $this->_thumnail_max_height . '";' . PHP_EOL);
        fwrite($fp, '$show_real_size_image = "' . $this->_show_real_size_image . '";' . PHP_EOL);
        fwrite($fp, '$show_realsize_in_popup = "' . $this->_show_realsize_in_popup . '";' . PHP_EOL);
        fwrite($fp, '$chkSearch = "' . $this->_chkSearch . '";' . PHP_EOL);
    }

    /**
     * process_filter_array
     * This function remove any redunduncies from the filter array, it's called only when the filter array is not empty
     */
    protected function process_filter_array() {
        $params = array();
        $sql = array();
        $types = array();
        foreach ($this->_tables_filters as $key => $filter) {
            if (in_array($filter["sql"], $sql) && in_array($filter["param"], $params) && in_array($filter["type"], $types)) {
                //remove this element from the array
                unset($this->_tables_filters[$key]);
            } else {
                $sql[] = $filter["sql"];
                $params[] = $filter["param"];
                $types[] = $filter["type"];
            }
        }
    }

    /**
     * This function is called by the create_report_config() to write the apperance and wizard settings
     * @param type $fp : refrence to the config file
     */
    private function write_wizard_settings($fp) {
        fwrite($fp, '//  wizard settings' . PHP_EOL);
        fwrite($fp, '$language = "' . $this->_language . '";' . PHP_EOL);
        fwrite($fp, '$db_extension = "' . strtolower($this->_db_extension) . '";' . PHP_EOL);
        fwrite($fp, '$datasource = "' . $this->_datasource . '";' . PHP_EOL);
        if ($this->_datasource == "sql") {
            fwrite($fp, '$sql = "' . str_replace('"', "'", $this->_sql) . '";' . PHP_EOL);
            fwrite($fp, '$table = array();' . PHP_EOL);
            fwrite($fp, '$tables_filters = array();' . PHP_EOL);
            fwrite($fp, '$relationships = array();' . PHP_EOL);
        } else {
            fwrite($fp, '$sql = "";' . PHP_EOL);
            fwrite($fp, '$table = ' . $this->serialize_array($this->_table) . ';' . PHP_EOL);
            fwrite($fp, '$tables_filters = ' . $this->serialize_array($this->_tables_filters) . ';' . PHP_EOL);
            fwrite($fp, '$relationships = ' . $this->serialize_array($this->_relationships) . ';' . PHP_EOL);

            fwrite($fp, '$affected_column = "' . $this->statestical_affected_column . '";' . PHP_EOL);
            fwrite($fp, '$function = "' . $this->statestical_function . '";' . PHP_EOL);
            fwrite($fp, '$groupby_column = "' . $this->statestical__groupby_column . '";' . PHP_EOL);
        }
        fwrite($fp, '$labels = ' . $this->serialize_array($this->_labels) . ';' . PHP_EOL);
        fwrite($fp, '$cells = ' . $this->serialize_array($this->_cells) . ';' . PHP_EOL);
        fwrite($fp, '$conditional_formating = ' . $this->serialize_array($this->_conditional_formating) . ';' . PHP_EOL);
        fwrite($fp, '$fields = ' . $this->serialize_array($this->_fields) . ';' . PHP_EOL);
        fwrite($fp, '$fields2 = ' . $this->serialize_array($this->_fields) . ';' . PHP_EOL);
        fwrite($fp, '$group_by = ' . $this->serialize_array($this->_group_by) . ';' . PHP_EOL);
        fwrite($fp, '$sort_by = ' . $this->serialize_array($this->_sort_by) . ';' . PHP_EOL);
        // apperance and security
        fwrite($fp, '$records_per_page = "' . $this->_records_per_page . '";' . PHP_EOL);
        fwrite($fp, '$layout = "' . $this->_layout . '";' . PHP_EOL);
        fwrite($fp, '$style_name = "' . $this->_style_name . '";' . PHP_EOL);
        fwrite($fp, '$title = "' . $this->_title . '";' . PHP_EOL);
        fwrite($fp, '$header = "' . $this->_header . '";' . PHP_EOL);
        fwrite($fp, '$footer = "' . $this->_footer . '";' . PHP_EOL);
        fwrite($fp, '$allow_only_admin = "' . $this->_allow_only_admin . '";' . PHP_EOL);
        fwrite($fp, '$sec_Username = "' . $this->_sec_Username . '";' . PHP_EOL);
        fwrite($fp, '$sec_pass = "' . $this->_sec_pass . '";' . PHP_EOL);
        fwrite($fp, '$security = "' . $this->_security . '";' . PHP_EOL);
        fwrite($fp, '$is_public_access = "' . $this->_is_public_access . '";' . PHP_EOL);
        fwrite($fp, '$sec_email = "' . $this->_sec_email . '";' . PHP_EOL);
        fwrite($fp, '$members = "' . $this->_members . '";' . PHP_EOL);
        fwrite($fp, '$sec_table = "' . $this->_sec_table . '";' . PHP_EOL);
        fwrite($fp, '$sec_Username_Field = "' . $this->_sec_Username_Field . '";' . PHP_EOL);
        fwrite($fp, '$sec_pass_Field = "' . $this->_sec_pass_Field . '";' . PHP_EOL);
        fwrite($fp, '$sec_email_field = "' . $this->_sec_email_field . '";' . PHP_EOL);
        fwrite($fp, '$sec_pass_hash_type = "' . $this->_sec_pass_hash_type . '";' . PHP_EOL);
        fwrite($fp, '$Forget_password = "' . $this->_Forget_password . '";' . PHP_EOL);
        fwrite($fp, '$is_mobile = "' . $this->_is_mobile . '";' . PHP_EOL);
        fwrite($fp, '$sub_totals_enabled = "' . $this->_sub_totals_enabled . '";' . PHP_EOL);
        fwrite($fp, '$filters_grouping = "' . $this->_filters_grouping . '";' . PHP_EOL);       
        fwrite($fp, '$sub_totals = ' . $this->serialize_array($this->_sub_totals) . ';' . PHP_EOL);
    }

    /**
     * This function converts the cells array from the session formats to the report config formats
     * @param type $cells is the cells array as stored in the session
     * @return type the $cells array as it should be stored in the config
     */
    private function process_cells_settings($cells) {

        $arr = Array();
        if(!is_array($cells) || empty($cells)) return array();

        foreach ($cells as $cell) {

            foreach ($cell as $k => $v) {
                if ($k == "column") {
                    if (strstr($cell ["cellType"], "append")) {
                        $arr [$v] = $cell ["cellType"] . "-" . $cell ["appendedText"];
                    } else {
                        $arr [$v] = $cell ["cellType"];
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * This function is called if aggregation functions are used . it affects the fields, sort by and group by arrays
     */
    protected function handle_aggregation_functions() {
        if (isset($this->statestical_affected_column) && $this->statestical_affected_column != "") {

            $new_flds = array();
            foreach ($this->_fields as $f) {
                // if function is not already set
                if ($f === $this->statestical_affected_column && !strstr($f, $this->statestical_function))
                    $new_flds [] = $this->statestical_function . "(`$f`)";
                else
                    $new_flds [] = $f;
            }
            $this->_fields = $new_flds;

            $new_group = array();
            foreach ($this->_group_by as $g) {
                if ($g !== $this->statestical_affected_column)
                    $new_group [] = $g;
            }
            $this->_group_by = $new_group;

            $new_sort = array();
            foreach ($this->_sort_by as $arr) {
                if ($arr [0] !== $this->statestical_affected_column)
                    $new_sort [] = $arr;
            }
            $this->_sort_by = $new_sort;
        }
    }

    /**
     * This function create a way to write array variables in the cnfig file of reports
     * @param teh array in a session variable
     * @return type string to be written in the config file
     */
    private function serialize_array($arr) {

        $str = "array(";

        foreach ($arr as $k => $v) {
            //case two dimensional array where 2nd level is not associative
            //case second dimension where 2nd level is associative
            if (is_array($v)) {
                $str .= PHP_EOL . '      "' . $k . '" => ' . $this->serialize_array($v) . ",";
            }
            //case one dimensional associative array
            else {
                $str .=PHP_EOL . '"' . $k . '" => "' . str_replace('"', "'", $v) . '",';
            }
        }

        $str .= ")";
        $str = str_replace(",)", ")", $str);

        return $str;
    }

    /**
     * 
     * @return typecheck if an array is associative or not
     */
    private function is_associative_array() {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * This is the template method that creates the report if called by an object of a subclass
     */
    final public function run() {
        try {
            $this->validate_request(); //done      
            $this->RecursiveMkdir($this->_report_path); //done           
            $this->create_report_structure(); //done
            if ($this->_datasource == "table" && $this->statestical_affected_column != "")
                $this->handle_aggregation_functions(); //done
            if ($this->_datasource == "table" && $this->_tables_filters != array()) {
                $this->process_filter_array();
            }
            $this->create_init_file(); //done
            $this->create_report_config(); //done
            $this->clear_sessions();
            if (strtolower($this->_layout) != "mobile") {
                header("Location: " . $this->_report_path . "/" . $this->_file_name . ".php");
            } else {
                header("Location: " . $this->_report_path . "/" . "Tablet.php");
            }
            return true;
        } catch (Exception $e) {
            echo 'Exception: ', $e->getMessage();
            exit();
        }
    }

    public function set_file_name($_file_name) {
        $_file_name = "rep" . $_file_name;
        $_file_name = str_replace(" ", "", $_file_name);
        $this->_file_name = str_replace(".php", "", $_file_name);
        $this->_report_path = $this->_reports_directory . $this->_file_name;
    }

    public function set_filters_grouping($_filters_grouping) {
        $this->_filters_grouping = $_filters_grouping;
    }
    public function set_sub_totals($_sub_totals) {
        $this->_sub_totals = $_sub_totals;
    }
    public function set_sub_totals_enabled($_sub_totals_enabled) {
        $this->_sub_totals_enabled = $_sub_totals_enabled;
    }

    public function set_category($_category) {
        $this->_category = $_category;
    }

    public function set_date_created($_date_created) {
        $this->_date_created = $_date_created;
    }

    public function set_maintainance_email($_maintainance_email) {
        $this->_maintainance_email = $_maintainance_email;
    }

    public function set_images_path($_images_path) {
        $this->_images_path = $_images_path;
    }

    public function set_headers_output_escaping($_headers_output_escaping) {
        $this->_headers_output_escaping = $_headers_output_escaping;
    }

    public function set_default_page_size($_default_page_size) {
        $this->_default_page_size = $_default_page_size;
    }

    public function set_output_escaping($_output_escaping) {
        $this->_output_escaping = $_output_escaping;
    }

    public function set_thumnail_max_width($_thumnail_max_width) {
        $this->_thumnail_max_width = $_thumnail_max_width;
    }

    public function set_thumnail_max_height($_thumnail_max_height) {
        $this->_thumnail_max_height = $_thumnail_max_height;
    }

    public function set_show_real_size_image($_show_real_size_image) {
        $this->_show_real_size_image = $_show_real_size_image;
    }

    public function set_show_realsize_in_popup($_show_realsize_in_popup) {
        $this->_show_realsize_in_popup = $_show_realsize_in_popup;
    }

    public function set_chkSearch($_chkSearch) {
        $this->_chkSearch = $_chkSearch;
    }

    public function set_language($_language) {
        $this->_language = $_language;
    }

    public function set_db_extension($_db_extension) {
        $this->_db_extension = $_db_extension;
    }

    public function set_datasource($_datasource) {
        $this->_datasource = $_datasource;
    }

    public function set_table($_table) {
        $this->_table = $_table;
    }

    public function set_tables_filters($_tables_filters) {
        $this->_tables_filters = $_tables_filters;
    }

    public function set_fields($_fields) {
        $this->_fields = $_fields;
    }

    public function set_relationships($_relationships) {
        $this->_relationships = $_relationships;
    }

    public function set_sql($_sql) {
        $this->_sql = $_sql;
    }

    public function set_fields2($_fields2) {
        $this->_fields2 = $_fields2;
    }

    public function set_records_per_page($_records_per_page) {
        if (!is_int($_records_per_page) || $_records_per_page < 1)
            $_records_per_page = 10;
        $this->_records_per_page = (int) $_records_per_page;
    }

    public function set_layout($_layout) {
        
        if (strtolower($_layout) == "alignleft" || $_layout == "AlignLeft")
            $this->_layout = "AlignLeft";
        elseif (strtolower($_layout) == "block" || $_layout == "Block")
            $this->_layout = "Block";
        elseif (strtolower($_layout) == "stepped" || $_layout == "Stepped")
            $this->_layout = "Stepped";
        elseif (strtolower($_layout) == "outline" || $_layout == "Outline")
            $this->_layout = "Outline";
        elseif (strtolower($_layout) == "horizontal" || $_layout == "Horizontal")
           $this->_layout = "Horizontal";
        elseif (strtolower($_layout) == "mobile" || $_layout == "Mobile") {
            $this->_layout = "mobile";
        } else
            $this->_layout = "AlignLeft";
    }

    public function set_style_name($_style_name) {
        $all_styles = array (
		"blue",
		"grey",
		"default",
                 "mobile"
);
        if(in_array($_style_name,$all_styles))
        $this->_style_name = $_style_name;
        elseif(strtolower($this->_layout)=="mobile"){
            $this->_style_name = "mobile"; 
        }else{
         $this->_style_name = "blue";   
        }
    }

    public function set_title($_title) {
        $this->_title = $_title;
    }

    public function set_header($_header) {
        $this->_header = $_header;
    }

    public function set_footer($_footer) {
        $this->_footer = $_footer;
    }

    public function set_allow_only_admin($_allow_only_admin) {
        $this->_allow_only_admin = $_allow_only_admin;
    }

    public function set_is_public_access($_is_public_access) {
        $this->_is_public_access = $_is_public_access;
    }

    public function set_sec_Username($_sec_Username) {
        $this->_sec_Username = $_sec_Username;
    }

    public function set_sec_pass($_sec_pass) {
        $this->_sec_pass = $_sec_pass;
    }

    public function set_security($_security) {
        $this->_security = $_security;
    }

    public function set_sec_email($_sec_email) {
        $this->_sec_email = $_sec_email;
    }

    public function set_members($_members) {
        $this->_members = $_members;
    }

    public function set_sec_table($_sec_table) {
        $this->_sec_table = $_sec_table;
    }

    public function set_sec_Username_Field($_sec_Username_Field) {
        $this->_sec_Username_Field = $_sec_Username_Field;
    }

    public function set_sec_pass_Field($_sec_pass_Field) {
        $this->_sec_pass_Field = $_sec_pass_Field;
    }

    public function set_sec_email_field($_sec_email_field) {
        $this->_sec_email_field = $_sec_email_field;
    }

    public function set_sec_pass_hash_type($_sec_pass_hash_type) {
        $this->_sec_pass_hash_type = $_sec_pass_hash_type;
    }

    public function set_Forget_password($_Forget_password) {
        $this->_Forget_password = $_Forget_password;
    }

    public function set_is_mobile($_is_mobile) {
        $this->_is_mobile = $_is_mobile;
    }

    public function set_cells($_cells) {

        $this->_cells = $this->process_cells_settings($_cells);
    }

    public function set_conditional_formating($_conditional_formating) {
        $this->_conditional_formating = $_conditional_formating;
    }

    public function set_labels($_labels) {
        $this->_labels = $_labels;
    }

    public function set_group_by($_group_by) {
        $this->_group_by = $_group_by;
    }

    public function set_sort_by($_sort_by) {
        $this->_sort_by = $_sort_by;
    }

    public function set_connection_user($_db_user) {
        $this->connection_db_user = $_db_user;
    }

    public function set_connection_pass($_db_pass) {
        $this->connection_db_pass = $_db_pass;
    }

    public function set_connection_db_name($_db_name) {
        $this->connection_db_name = $_db_name;
    }

    public function set_connection_host($_db_host) {
        $this->connection_db_host = $_db_host;
    }

    public function set_Statestical_affected_column($statestical_affected_column) {

        $this->statestical_affected_column = $statestical_affected_column;
    }

    public function set_Statestical_function($statestical_function) {
        $this->statestical_function = $statestical_function;
    }

    public function set_Statestical__groupby_column($statestical__groupby_column) {
        $this->statestical__groupby_column = $statestical__groupby_column;
    }

    public function set_Is_template($is_template) {
        $this->is_template = $is_template;
    }

    public function set_Save_template_name($save_template_name) {
        $this->save_template_name = $save_template_name;
    }

}
