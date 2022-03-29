<?php
/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");

class TableReport extends Report {

    protected $table;
    protected $affected_column;
    protected $groupby_column;
    protected $relationships;
    protected $tables_filters;
    protected $search;
    protected $funcations_arr;

    public function __construct($table, $fields, $relationships = array(), $tables_filter = array(), $search_options = NULL) {

        $this->set_search_options($search_options);
        $this->set_relasions($relationships);
        $this->set_filters($tables_filter);
        $this->set_table($table);
        $this->set_fields($fields);

        $this->funcations_arr = array(
            "sum(",
            "avg(",
            "min(",
            "max(",
            "count("
        );
    }

    public function set_table($table) {
        if (is_array($table))
            $this->table = $table;
        else
            $this->table = array();
    }

    public function set_relasions($relasions) {
        if (is_array($relasions))
            $this->relationships = $relasions;
        else
            $this->relationships = array();
    }

    public function set_search_options($search) {
        if ($search !== Null  && get_class($search) == "Search_options")
            $this->search = $search;
        else
            $this->search = Null;
    }

    public function set_filters($filters) {
        if (is_array($filters)) {
            $this->tables_filters = $filters;
        } else {
            $this->tables_filters = array();
        }
    }

    public function set_affected_column($affected_column) {
        $this->affected_column = $affected_column;
    }

    public function set_groupby_column($groubby_column) {
        $this->groupby_column = $groubby_column;
    }

