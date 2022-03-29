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

class TestHelper {

//put your code here
    static function check_generated_report_is_complete($report_path,$is_mobile = false,$test_records_count=false,$records_count=25) {
        if (empty($report_path))
            return false;
        
        $generated_contents = file_get_contents($report_path);
     
        return self::validate_response_as_report($generated_contents,$is_mobile,$test_records_count,$records_count);
      
    }

    static function output_error($ex, $report_path = "", $class) {
        echo '######exception sTart ######' . PHP_EOL;
        echo 'Test Case ' . $class . PHP_EOL;
        echo ' code : ' . $ex->getCode() . PHP_EOL;
        echo " Message: " . $ex->getMessage() . PHP_EOL;
        echo "File: " . $ex->getFile() . PHP_EOL;
        echo "Line" . $ex->getLine() . PHP_EOL;
        if (!empty($report_path))
            echo "Report Path" . $report_path . PHP_EOL;
        echo "###### exception end ######";
    }

    static function call_report_and_get_reponse_code($report_path) {


        $handle = curl_init($report_path);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        return $httpCode;
    }

    public static function get_report_config_path($report_path) {
        $report_path_piecies = explode("/", $report_path);
        unset($report_path_piecies[count($report_path_piecies) - 1]);
        $report_path_piecies[] = "config.php";
       return implode("/", $report_path_piecies);
    }

    public static function send_request_get_response_and_response_code($url) {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);
        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        return array("response_code" => $httpCode, "response" => $response);
    }
    
    public static function validate_response_as_report($generated_contents,$is_mobile=false,$test_records_count,$records_count){
        if($test_records_count){
            if(substr_count($generated_contents,"data-row") != $records_count ){
                return false;
            }
        }
        
        if(!$is_mobile){
        return stristr($generated_contents, '</html>') &&
                stristr($generated_contents, '.stars()') &&
                stristr($generated_contents, '<!-- end pagination block -->') &&
                stristr($generated_contents, '<!-- ******************** end custom footer ******************** !-->') &&
                stristr($generated_contents, '<!-- ******************** end custom header ******************** !-->') &&
                stristr($generated_contents, 'MainTable') &&
                stristr($generated_contents, '(document).ready(function()') &&
                !stristr($generated_contents, "Notice:") &&
                !stristr($generated_contents, "on line") &&
                !stristr($generated_contents, "Warning");
          }else{
              return stristr($generated_contents, '</html>') &&
                stristr($generated_contents, '.stars()') &&
                stristr($generated_contents, 'Export') &&                
                stristr($generated_contents, "container") &&
                stristr($generated_contents, '(document).ready(function()') &&
                !stristr($generated_contents, "Notice:") &&
                !stristr($generated_contents, "on line") &&
                !stristr($generated_contents, "Warning");
          }
    }

}
