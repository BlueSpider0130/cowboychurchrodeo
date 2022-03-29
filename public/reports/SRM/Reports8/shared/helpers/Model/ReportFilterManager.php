<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");

class FilterManager {

    public $table;
    public $column;
    public $column_datatype;
    public $filter_type;
    public $filter_value_1;
    public $filter_value_2;
    public $all_filters;
    private $name;
    public $parameter_text;

    public function __construct() {
        $this->parameter_text = "a user input";
        $this->filter_value_2 = "";
        if (!isset($_SESSION ["srm_f62014_tables_filters"]))
            $_SESSION ["srm_f62014_tables_filters"] = array();
        $this->all_filters = array(
            "Equal",
            "Like",
            "NOT Like",
            "Not Equal",
            "Begin with",
            "End With",
            "Contain",
            "Greater than",
            "Less than",
            "Greater than or Equal",
            "Less than or Equal",
            "Between",
            "Is Null",
            "Is Not Null",
            "Is Today"
                )
        ;
    }

    public function add_filter() {
        $index = (int) count($_SESSION ["srm_f62014_tables_filters"]) + 1;

        if (!$this->is_filter_exist("filter" . $index)) {
            $this->name = "filter" . $index;
        } else {
            //no repeated filter name
            $s = substr(str_shuffle(str_repeat("123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 2);
            $this->name = "filter" . $index . "_" . $s;
        }
        $arr = array();
        $arr ["sql"] = $this->get_sql();
        if (stristr($this->filter_value_1, $this->parameter_text) && stristr($this->filter_value_2, $this->parameter_text)) {
            $arr ["param"] = array(
                $this->get_parameter_value($this->filter_value_1),
                $this->get_parameter_value($this->filter_value_2)
            );
        } elseif (stristr($this->filter_value_1, $this->parameter_text) && $this->filter_value_2 == "") {
            $arr ["param"] = $this->get_parameter_value($this->filter_value_1);
        } elseif ($this->filter_value_2 != "") {
            $arr ["param"] = array(
                $this->filter_value_1,
                $this->filter_value_2
            );
        } elseif (strtolower($this->filter_type) == "begin with") {
            $arr ["param"] = $this->filter_value_1 . "%";
        } elseif (strtolower($this->filter_type) == "end With") {
            $arr ["param"] = "%" . $this->filter_value_1;
        } elseif (strtolower($this->filter_type) == "contain") {
            $arr ["param"] = "%" . $this->filter_value_1 . "%";
        } elseif (strtolower($this->filter_type) == "is null" || strtolower($this->filter_type) == "is not null") {
            $arr ["param"] = "";
        } 
        else {
            $arr ["param"] = $this->filter_value_1;
        }
        $arr ["type"] = $this->get_type();

        $_SESSION ["srm_f62014_tables_filters"] [$this->name] = $arr;

        $response = $this->name . " >> " . $this->filter_type . ">>" . $this->filter_value_1;
        echo "2nd filter var." . $this->filter_value_2;
        if ($this->filter_value_2 != "")
            $response .= " & " . $this->filter_value_2;
        return $response;
    }

    public function remove_filter($name) {
        foreach ($_SESSION ["srm_f62014_tables_filters"] as $key => $value) {
            if (strtolower(trim($name)) == strtolower(trim($key))) {
                unset($_SESSION ["srm_f62014_tables_filters"] [$key]);
            }
        }
    }

    public function is_filter_exist($filter) {
        foreach ($_SESSION ["srm_f62014_tables_filters"] as $key => $value) {

            if (strtolower(trim($filter)) == strtolower(trim($key))) {
                return true;
            }
        }
        return false;
    }

    private function get_type() {
        if ($this->filter_value_2 == "" && (strtolower($this->column_datatype) == "int" || strtolower($this->column_datatype) == "integer")) {
            return "i";
        } elseif ($this->filter_value_2 != "" && (strtolower($this->column_datatype) == "int" || strtolower($this->column_datatype) == "integer")) {
            return "ii";
        } elseif ($this->filter_value_2 == "" && (strtolower($this->column_datatype) == "decimal" || strtolower($this->column_datatype) == "float" || strtolower($this->column_datatype) == "double")) {
            return "d";
        } elseif ($this->filter_value_2 != "" && (strtolower($this->column_datatype) == "decimal" || strtolower($this->column_datatype) == "float" || strtolower($this->column_datatype) == "double")) {
            return "dd";
        } elseif ($this->filter_value_2 != "") {
            return "ss";
        } else {
            return "s";
        }
    }

    private function get_parameter_value($param) {
        if (stristr($param, $this->parameter_text)) {
            $numeric_data_types = array_map('strtolower', array("int", "integer", "SMALLINT", "TINYINT", "MEDIUMINT", "BIGINT", "decimal", "NUMERIC", "float", "double", "real", "bit"));
            $date_data_types = array_map('strtolower', array("DATE", "DATETIME"));
            foreach ($numeric_data_types as $v) {
                if (stristr(trim(strtolower($this->column_datatype)), $v)) {
                    return "n-param";
                }
            }
            foreach ($date_data_types as $d) {
                if (stristr(trim(strtolower($this->column_datatype)), $d)) {
                    return "d-param";
                }
            }
            return "t-param";
        } else {
            return $param;
        }
    }

    private function get_sql() {
        $sql = "";
        $this->table = str_replace("`", "", $this->table);
        $this->column = str_replace("`", "", $this->column);
        $sql .= "`" . $this->table . "`.`" . $this->column . "` ";
        if (strtolower($this->filter_type) == "is null" || strtolower($this->filter_type) == "is not null") {
           
            $sql .= " <-> " . $this->get_operator() . "";
        }elseif(strtolower($this->filter_type) != "between")
        {
            $sql .= " <-> " . $this->get_operator() . " ?";
        } 
        else {
            $sql .= "  <-> > ? and `" . $this->table . "`.`" . $this->column . "`  <-> < ?";
        }
        return $sql;
    }

    private function get_operator() {
        switch (strtolower($this->filter_type)) {
            case "like" :
                return "LIKE";
            case "not like" :
                return "NOT LIKE";
            case "begin with" :
                return "LIKE";
            case "end with" :
                return "LIKE";
            case "contain" :
                return "LIKE";
            case "equal":
                return "=";
            case "not equal":
                return "!=";
            case "greater than":
                return ">";
            case "less than":
                return "<";
            case "greater than or equal":
                return ">=";
            case "less than or equal":
                return "<=";
                case "is null":
                    return "IS NULL";
                case "is not null":
                    return "IS NOT NULL";
                case "is today":
                    return "= today";
        }
    }

}
