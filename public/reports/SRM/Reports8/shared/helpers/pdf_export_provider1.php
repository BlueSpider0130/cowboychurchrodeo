<?php

/*
 * #################################################################################################
 * export with TCPDF provider WITH sub totals
 * ################################################################################################
 */

function export_pdf_with_sub_total_TCPDF_provider($results, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug) {
    global $custom_logo, $sub_totals_obj, $sub_totals, $records_per_page, $title, $cells, $file_name, $labels, $title, $empty_report_lang, $default_page_size, $fields, $header, $footer;


    // having two dimentional array
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header("Content-Disposition:attachment;filename=downloaded.pdf");
    header('Content-Transfer-Encoding: binary');
    $date = "Date: " . date("Y-m-d H:i:s");

    $pdf = new MYPDF($oriantation, $pagesize, $title, $date);
    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->AddPage();


    $data = array(array());
    $cols = array();
    $pdf->setFontSubsetting(true);
    // set font
    $pdf->SetFont('freeserif', '', 12);

    foreach ($results as $key => $group) {
        // groups title
        $pdf->SetFont('freeserif', 'B', 14);
        $pdf->writeHTML('<span style="color: #337AB7; font-weight: bold;">'.$sub_totals['group_by'] . ': ' . $key.'</span>', true, 0, true, 0, 'L');
        $pdf->ln();

        $pdf->SetFont('freeserif', '', 9);
        $tbl = '
        <style>
            table { 
                width: 100%; 
            }
            th {
                border: 0px solid #CCCCCC;
               
                display: inline-block;
                vertical-align:middle;
               
               
            }
                td{
                border: 0px solid #CCCCCC;
                display: inline-block;
                vertical-align:middle;
            
            }
        </style>
        <table cellpadding="2"  nobr="true">
        <tr  style="background-color:#ECF0F1;  ">';
        //table header
        foreach ($fields as $v) {

            $tbl .= ' <th align="center">';
            //$tbl .= '<span style="font-weight: bold;  line-height: 2em;  vertical-align: middle;  display: inline-block;">';
            $tbl .= $labels[$v];
            //$tbl .= '</span>';
            $tbl .= '</th>';
        }
        $tbl .= '</tr>';
        $group_size = count($group);
        //table body

        foreach ($group as $row) {

            $tbl .= '<tr>';
            foreach ($row as $k => $v) {
                $tbl .= ' <td align="center">';
                $tbl .= $v;
                $tbl .= '</td>';
            }
            $tbl .= '</tr>';
        }
        // subtotal row
        if (array_keys($results)[count($results) - 1] != $key || $group_size === $sub_totals_obj->get_records_count($key)) {
            $tbl .= '<tr>';
            $col_index = -1;
            foreach ($fields as $f) {
                $col_index++;
                $is_first = ($col_index === 0) ? true : false;
                if ($is_first) {
                    $tbl .= ' <td align="center">';
                    $tbl .= $sub_totals_obj->apply_sub_total_function($key, $f, false, $is_first);
                    $tbl .= '</td>';
                } else {
                    $tbl .= ' <td align="center" style="font-weight:bold;background-color:#E6EEFD;color:#337AB7;">';
                    $tbl .= $sub_totals_obj->apply_sub_total_function($key, $f, false, $is_first);
                    $tbl .= '</td>';
                }
            }
            $tbl .= '</tr>';
        }
        $tbl .= '</table>';
        //table render
        $pdf->writeHTML($tbl, true, false, false, false, '');
    }
    //grand total title
    $pdf->SetFont('freeserif', 'B', 14);
    $pdf->writeHTML('<span style="color: #337AB7; font-weight: bold;">' . $sub_totals_obj->get_title() . '</span>', true, 0, true, 0, 'L');
    $pdf->ln();
    // grand table
    $Grandtbl = ' <style>
    table { 
        width: 100%; 
    }
    th {
        border: 0px solid #CCCCCC;
        
        display: inline-block;
        vertical-align:middle;
        align:center;
       
    }
        td{
        border: 0px solid #CCCCCC;
        display: inline-block;
        vertical-align:middle;
        align:center;
    
    }
</style>
    <table border="1" cellpadding="2" nobr="true">
    <tr  style="background-color:#ECF0F1;">';
    foreach ($fields as $v) {
        $Grandtbl .= ' <th  align="center">';
        $Grandtbl .= $labels[$v];
        $Grandtbl .= '</th>';
    }
    $Grandtbl .= '
        </tr>';
    $Grandtbl .= '<tr>';
    $column_index = -1;
    foreach ($fields as $f) {
        $column_index++;
        $cell_grand_total = $sub_totals_obj->get_grand_totals($f);
        if ($column_index == 0 && empty($cell_grand_total)) {
            $Grandtbl .= '<td align="center" style="font-size: 10px;color: #fff; background-color: #337AB7; font-weight: bold; text-align:enter; padding: 10px 0; height:30px;">';
            $Grandtbl .= str_replace(" ", " <br/>",$sub_totals_obj->get_title() );
            $Grandtbl .= '</td>';
        } else {
            $Grandtbl .= '<td  align="center" style="vertical-align:middle; color:#337AB7; background-color:#E6EEFD; font-weight:bold;  padding:10px; height:30px;">&nbsp;<br/>';
            $Grandtbl .= $cell_grand_total. "<br/>";
            $Grandtbl .= '</td>';
        }
    }
    $Grandtbl .= '</tr>';
    $Grandtbl .= '</table>';
    // render grand table 
    $pdf->SetFont('freeserif', '', 9);
    $pdf->writeHTML($Grandtbl, true, false, false, false, '');
    $pdf->Output('Exported_report.pdf');
}

