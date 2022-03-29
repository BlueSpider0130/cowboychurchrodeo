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

class ReportSecurity {

//put your code here
    private $_access_mode;
    private $_session_validation_login_keys;
    private $login_page;
    private $logout_page;
    private $session_name;

    public function __construct($access_mode, $login_page = SRE__DEFAULT_LOGIN_PAGE_, $logout_page = SRE__DEFAULT_LOGOUT_PAGE_,$session_name="") {
        $this->_access_mode = ($access_mode === SRE_PUBLIC_REPORT) ? SRE_PUBLIC_REPORT : SRE_PRIVATE_REPORT;
        $this->_session_validation_login_keys = array();
        $this->login_page = $login_page;
        $this->logout_page = $logout_page;
        $this->session_name = $session_name;
    }

    public function get_access_mode() {
        return $this->_access_mode;
    }

    public function get_session_validation_login_keys() {
        return $this->_session_validation_login_keys;
    }

    public function get_Login_page() {
        return $this->login_page;
    }
    
    public function get_session_name(){
        return $this->session_name;
    }

    public function get_Logout_page() {

        return $this->logout_page;
    }

    private function session_add_validation_login_key($login_key, $check, $correct_data_type = SRE_TEXT, $correct_value_array = array()) {
        $checks = array("KEY_EXISTS", "DATA_TYPE", "EXACT_VALUE", "IN_ARRAY", "IP", "CLIENT_AGENT");

        if (in_array($check, $checks) && !empty($login_key)) {
            $_new_check_array = array("check" => $check);
            switch ($check) {
                case "DATA_TYPE":
                    if (in_array($correct_data_type, json_decode(SRE_DATA_TYPES)))
                        $_new_check_array["correct_data_type"] = $correct_data_type;
                    else
                        $_new_check_array["correct_data_type"] = "";
                    break;
                case "EXACT_VALUE":
                    $_new_check_array["correct_value"] = $correct_value_array[0];
                    break;
                case "IN_ARRAY":
                    $_new_check_array["correct_value_array"] = $correct_value_array;
                    break;
                case "IP":
                    $_new_check_array["correct_value"] = "dynamic";
                    break;
                case "CLIENT_AGENT":
                    $_new_check_array["correct_value"] = "dynamic";
                    break;
            }

            if (!isset($this->_session_validation_login_keys[$login_key])) {
                $this->_session_validation_login_keys[$login_key] = array();
            }
            $this->_session_validation_login_keys[$login_key] [] = $_new_check_array;
        }
    }

    public function check_session_saved_user_key($session_user_key, $check_if_numeric = true) {

        $this->session_add_validation_login_key($session_user_key, "KEY_EXISTS");

        if ($check_if_numeric)
            $this->session_add_validation_login_key($session_user_key, "DATA_TYPE", SRE_NUMBER);
    }

    public function check_session_saved_group_key($session_group_key, $allowed_group_array) {
       
            $this->session_add_validation_login_key($session_group_key, "KEY_EXISTS");


            if (is_array($allowed_group_array) && count($allowed_group_array) > 1) {
                $this->session_add_validation_login_key($session_group_key, "IN_ARRAY",SRE_NUMBER , $allowed_group_array);
            } elseif (is_array($allowed_group_array) && count($allowed_group_array) === 1) {
                $this->session_add_validation_login_key($session_group_key, "EXACT_VALUE", SRE_NUMBER, $allowed_group_array);
            } elseif (!is_array($allowed_group_array) && !empty($allowed_group_array)) {
                $this->session_add_validation_login_key($session_group_key, "EXACT_VALUE", SRE_NUMBER, array($allowed_group_array));
            }
        }
   

    public function check_session_saved_ip_key($session_ip_key) {
        if (isset($session_ip_key)) {
            $this->session_add_validation_login_key($session_ip_key, "KEY_EXISTS");
            $this->session_add_validation_login_key($session_ip_key, "IP");
        }
    }

    public function check_session_saved_client_agent_key($session_client_agent_key) {

        $this->session_add_validation_login_key($session_client_agent_key, "KEY_EXISTS");
        $this->session_add_validation_login_key($session_client_agent_key, "CLIENT_AGENT");
    }

    public function check_session_saved_exact_value_key($session_key, $correct_value) {

        $this->session_add_validation_login_key($session_key, "KEY_EXISTS");


        $this->session_add_validation_login_key($session_key, "EXACT_VALUE", "", array($correct_value));
    }

    public function check_session_saved_exact_data_type_key($session_key, $correct_data_type) {

        $this->session_add_validation_login_key($session_key, "KEY_EXISTS");
        if (in_array($correct_data_type, json_decode(SRE_DATA_TYPES)))
            $this->session_add_validation_login_key($session_key, "DATA_TYPE", $correct_data_type);
        else{
            Helper::log(ErrorMessages::$messages[SRE__LANGUAGE__]["43"], 43);
            throw new \Exception(ErrorMessages::$messages[SRE__LANGUAGE__]["43"], 43);
        }
    }

}
