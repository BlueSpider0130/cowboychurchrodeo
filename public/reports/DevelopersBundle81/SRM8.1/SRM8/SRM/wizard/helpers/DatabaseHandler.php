<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");
require_once 'MysqliHandler.php';
require_once 'PDOHandler.php';

class DatabaseHandler {

    protected $provider, $used_extension = "Undefined", $debug = false;
    public $extension = '';

    public function __construct($host, $user, $pass, $db, $extension = 'PDO') {
        $this->extension = $extension;
        if (extension_loaded('pdo') && version_compare(PHP_VERSION, '5.1.0') >= 0) {
            $this->used_extension = "PDO";
            $this->provider = new PDOHandler($host, $user, $pass, $db, false);
        } elseif (extension_loaded('mysqli') && function_exists('mysqli_stmt_get_result')) {
            $this->used_extension = "MySQLi";
            $this->provider = new MysqliHandler($host, $user, $pass, false, $db);
        } else {
            die("No Db drive is recognized");
        }
    }

    public function select_database($db) {
        return $this->provider->select_database($db);
    }

    public function get_used_extension() {
        return $this->used_extension;
    }

    // this function make query to fetch data from database ( Like using SELECT & SHOW ), this function return array and not handler
    public function query($sqlStatement, $keyType = "NUM", $params = array(), $paramsType = '') { // $keyType = ASSOC, NUM, BOTH
        if (extension_loaded('pdo') && version_compare(PHP_VERSION, '5.1.0') >= 0) {
            return $this->provider->query($sqlStatement, $keyType, $params); // pdo
        } elseif (extension_loaded('mysqli') && function_exists('mysqli_stmt_get_result')) {
            if ($paramsType !== '')
                $params = array_merge(array(
                    $paramsType
                        ), $params);
            return $this->provider->query($sqlStatement, $keyType, $params); // mysqli
        } else {
            die("No Db drive is recognized");
        }
    }

    public function command($sqlStatement, $params = array(), $paramsType = '') {
        if (extension_loaded('mysqli') && function_exists('mysqli_stmt_get_result') && ($this->extension === '' || $this->extension === 'Mysqli')) {
            if ($paramsType !== '')
                $params = array_merge(array(
                    $paramsType
                        ), $params);
            return $this->provider->command($sqlStatement, $params); // mysqli
        } else if (extension_loaded('pdo') && version_compare(PHP_VERSION, '5.1.0') >= 0 && ($this->extension === '' || $this->extension === 'PDO')) {
            return $this->provider->command($sqlStatement, $params); // pdo
        } else {
            if ($paramsType !== '')
                $params = array_merge(array(
                    $paramsType
                        ), $params);
            return $this->provider->command($sqlStatement, $params); // mysql
        }
    }

    // sanitize string
    public function sanitize_values($string) {
        return $this->provider->sanitize_values($string);
    }

    // sanitize array
    public function sanitize_array($array) {
        foreach ($array as $key => $value)
            $array [$key] = $this->sanitize_values($value);
        return $array;
    }

    // this function to check if connection failed or succeeded
    public function is_connection_failed() { // if connection failed return true
        // return $this->provider->is_connection_failed ();
        if ($this->provider->connect() == false) {

            return array(
                true,
                $this->provider->connection_error
            );
        } else {
            if ($this->provider->select_database($this->provider->db)) {
                return array(
                    false,
                    ""
                );
            } else {
                return array(
                    true,
                    $this->provider->connection_error
                );
            }
        }
    }

    // this function return number of rows for current query
    public function get_num_rows() {
        return $this->provider->get_num_rows();
    }

    // this function return database handler type
    public function get_db_handler_type() {
        return $this->provider->get_db_handler_type();
    }

    // this function for close connection
    public function close_connection() {
        $this->provider->close_connection();
    }

    public function available_extensions() {
        $available_extensions = array();
        if (extension_loaded('mysqli') && function_exists('mysqli_stmt_get_result'))
            $available_extensions [] = 'Mysqli';
        if (extension_loaded('pdo') && version_compare(PHP_VERSION, '5.1.0') >= 0)
            $available_extensions [] = 'PDO';
        if (extension_loaded('mysql'))
            $available_extensions [] = 'Mysql';

        return $available_extensions;
    }

}

?>