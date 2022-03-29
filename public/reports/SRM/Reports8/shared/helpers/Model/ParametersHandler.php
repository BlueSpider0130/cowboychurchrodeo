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

class ParametersHandler {

    private $_report_filters = array();  //static and dynamic filters
    private $_reports_params = array();  //only dynamic filters
    private $_params_count = 0;
    public $_posted_params_valied = array();
    Private $_key_to_detect_dinamic_params = "-param";
    private $_key_to_detect_params_value_in_post = "posted_params_";
    private $_ignore_missing_filters = true;
	private $param_session_key;

    public function __Construct($_tables_filters,$report_name) {
		$this->param_session_key = 'dynamic_param'.$report_name;
        $this->_report_filters = $_tables_filters;
        $this->read_params();
        $this->_params_counts = count($this->_reports_params);
    }

    public function get_ignore_missing_filters() {
        return $this->_ignore_missing_filters;
    }

    public function set_ignore_missing_filters($ignore = false) {
        $this->_ignore_missing_filters = $ignore;
    }

//read filters array and populate reports param with only dynamic filters 
    private function read_params() {
        if (is_array($this->_report_filters)) {
            foreach ($this->_report_filters as $k => $v) {
                if (is_array($v)) {
                    if (is_array($v["param"]) && stristr($v["param"][0], $this->_key_to_detect_dinamic_params)) {
                        $this->_reports_params[$k] = $v;
                        $this->_reports_params[$k] ["view_name"] = $this->get_view_name($this->_reports_params[$k]["sql"]);
                        $this->_reports_params[$k] ["operator"] = $this->get_operator($this->_reports_params[$k]["sql"]);
                        $this->_reports_params[$k] ["data_type"] = str_replace($this->_key_to_detect_dinamic_params, "", $this->_reports_params[$k]["param"][0]);
                    } else {
                        if (stristr($v["param"], $this->_key_to_detect_dinamic_params)) {
                            $this->_reports_params[$k] = $v;
                            $this->_reports_params[$k] ["view_name"] = $this->get_view_name($this->_reports_params[$k]["sql"]);
                            $this->_reports_params[$k] ["operator"] = $this->get_operator($this->_reports_params[$k]["sql"]);
                            $this->_reports_params[$k] ["data_type"] = str_replace($this->_key_to_detect_dinamic_params, "", $this->_reports_params[$k]["param"]);
                        }
                    }
                }
            }
        }
    }

//read filters array  and decide if there are dynamic reports
    public function is_parameter_report() {
        if ($this->_params_counts > 0)
            return true;
        else
            return false;
    }

//check the cleaned array if there is an array of posted_params with keys for at least one filter 
    public function is_posted_param($all_posted) {

        foreach ($all_posted as $key => $value) {
            if (stristr($key, $this->_key_to_detect_params_value_in_post)) {
                return true;
            }
        }
        return false;
    }

// validate posted params to this report (if any) and see if they match expected scheme (data types, number less than filters , keys)
// returns 1 if success and error message if failed not to revalidate 
    public function validate_posted_report_param($all_posted) {
        $_result = array();
        $_parameter_data_type = "";
        if (is_array($all_posted)) {
            foreach ($this->_reports_params as $k => $v) {
                $_temp = 0;

                ////////////////////
                if (is_array($v)) {
                    if (is_array($v["param"])) {
                        $_posted_params = 0; //number of dynamic params who are posted by users


                        for ($i = 0; $i < count($v["param"]); $i++) {


                            if ($this->check_is_post_sent($all_posted[$this->_key_to_detect_params_value_in_post . $k . "_" . $i])) {
                                $_posted_params++;
                                $_parameter_data_type = str_replace($this->_key_to_detect_dinamic_params, "", $v["param"][$i]);
                                $_temp = $this->validate_param_value($all_posted[$this->_key_to_detect_params_value_in_post . $k . "_" . $i], $_parameter_data_type);
                                if ($_temp !== 1) {
                                    $_result[] = $_temp;
                                }
                            } else {
                                if (!$this->_ignore_missing_filters)
                                //if any parameters dosn't exist
                                    $_result[] = $this->_reports_params[$k] ['view_name'] . "  parameter Can't be Empty.";
                            }
                        }
                        if ($_posted_params === 1 && $this->_ignore_missing_filters) {
                            //two params required and only one is entered
                            $_result[] = $this->_reports_params[$k] ['view_name'] . " has a missing parameter";
                        }
                    } else {
                        echo((($all_posted[$this->_key_to_detect_params_value_in_post . $k . "_0"])));
                        if ($this->check_is_post_sent($all_posted[$this->_key_to_detect_params_value_in_post . $k . "_0"])) {//&& !empty($all_posted[$this->_key_to_detect_params_value_in_post . $k . "_0"])) {
                            $_parameter_data_type = str_replace($this->_key_to_detect_dinamic_params, "", $v["param"]);
                            $_temp = $this->validate_param_value($all_posted[$this->_key_to_detect_params_value_in_post . $k . "_0"], $_parameter_data_type);
                            if ($_temp !== 1) {
                                $_result[] = $_temp;
                            }
                        } else {
                            if (!$this->_ignore_missing_filters) {
                                $_result[] = $this->_reports_params[$k] ['view_name'] . "  parameter Can't be Empty ";
                            }
                        }
                    }
                }
            }
        }


        if (count($_result) === 0) {
            return 1;
        } else {
            return $_result;
        }
    }

