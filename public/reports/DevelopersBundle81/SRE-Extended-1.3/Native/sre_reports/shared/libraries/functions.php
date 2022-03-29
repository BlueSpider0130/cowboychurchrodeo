<?php

/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreports.com/
 *
 */
/*
 * #################################################################################################
 * DB managment and other helper functions 
 * ################################################################################################
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");

function set_direction() {
    global $language;
    if ($language == "he" || $language == "ar") {
        return " dir = 'rtl' ";
    } else {
        return "";
    }
}

function get_cur_url() {
    global $_SERVER;
    $pageURL = 'http';

    if (isset($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on') {
        $pageURL .= "s";
    } elseif (strstr(strtolower($_SERVER ["SERVER_PROTOCOL"]), "https")) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER ["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
    }
    return strtok($pageURL, '?');
}

function h_aggregation_arr($arr) {
    // this function correct detect and fix statestical columns in arrays
    // for example changes $arr["some column"] to $arr[sum(the same column)] if the column is an affectd one
    global $affected_column, $function;
    $editedArray = array();
    foreach ($arr as $key => $val) {
        if ($key == $affected_column) {
            $editedArray ["$function(`$key`)"] = $val;
        } else {
            $editedArray [$key] = $val;
        }
    }
    return $editedArray;
}

function get_param_type($var) {
    // this function should be used with numeric parameters to decide wether it is an integer or double
    $int_var = (int) $var;

    if ($var == $int_var) {
        return "i";
    } else {
        return "d";
    }
}

/*
 * Sending Queries
 * *****************************************************************************************
 */

function connect() {
    global $db_extension, $used_extension, $DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME;
    $extensions = array(
        "pdo",
        "mysqli",
        "mysql"
    );
    if (isset($db_extension) && in_array(strtolower($db_extension), $extensions)) {
        $extension = $db_extension;
    } else {
        $extension = "";
    }

    if (check_debug_mode() == 1) {
        $dbHandler = new DatabaseHandler($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, true, $extension);
    } else {
        $dbHandler = new DatabaseHandler($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, false, $extension);
    }
    if (!$dbHandler || $dbHandler->is_connection_failed()) {
        Die("Internal System Error");
        return false;
    }
    $used_extension = $dbHandler->get_used_extension();
    debug("\n Used Extension : $used_extension \n");
    return $dbHandler;
}

function query($query, $stacktrace = "query", $params = array(), $paramsType = "") {
    global $possible_attack, $flush, $maintainance_email, $report_key;
    // important to protect database from attacks
    if ($possible_attack == true) {
        return array();
    }
    debug("\n *** New Request  at: " . date('Y-m-d H:i:s') . " -----------------------------------------------------> \n \n");
    debug("\n ## calling function : $stacktrace  \n");

    $dbHandler = connect();
    if (!empty($params))
        $unique_params = array_unique($params);

    if (empty($params)) {
        $params = array();
        $paramsType = '';
        debug("\n No parameters passed ");
    } elseif (count($unique_params) === 1 && count($params) > 1) {
        // case ordinary search params where all the params are duplicates of the same param
        $cleaned_param = clean_input($unique_params [0], false, false, false);
        debug("\n  Cleaned Parameters :  " . $cleaned_param);
        $sanitized_param = $dbHandler->sanitize_values($cleaned_param);
        debug("\n  escaped parameters :  " . $sanitized_param);
        $elements = count($params);
        $params = array();
        for ($i = 0; $i < $elements; $i ++) {
            $params [] = $sanitized_param;
        }
    } else {
        // case any other parameters
        debug("\n  Parameters :  " . implode("\n  * parameter: ", $params));
        debug("\n  parameter types : $paramsType");

        // Cleaning
        $params = clean_array($params);
        debug("\n  Cleaned Parameters :  " . implode("\n  * parameter: ", $params));

        // sql outpurt escaping against SQL injection
        $params = $dbHandler->sanitize_array($params);

        debug("\n  escaped parameters :  " . implode("\n  * parameter: ", $params));
    }

    if (!$dbHandler || $dbHandler->is_connection_failed()) {
        if ($flush && check_debug_mode() == 1)
            send_log_info($maintainance_email);
        debug(" Connection error ");
        return false;
    }

    debug("\n ## Sql query : $query  \n   ");
    $result = $dbHandler->query($query, "ASSOC", $params, $paramsType);
    if (!is_array($result)) {

        debug("##$stacktrace : $query <br\> **Invalid query ");
        if ($flush && check_debug_mode() === 1)
            send_log_info($maintainance_email);
        return false;
    }
    $dbHandler->close_connection();
    debug("*** End of the request at: " . date('Y-m-d H:i:s'));
    if ($flush && check_debug_mode() == 1)
        send_log_info($maintainance_email);
    debug("array returned " . count($result) . "rows");
    return $result;
}

