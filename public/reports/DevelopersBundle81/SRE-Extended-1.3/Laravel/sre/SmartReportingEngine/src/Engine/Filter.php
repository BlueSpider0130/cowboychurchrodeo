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

class Filter   {
    
    private $tables_filters; 
    
    public function __construct() {
        $this->tables_filters = array();
    }
    
    
    public function get_tables_filters() {
        return $this->tables_filters;
    }
     protected function filter($table, $column, $operator, $parameter, $parameter_type = Constants::SRE_NUMBER) {

        $this_table_filter_array = array();
        $allowed_parameter_types = array(Constants::SRE_DATE, Constants::SRE_NUMBER, Constants::SRE_TEXT);
        $allowed_operators = array("equal", "not_equal", "less", "more", "less_and_equal", "more_and_equal", "like", "not_like");
  

        if (!empty($table) && !empty($column) && in_array(strtolower($operator), $allowed_operators) && in_array($parameter_type, $allowed_parameter_types)) {
            //construct array
          $table = strtolower($table);
          $column = strtolower($column);
            $index = count($this->tables_filters) + 1;
            $key = "filter$index";

            if ($parameter_type === Constants::SRE_DATE&& Helper::is_date($parameter)) {
                $this_table_filter_array = array(
                    "sql" => "`" . $table . "`.`" . $column . "`  <->  " . $this->get_edited_operator(strtolower($operator)) . "  ?",
                    "param" => Helper::format_date_param($parameter, $parameter_type),
                    "type" => $this->get_edited_parameter_type(strtolower($parameter_type))
                );
            } elseif ($parameter_type === Constants::SRE_NUMBER || $parameter_type === Constants::SRE_TEXT) {

                $this_table_filter_array = array(
                    "sql" => "`" . $table . "`.`" . $column . "`  <->  " . $this->get_edited_operator(strtolower($operator)) . "  ?",
                    "param" => $parameter,
                    "type" => $this->get_edited_parameter_type(strtolower($parameter_type))
                );
            }
            if (!empty($this_table_filter_array)) {
                $existed_kay = array_search($this_table_filter_array, $this->tables_filters);

                if ($existed_kay)
                    $this->tables_filters[$existed_kay] = $this_table_filter_array;
                else
                    $this->tables_filters[$key] = $this_table_filter_array;
            }
        }
    }

    public function between($table, $column, $first_param, $second_param, $parameters_type = Constants::SRE_NUMBER) {
         $allowed_parameter_types = array(Constants::SRE_DATE, Constants::SRE_NUMBER, Constants::SRE_TEXT);

        if (!empty($table) && !empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            $this->filter($table, $column, "more", $first_param, $parameters_type);
            $this->filter($table, $column, "less", $second_param, $parameters_type);
        }
    }

    public function more($table, $column, $param, $is_or_equal = false, $parameters_type = Constants::SRE_NUMBER) {
         $allowed_parameter_types = array(Constants::SRE_DATE, Constants::SRE_NUMBER, Constants::SRE_TEXT);

        if (!empty($table) && !empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            if ($is_or_equal)
                $this->filter($table, $column, "more_and_equal", $param, $parameters_type);
            else
                $this->filter($table, $column, "more", $param, $parameters_type);
        }
    }

    public function less($table, $column, $param, $is_or_equal = false, $parameters_type =Constants::SRE_NUMBER) {
         $allowed_parameter_types = array(Constants::SRE_DATE, Constants::SRE_NUMBER, Constants::SRE_TEXT);

        if (!empty($table) && !empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            if ($is_or_equal)
                $this->filter($table, $column, "less_and_equal", $param, $parameters_type);
            else
                $this->filter($table, $column, "less", $param, $parameters_type);
        }
    }

    public function equal($table, $column, $param, $parameters_type = Constants::SRE_NUMBER) {
         $allowed_parameter_types = array(Constants::SRE_DATE, Constants::SRE_NUMBER, Constants::SRE_TEXT);

        if (!empty($table) && !empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            $this->filter($table, $column, "equal", $param, $parameters_type);
        }
    }

    public function not_equal($table, $column, $param, $parameters_type = Constants::SRE_NUMBER) {
        $allowed_parameter_types = array(Constants::SRE_DATE, Constants::SRE_NUMBER, Constants::SRE_TEXT);

        if (!empty($table) && !empty($column) && in_array($parameters_type, $allowed_parameter_types)) {
            $this->filter($table, $column, "not_equal", $param, $parameters_type);
        }
    }

    public function like($table, $column, $param) {


        if (!empty($table) && !empty($column)) {
            $this->filter($table, $column, "like", $param, Constants::SRE_TEXT);
        }
    }

    public function not_like($table, $column, $param) {


        if (!empty($table) && !empty($column)) {
            $this->filter($table, $column, "not_like", $param, Constants::SRE_TEXT);
        }
    }
    
     private function get_edited_operator($operator) {
        switch ($operator) {


            case "equal":
                return "=";
                break;
            case "not_equal":
                return "!=";
                break;
            case "more":
                return ">";
                break;
            case "less":
                return "<";
                break;
            case "more_and_equal":
                return ">=";
                break;
            case "less_and_equal":
                return "<=";
                break;
            case "like":
                return "like";
                break;
            case "not_like":
                return "not like";
                break;
        }
    }

    //$allowed_types = array("date", "number", "string");
    private function get_edited_parameter_type($type) {
        switch ($type) {
            case Constants::SRE_NUMBER:
                return "i";
                break;
            default:
                return "s";
                break;
        }
    }


}

/*
 * \endcond
 */