    public function Prepare_Sql() {

        // global $fields, $report_key, $table, $sort_by, $group_by, $affected_column, $groupby_column, $relationships, $tables_filters;
        //filters parameters and parametres types
        $filter_params = array();
        $filter_param_types = "";
        //All (filters + search) parameters and parameters types .  
        $parameters = array();
        $types = "";

        $sql = "select ";
        $c = 0;
        foreach ($this->fields as $f) {
            if (count($this->table) != 1) {
                // check if this is a function field
                $isFunction = 0;
                foreach ($this->funcations_arr as $key => $val) {
                    if (strstr($f, $val)) {
                        $isFunction = 1;
                        break;
                    }
                }

                $temp = explode(".", $f);
                $t = $temp [0];
                $f = $temp [1];
                if ($isFunction == 1) {
                    $sql .= "$t`.`$f ";
                    $sql .= " as '" . substr($f, 0, strlen($f) - 2) . "'";
                } else {
                    $sql .= "`$t`.`$f` ";
                    $sql .= " as '$f'";
                }
            } else {
                $isFunction = 0;
                foreach ($this->funcations_arr as $key => $val) {
                    if (strstr($f, $val)) {
                        $isFunction = 1;
                        break;
                    }
                }
                if ($isFunction == 0)
                    $sql .= "`$f`";
                else
                    $sql .= "$f";
            }
            if ($c < (count($this->fields) - 1))
                $sql .= ",";
            $c ++;
        }

        // add tables names
        $sql .= " from ";
        foreach ($this->table as $key => $val)
            $sql .= "`$val`,";
        $sql = substr($sql, 0, strlen($sql) - 1);

        // add relations
        if (!empty($this->relationships) && count($this->relationships) > 0) {
            $sql .= " where";
            foreach ($this->relationships as $key => $val) {
                $sql .= " $val" . " and";
            }
            $sql = substr($sql, 0, strlen($sql) - 3);
        }

        if (count($this->tables_filters) > 0) {
            if (count($this->relationships) > 0) {
                $sql .= " and";
            } else {
                $sql .= " where ";
            }

            foreach ($this->tables_filters as $filter) {

                foreach ($filter as $key => $value) {
                    if ($key == "sql") {
                        $newvalue = str_replace("\\", " ", $value);

                        $newvalue = str_replace("<->", " ", $newvalue);
                        $newvalue = str_replace("\\", "", $newvalue);
                        $sql .= "( $newvalue )" . " and";
                    }
                    if ($key == "param" && !is_array($value))
                        $filter_params [] = $value;
                    elseif ($key == "param" && is_array($value))
                        $filter_params = array_merge($filter_params, $value);
                    if ($key == "type")
                        $filter_param_types .= $value;
                }
            }
            $sql = substr($sql, 0, strlen($sql) - 3);
            $parameters = $filter_params;
            $types = $filter_param_types;
        }



        if (!is_null($this->search)) {
            if ($this->search->search_type == "quick") {

                $search_array = $this->search->prepare_ordinary_search_statment($this->table, $this->fields);
            } else if ($this->search->search_type == "advanced") {
                $search_array = $this->search->prepare_advanced_search_statment();
            }
        }

        if (isset($search_array) && !empty($search_array)) {
            if (is_array($search_array)) {
                //case filter and search
                $search_sql = $search_array ["sql"];
                $parameters = array_merge($parameters, $search_array ["parameters"]);
                $types = $types . $search_array ["types"];
            } else {

                $search_sql = $search_array;
            }
            if (!empty($this->relationships) || count($this->tables_filters) > 0) {
                $sql .= " and " . $search_sql;
            } else {
                $sql .= " where " . $search_sql;
            }
        }

        // group by in case of statistics
        if (!empty($this->groupby_column)) {

            $grp_ar = explode(".", $this->groupby_column);

            if (count($grp_ar) > 1) {
                $sql .= " group by (`" . $grp_ar [0] . "`.`" . $grp_ar [1] . "`) ";
            } else {
                $sql .= " group by (`" . $grp_ar [0] . "`) ";
            }
        }

        if (count($this->sort_by) > 0 || count($this->group_by) > 0)
            $sql .= " order by ";

        $group_by_sort = array();
        foreach ($this->group_by as $g) {
            $flag = 0;
            $i = 0;

            foreach ($this->sort_by as $arr) {
                if ($g == $arr [0]) {
                    $group_by_sort [] = array(
                        $arr [0],
                        $arr [1]
                    );
                    $flag = 1;
                    $this->sort_by [$i] [0] = '~xxx~';
                    break;
                }
                $i ++;
            }
            if ($flag == 0) {
                $group_by_sort [] = array(
                    $g,
                    '0'
                );
            }
        }

        foreach ($this->sort_by as $arr_sort) {
            if ($arr_sort [0] != '~xxx~') {
                $group_by_sort [] = array(
                    $arr_sort [0],
                    $arr_sort [1]
                );
            }
        }
        $i = 0;

        foreach ($group_by_sort as $arr) {
            if (count($this->table) != 1) {

                $dummy = explode(".", $arr [0]);

                $sql .= "`" . $dummy [0] . "`.`" . $dummy [1] . "`";
            } else {
                $sql .= "`" . $arr [0] . "`";
            }

            if ($arr [1] == '1')
                $sql .= "desc";
            if ($i < (count($group_by_sort) - 1)) {
                $sql .= ",";
            }
            $i ++;
        }

        $new_fields = array();
        $new_sort_by = array();
        $new_group_by = array();

        // fields
        foreach ($this->fields as $key => $val) {
            // check if it's function field
            $isFunction = 0;
            foreach ($this->funcations_arr as $key1 => $val1) {
                if (strstr($val, $val1)) {
                    $isFunction = 1;
                    break;
                }
            }

            @list ( $t, $f ) = explode(".", $val);

            if ($isFunction == 1) {
                $new_fields [] = substr($f, 0, strlen($f) - 2);
            } else {
                $new_fields [] = $f;
            }
        }
        if (count($this->table) != 1)
            $this->fields = $new_fields;

        // this->sort_by

        foreach ($this->sort_by as $key => $arr) {
            if (strstr($arr [0], ".")) {
                $temp = explode(".", $arr [0]);
                $t = $temp [0];
                $f = $temp [1];
            }else{
                $t = $arr [0];
                $f = "";
            }

            $new_sort_by [] = array(
                $f,
                $arr [1]
            );
        }
        if (count($this->table) != 1)
            $this->sort_by = $new_sort_by;

        // this->affected_column
        foreach ($this->group_by as $key => $val) {
            @list ( $t, $f ) = explode(".", $val);

            $new_group_by [] = $f;
        }
        if (count($this->table) != 1)
            $this->group_by = $new_group_by;


        $arr_sql [0] = $sql;
        $arr_sql [1] = $parameters;
        $arr_sql [2] = $types;
        return $arr_sql;
    }

}
?>