function language_exist($lang) {
    $path = "../shared/languages/" . $lang . ".php";
    if (file_exists($path))
        return true;
    else
        return false;
}

function grouping_diff_index($arr1, $arr2) {
    $i = 0;

    foreach ($arr1 as $key => $val) {
        if ($val != $arr2 [$key]) {
            // echo "i=".$i."\n ";
            return $i;
        }

        $i ++;
    }

    return - 1;
}

function array_row_count($arr) {
    if (function_exists(array_column)) {
        return array_column($arr);
    } else {
        return count($arr [0]);
    }
}

function array_appending($arr1, $arr2) {
    foreach ($arr2 as $key => $val) {
        $arr1 [$key] = $val;
    }
    return $arr1;
}

function map_datatype($datatype) {
    $dataStr = array(
        'varchar',
        'char',
        'text'
    );
    $dataInt = array(
        'int',
        'decimal',
        'double',
        'smallint',
        'float',
        'year'
    );
    $dataDate = array(
        'date'
    );
    $dataDateTime = array('datetime', 'timestamp');
    $dataTime = array('time');
    $dataBool = array(
        'bit',
        'bool',
        'tinyint'
    );
    if (in_array($datatype, $dataInt))
        return "number";
    elseif (in_array($datatype, $dataDate))
        return "date";
    elseif (in_array($datatype, $dataBool))
        return "YesNo";
    elseif (in_array($datatype, $dataTime))
        return "time";
    elseif (in_array($datatype, $dataDateTime))
        return 'datetime';
    else
        return "text";
}

function get_numeric_index($key, $arr) {
    $index = 1;
    foreach ($arr as $f) {

        if (strcasecmp($f, $key) == 0) {
            return $index;
        }
        $index ++;
    }
    return 0;
}

function array_get_insensetive_element($key, $arr) {
    $tmp = arr_to_lower($arr);
    return $tmp[strtolower($key)];
}

function get_all_fields() {
    global $table, $datasource, $sql;
    $arr = array();
    $columns = array();
    if ($datasource == "table") {
        foreach ($table as $t) {
            $arr = query("show columns from `$t`");
            foreach ($arr as $element) {
                if (count($table) === 1)
                    $columns[] = $element["Field"];
                else
                    $columns[] = $t . "." . $element["Field"];
            }
        }
    }else {
        $arr = query($sql);
        foreach ($arr[0] as $col => $val) {
            $columns[] = $col;
        }
    }
    
    return $columns;
}

/*
 * get_field_part
 *
 * Return the appropriate fields index in a result set
 * either col or table.col depending on how the results is formated
 *
 * @param (name) the index to adjust
 * @param (row) a row in the result set
 *
 *
 * @return the adjusted index .
 */

function get_field_part($name, $row) {
    global $table;
    $name = strtolower($name);
    if (count($table) > 1 && strstr($name, ".") && is_short_fields_result($row)) {
        $arr = explode(".", $name);
        if ($arr [1]) {
            $field = str_replace("`", "", $arr [1]);
            $field = str_replace(")", "", $field);
            return $field;
        } else {

            return $name;
        }
    } else {

        return $name;
    }
}

/*
 * is_short_fields_result 
 *
 * check if the array consists of result set in the form of tab.col or just col
 *
 * @param (arr) the array to check
 * 
 *
 *
 * @return true if the array consists of col and false if it consists of table.col .
 */

function is_short_fields_result($arr) {
    foreach ($arr as $key => $val) {
        if (strstr($key, ".")) {
            //long field index tab.col in the result set
            return false;
        } else {
            //short field index col in the result set
            return true;
        }
    }
}

function arr_to_lower($arr, $change_values = false) {
    $tmp = array();
    foreach ($arr as $k => $v) {
        if (!$change_values)
            $tmp[strtolower($k)] = $v;
        else
            $tmp[strtolower($k)] = strtolower($v);
    }
    return $tmp;
}

?>