/*
 * #################################################################################################
 * export with TCPDF provider WITHout sub totals
 * ################################################################################################
 */

function export_pdf_TCPDF_provider($results, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug) {
    global $custom_logo, $sub_totals, $records_per_page, $title, $cells, $file_name, $labels, $title, $empty_report_lang, $default_page_size, $fields, $header, $footer;


    // having two dimentional array
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header("Content-Disposition:attachment;filename=downloaded.pdf");
    header('Content-Transfer-Encoding: binary');
    $date = "Date: " . date("Y-m-d H:i:s");

    $pdf = new MYPDF($oriantation, $pagesize, $title, $date);
    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->AddPage();


    $data = array(array());
    $cols = array();
    $pdf->setFontSubsetting(true);
    // set font




    $pdf->SetFont('freeserif', '', 9);
    $tbl = '
        <style>
            table { 
                width: 100%; 
            }
            th {
                border: 0px solid #CCCCCC;
               
                display: inline-block;
                vertical-align: middle;
               
               
            }
                td{
                border: 0px solid #CCCCCC;
                display: inline-block;
                vertical-align: middle;
            
            }
        </style>
        <table cellpadding="2"  nobr="true">
        <tr  style="background-color:#ECF0F1;  ">';
    //table header
    foreach ($fields as $v) {

        $tbl .= ' <th align="center">';
        //$tbl .= '<span style="font-weight: bold;  line-height: 2em;  vertical-align: middle;  display: inline-block;">';
        $tbl .= $labels[$v];
        //$tbl .= '</span>';
        $tbl .= '</th>';
    }
    $tbl .= '</tr>';

    //table body

    foreach ($results as $row) {

        $tbl .= '<tr>';
        foreach ($row as $k => $v) {
            $tbl .= ' <td align="center">';
            $tbl .= $v;
            $tbl .= '</td>';
        }
        $tbl .= '</tr>';
    }

    $tbl .= '</table>';
    //table render
    $pdf->writeHTML($tbl, true, false, false, false, '');


    $pdf->Output('Exported_report.pdf');
}

/*
 * #################################################################################################
 * export with TCPDF overrite provider class
 * ################################################################################################
 */

class MYPDF extends TCPDF {

    public function __construct($orientation, $format, $title, $date) {
        $this->title = $title;
        $this->date = $date;
        $this->orientation = $orientation;
        parent::__construct($orientation, $unit = 'mm', $format, $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false);
    }

    //Page header
    public function Header() {
        // Logo
        $this->SetY(15);
        // Set font
        // Title
        $this->setFontSubsetting(true);
        $this->SetFont('freeserif', 'B', 14);
        $this->Cell(0, 10, $this->title, 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->Cell(0, 10, $this->date, 0, false, 'R', 0, '', 0, false, 'M', 'M');


        $this->Line(12, 20, $this->w - 12, 20);
        $this->Line(12, 21, $this->w - 12, 21);
        $this->Ln();
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->setFontSubsetting(true);
        $this->SetFont('freeserif', '', 12);
        if ($this->orientation == "landscape") {
            $this->Line(12, 195, $this->w - 12, 195);
        } else
            $this->Line(12, 281, $this->w - 12, 281);
        // Set font
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}
