<?php
/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreports.com/
 *
 */

$resultcon = query("SELECT table_name,COLUMN_NAME ,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS" . " WHERE  TABLE_SCHEMA = '" . $DB_NAME . "' and ( $cond )", "Menu: Get Data Types");

function lower(&$string) {
    $string = strtolower($string);
}

/**
 * * apply the lower function to the array **
 */
array_walk($fields2, 'lower');

$data = array();
if ($datasource == "sql")
    $resultcon = array();
if (is_array($resultcon)) {
    foreach ($resultcon as $row) {
        $fild = array();

        // if(in_array($row['DATA_TYPE'],$dataty) && in_array(((count($table)==1)?"":strtolower($row['table_name']).".").strtolower($row['COLUMN_NAME']),$fields2) )
        // {

        foreach ($row as $k => $v) {
            $fild [] = $v;
        }
        if (!in_array($fild, $data)) {
            $data [] = $fild;
        }
        // }
    }
}

