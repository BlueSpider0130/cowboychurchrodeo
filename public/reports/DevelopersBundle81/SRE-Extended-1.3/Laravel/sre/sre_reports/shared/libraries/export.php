<?php
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */
use Dompdf\Dompdf;
use Sre\SmartReportingEngine\src\Engine\Constants;





/*
 * #################################################################################################
 * Expoert to CSV.
 * ################################################################################################
 */

function export_csv($sql, $limits, $start, $duration, $records_count) {
    global $labels, $empty_report_lang;
    // validation of exporting parameters
    $result = validate_export_parameters($sql, $limits, $start, $duration, $records_count);

    // adjust header to send the file
    $html = "";

    $fields_count = count($labels);
    $header = "";
    foreach ($labels as $k => $v) {
        $field = $v;
        $header .= str_replace(',', ';', $field) . ',';
    }
    $header = substr($header, 0, strlen($header) - 1);

    // output CSV field names
    $header .= $header . " " . PHP_EOL;

    $k = 0;
    $records = $header;
    if (!empty($result)) {
        foreach ($result as $row) {

            // $i++;
            // $field_data = "";
            foreach ($row as $key => $val) {
                $field_data = $val;
                $field_data = str_replace("\r\n", ' ', $field_data);
                $field_data = str_replace(',', ';', $field_data);
                $field_data = str_replace("\n", ' ', $field_data);

                $field_data .= ',';

                $records .= $field_data;
            }

            $records .= PHP_EOL;
            // $records = mb_convert_encoding($records, 'UCS-2LE', 'UTF-8');
        }
    } else {
        $records .= $empty_report_lang . PHP_EOL;
    }

    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=export.csv;');
    header('Content-Transfer-Encoding: binary');

    $fp = fopen('php://output', 'w');

    // add BOM to fix UTF-8 in Excel
    fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
    if ($fp) {
        fwrite($fp, $records);
    }

    fclose($fp);
    dump();
}

/*
 * #################################################################################################
 * Expoerting to XML .
 * ################################################################################################
 */

function export_xml($sql, $limits, $start, $duration, $records_count) {

    // adjust header to send the file
    global $fields, $empty_report_lang;
    // validation of exporting parameters
    $result = validate_export_parameters($sql, $limits, $start, $duration, $records_count);

    $fields_arr = array();
    // output CSV HTTP headers ...
    // start getting data from the sql statement

    $fields_count = count($fields);
    $tags = array();

    // add fields names to the array
    for ($i = 0; $i < $fields_count; $i ++) {
        $field = $fields [$i];
        $field_name = str_replace(']]>', ']>', $field);
        // removing invalid characters from field name
        $chars = array(
            "(",
            ")"
        );
        foreach ($chars as $v) {
            $field_name = str_replace($v, "", $field_name);
        }

        $field_name = str_replace(' ', '_', $field_name);
        array_push($tags, $field_name);
    }

    // xml header

    $html = "<?xml version='1.0'  encoding='utf-8' ?>" . PHP_EOL;
    $html .= "<RECORDS>" . PHP_EOL;
    // iterate through rows
    if (!empty($result)) {
        foreach ($result as $row) {

            $html .= "<RECORD>" . PHP_EOL;
            $i = 0;
            foreach ($fields as $f) {

                // $html .= "<" . $tags [$i] . ">" . escape (clean_input($v) ) . "</" . $tags [$i] . ">". PHP_EOL;

                $html .= "<" . $tags [$i] . "><![CDATA[" . escape($row[get_field_part($f, $row)]) . "]]></" . $tags [$i] . ">" . PHP_EOL;
                // }
                $i ++;
            }

            $html .= "</RECORD>" . PHP_EOL;
        }
    } else {
        $html .= $empty_report_lang . PHP_EOL;
    }
    $html .= "</RECORDS>" . PHP_EOL;
    ob_start();
    header("Cache-control: private");
    header("Content-type: application/force-download");

    if (strstr($_SERVER ["HTTP_USER_AGENT"], "MSIE"))
        header("Content-Disposition: filename=data.xml"); // For IE
    else
        header("Content-Disposition: attachment; filename=data.xml"); // For Other browsers
    echo $html;
    ob_end_flush();
}

/*
 * #################################################################################################
 * Expoerting to pdf for small records .
 * ################################################################################################
 */

function get_pdf($sql, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug = 0) {
   // validation of export parameters
	get_pdf_large($sql, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug);
}

/*
 * #################################################################################################
 * Expoert to PDF for large records <<under construction>>
 * ################################################################################################
 */

function get_pdf_large($sql, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug = 0) {
    $link = validate_export_parameters($sql, $limits, $start, $duration, $records_count);


    global $datasource, $title, $cells, $file_name, $labels, $title, $empty_report_lang, $default_page_size, $fields, $header, $footer;
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header("Content-Disposition:attachment;filename='downloaded.pdf'");
    header('Content-Transfer-Encoding: binary');
    $pdf = new Cezpdf($pagesize, $oriantation);
    $pdf->ezSetMargins($top, $bottom, $left, $right);
    $pattern = 'Page {PAGENUM} of {TOTALPAGENUM}';
    if ($oriantation == "landscape")
        $pdf->ezStartPageNumbers($width - 20, 560, 10, '', $pattern, 1);
    else
        $pdf->ezStartPageNumbers($width - 20, 810, 9, '', $pattern, 1);


    $pdf->selectFont('../shared/pdf-old/fonts/Helvetica.afm');

    $data = array(array());
    $cols = array();

    $prefrences = array(
        'justification' => 'center'
    );
    $pdf->ezText("<b>$title</b>", 15, $prefrences);
    $pdf->ezText("$header", 10, $prefrences);
    $pdf->ezText("", 15, $prefrences);
    $col_index = -1;
    $row_index = -1;
    foreach ($fields as $v) {
        $col_index++;

        $cols[
                $v]['justification'] = 'center';
        $col[$col_index] = "<b>" . utf8_decode($labels [$v]) . "</b>";
    }

    foreach ($link as $row) {

        $col_index = -1;
        $row_index++;
        foreach ($row as $k => $v) {
            $col_index++;
            $data[$row_index][$col_index] = $v;
        }
    }


    //option array
    $options = array(
        'showLines' => 1,
        'showHeadings' => 1,
        'shaded' => 1,
        'shadeCol' => array(0.8, 0.8, 0.8),
        'fontSize' => $font,
        'titleFontSize' => $title_font,
        'rowGap' => 2,
        'colGap' => 2,
        'xPos' => 'center',
        'xOrientation' => 'center',
        'width' => $width,
        'maxWidth' => $max_width,
        'cols' => $cols);

    $pdf->ezTable($data, $col, "", $options);

    $pdf->ezStream();


// Column headings
// Data loading
    /* $data = $pdf->LoadData($link);
      $pdf->title = $title;
      $pdf->header = $header;
      $pdf->logo = "../shared/images\icons/logo.jpg";
      $pdf->SetFont('Arial', '', 14);
      $pdf->AddPage();
      $pdf->FancyTable($labels, $data, 40);
      $pdf->Output(); */
}

/*
 * #################################################################################################
 * Expoert to XLS <<under construction>>
 * ################################################################################################
 */

function export_xls($sql, $limits, $start, $duration, $records_count) {
    // global $labels,$empty_report_lang; // $labels is an array of labels of the fields
    // results is an array of data to be exported .
    // $results = validate_export_parameters ( $sql, $limits, $start, $duration, $records_count );
    return true;
}

?>