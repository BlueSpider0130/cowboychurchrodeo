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



class ConditionalFormatting {

    private $formatting_rules;

    public function __construct() {
        $this->formatting_rules = array();
    }

    public function get_rules() {


        return $this->formatting_rules;
    }

    public function add_rule($column, $operator, $parameter1, $color, $parameter2 = "") {

        $this_rule = array();


        $allowed_operators = array("equal", "not_equal", "more", "less", "more_or_equal", "less_or_equal", "between", "contain", "not_contain", "begin_with", "end_with");


        if (!empty($column) && in_array(strtolower($operator), $allowed_operators)) {
            //construct array
            $key = count($this->formatting_rules) + 1;



            $this_rule = array(
                "filter" => $this->get_edited_operator($operator),
                "column" =>strtolower($column),
                "filterValue1" => $parameter1,
                "filterValue2" => $parameter2,
                "color" => $color
            );



            $this->formatting_rules[$key] = $this_rule;
        }
    }

    //operators =
    private function get_edited_operator($operator) {
        switch (strtolower($operator)) {

            case "between":
                return "between";
                break;
            case "equal":
                return "equal";
                break;
            case "not_equal":
                return "notequal";
                break;
            case "more":
                return "more";
                break;
            case "less":
                return "less";
                break;
            case "more_or_equal":
                return "moreorequal";
                break;
            case "less_or_equal":
                return "lessorequal";
                break;
            case "contain":
                return "contain";
                break;
            case "not_contain":
                return "notcontain";
                break;
            case "begin_with":
                return "beginwith";
                break;
            case "end_with":
                return "endwith";
                break;
        }
    }

}
