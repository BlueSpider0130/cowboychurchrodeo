<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SessionValidation
 *
 * @author webre
 */
class SessionValidation {

    //put your code here
    private $access_mode;
    private $login_page;
    private $session_validation_login_keys;
    private $correct_data_types = array("NUMBER", "ARRAY", "BOOLEAN", "OBJECT", "TEXT");
    private $checks = array("KEY_EXISTS", "DATA_TYPE", "EXACT_VALUE", "IN_ARRAY", "IP", "CLIENT_AGENT");

    public function __construct($access_mode, $login_page, $session_validation_login_keys) {
        $this->access_mode = $access_mode;
        $this->login_page = $login_page;
        $this->session_validation_login_keys = $session_validation_login_keys;
    }

    public function validate() {
       
       
        if ($this->access_mode === "PUBLIC_REPORT" && empty($this->session_validation_login_keys)) {
            return true;
        } elseif ($this->access_mode === "PUBLIC_REPORT" && !empty($this->session_validation_login_keys)) {
            $this->redirect_to_login();
        } elseif ($this->access_mode === "PRIVATE_REPORT" && empty($this->session_validation_login_keys)) {
            $this->redirect_to_login();
        } else {
            if ($this->validate_user_session_array()) {
         
                return true;
            } else {
                $this->redirect_to_login();
            }
        }
    }

    private function redirect_to_login() {
   

        if (empty(trim($this->login_page))) {
            header("HTTP/1.1 401 Unauthorized");
            echo 'Unauthorized';
            dump();
        } else {         
             header('Location: ' . $this->login_page);
            dump();
        }
    }

    private function validate_user_session_array() {
        foreach ($this->session_validation_login_keys as $key => $key_rules) {
            foreach ($key_rules as $rule) {
                if (!$this->validate_user_session_element($rule, $key)){
                   
                    return false;
                }
            }
        }
     
        return true;
    }

    private function validate_user_session_element($rule, $key) {
        switch ($rule["check"]) {
            case "KEY_EXISTS":
                return $this->check_key_exist($key);
                break;
            case "DATA_TYPE":
                return $this->check_data_type($key, $rule["correct_data_type"]);
                break;
            case "IN_ARRAY":
                return $this->check_in_array($key, $rule['correct_value_array']);
                break;
            case "EXACT_VALUE":
                return $this->check_exact_value($key, $rule["correct_value"]);
                break;
            case "IP":
                return $this->check_ip($key);
                break;
            case "CLIENT_AGENT":
                return $this->check_user_agent($key);
                break;
            default:
                return false;
        }
    }

    private function check_key_exist($key) {
       
        return isset($_SESSION[$key]);
    }

    private function check_data_type($key, $correct_data_type) {
        switch ($correct_data_type) {
            case "NUMBER":
                return is_numeric($_SESSION[$key]);
                break;
            case "ARRAY":
                return is_array($_SESSION[$key]);
                break;
            case "BOOLEAN":
                return is_bool($_SESSION[$key]);
                break;
            case "OBJECT":
                return is_object($_SESSION[$key]);
                break;
            case "TEXT":
                return is_string($_SESSION[$key]);
                break;
            default:
                return false;
        }
    }

    private function check_exact_value($key, $exact_correct_value) {
        return $_SESSION[$key] == $exact_correct_value;
    }

    private function check_in_array($key, $array) {
        return in_array($_SESSION[$key], $array);
    }

    private function check_ip($key) {
        return $_SESSION[$key] == $this->get_client_ip();
    }

    private function check_user_agent($key) {
   
        return $_SESSION[$key] == $_SERVER['HTTP_USER_AGENT'];
    }

    private function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