    private function validate_param_value($param_value, $data_Type) {
        switch (strtolower($data_Type)) {
            case 'd':
                if (!empty($param_value) && $this->validate_date($param_value))
                    return 1;
                else {
                    return $param_value . " is invalid Date";
                }
                break;
            case 'n':
                if (is_numeric($param_value))
                    return 1;
                else {
                    return $param_value . " is invalid Number";
                }
                break;
            case 't':
                if (is_string($param_value) && !empty($param_value))
                    return 1;
                else {
                    return $param_value . " is invalid Text";
                }
                break;

            default:
                return "There is no Such Data Type";
        }
    }

    private function validate_date($str) {

        $stamp = strtotime($str);
        if (!is_numeric($stamp)) {
            return false;
        }
        $month = date('m', $stamp);
        $day = date('d', $stamp);
        $year = date('Y', $stamp);
        if (checkdate($month, $day, $year)) {
            return true;
        } else {
            return false;
        }
    }

    public function set_valid_posted_params($all_posted) {


        if (is_array($all_posted)) {
            foreach ($all_posted as $k => $v) {
                if (stristr($k, $this->_key_to_detect_params_value_in_post)) {
                    $this->_posted_params_valied[$k] = $v;
                }
            }
        }
    }

//create the dynamic_param in session and populate it 
    public function save_params_in_session() {
        $_SESSION[$this->param_session_key] = $this->_posted_params_valied;
    }

    public function get_params_from_session() {
        if (isset($_SESSION[$this->param_session_key]) && is_array($_SESSION[$this->param_session_key]))
            return $_SESSION[$this->param_session_key];
        else
            return array();
    }

//parase the session array check if there is an array called dynamic_param ín sessions exists and not empty with all params in filters array as filtername_n_param 
    public function is_params_in_session() {

        if (isset($_SESSION[$this->param_session_key]) && !empty($_SESSION[$this->param_session_key]) && is_array($_SESSION[$this->param_session_key])) {

            return true;
        } else {
            return false;
        }
    }

    public function Delete_params_From_session() {
        unset($_SESSION[$this->param_session_key]);
    }

    public function add_dynamic_params_to_filters_array() {
        $_result = array();
        if (is_array($this->_report_filters)) {
            foreach ($this->_report_filters as $k => $v) {
                $_result[$k] = $v;
                if (isset($_result[$k]["view_name"])) {
                    unset($_result[$k]["view_name"]);
                }
                if (isset($_result[$k]["operator"])) {
                    unset($_result[$k]["operator"]);
                }
                if (isset($_result[$k]["data_type"])) {
                    unset($_result[$k]["data_type"]);
                }
                if (isset($this->_reports_params[$k])) {
                    if (is_array($v)) {
                        if (is_array($v["param"])) {


                            //$_result[$k]["param"] = array();
                            for ($i = 0; $i < count($v["param"]); $i++) {
                                if ($this->check_is_post_sent($this->_posted_params_valied[$this->_key_to_detect_params_value_in_post . $k . "_" . $i])) {

                                    $_result[$k]["param"] [$i] = $this->_posted_params_valied[$this->_key_to_detect_params_value_in_post . $k . "_" . $i];
                                } else {

                                    if ($this->_ignore_missing_filters) {
                                        unset($_result[$k]);
                                        break;
                                    }
                                }
                            }
                        } else {
                            if ($this->check_is_post_sent($this->_posted_params_valied[$this->_key_to_detect_params_value_in_post . $k . "_0"])) {
                                $_result[$k]["param"] = $this->_posted_params_valied[$this->_key_to_detect_params_value_in_post . $k . "_0"];
                            } else {

                                if ($this->_ignore_missing_filters)
                                    unset($_result[$k]);
                            }
                        }
                    }
                }
            }
            return $_result; //$this->_report_filters;
        }
    }

    private function check_is_post_sent($_postedvalue) {
        if (isset($_postedvalue) && trim($_postedvalue) !== "") {
            return true;
        } else {
            return false;
        }
    }

//will be called in the view **
    public function get_reports_params() {
        return $this->_reports_params;
    }

//will be called in the view **
    public function get_params_count() {
        return $this->_params_count;
    }

    private function get_view_name($sql) {
        $sql = explode("<->", $sql);
        if (!empty($sql[0]) && stristr($sql[0], ".")) {
            return str_replace("`", "", explode(".", $sql[0])[1]);
        } else {
            return "&nbsp;";
        }
    }

    private function get_operator($sql) {
        $sql = explode("<->", $sql);
        if (!empty($sql[1])) {
            if (stristr($sql[1], "and")) {
                return "between";
            } elseif (stristr($sql[1], ">")) {
                return "From";
            } elseif (stristr($sql[1], "<")) {
                return "To";
            } else {
                return "&nbsp;";
            }
        } else {
            return "&nbsp;";
        }
    }

}