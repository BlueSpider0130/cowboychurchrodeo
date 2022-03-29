<?php
/*
 * #################################################################################################
 * Expoerting with ezpdf provider case there is sub totals
 * ################################################################################################
 */

function export_pdf_with_sub_total_ezpdf_provider($results, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug = 0) {


    global $custom_logo, $sub_totals_obj, $sub_totals, $records_per_page, $title, $cells, $file_name, $labels, $title, $empty_report_lang, $default_page_size, $fields, $header, $footer;



    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header("Content-Disposition:attachment;filename=downloaded.pdf");
    header('Content-Transfer-Encoding: binary');

    $pdf = new Cezpdf($pagesize, $oriantation);

    $pdf->ezSetMargins($top + 40, $bottom + 10, $left, $right);
    $pattern = 'Page {PAGENUM} of {TOTALPAGENUM}';
    $date = "Date: " . date("Y-m-d H:i:s");
    if ($oriantation == "landscape")
        $pdf->ezStartPageNumbers(780, 30, 8, '', $pattern, 1);
    else
        $pdf->ezStartPageNumbers(530, 30, 8, '', $pattern, 1);


    $pdf->selectFont('../shared/pdf-old/fonts/Montserrat-Regular.ttf');

    $data = array(array());
    $cols = array();

    $prefrences = array('justification' => 'left');
///
    $all = $pdf->openObject();
    $pdf->saveState();
    if ($oriantation == "landscape") {




        $pdf->addText(350, 570, 18, $header);


        // $pdf->line(20, 530, 820, 530);
        $pdf->selectFont('Montserrat-Medium');

        // footer line and text
        $pdf->line(10, 40, 820, 40);
        $pdf->addText(350, 30, 8, $footer);

        //$pdf->addText(820, 560, 14, $date, 0, 'right');
    } else {


        // header line and text

        $pdf->addText(245, 810, 16, $header);
        // $pdf->line(20, 530, 820, 530);
        $pdf->selectFont('Montserrat-Medium');

        // footer line and text
        $pdf->line(20, 40, 580, 40);
        $pdf->addText(240, 30, 8, $footer);
    }
/////

    $pdf->restoreState();
    $pdf->closeObject();

    $pdf->addObject($all, 'all');

    $pdf->ezSetMargins(70, 50, 10, 10);
    //$pdf->ezText($title, 18, $prefrences);
    $pdf->ezText("", 10, $prefrences);
    $pdf->setLineStyle(0);
    if ($oriantation == "landscape") {
        $pdf->line(10, 520, 820, 520);
        $pdf->line(10, 515, 820, 515);
        $pdf->addText(10, 537, 18, $title);
        $pdf->addText(595, 537, 18, $date);
    } else {
        $pdf->line(10, 755, 580, 755);
        $pdf->line(10, 752, 580, 752);
        $pdf->addText(10, 780, 18, $title);
        $pdf->addText(400, 780, 14, $date);
    }
    // $pdf->ezText("", 15, array('justification' => 'center'));

    $col_index = -1;
    $pdf->selectFont('Montserrat-SemiBold');
    foreach ($fields as $v) {

        $col_index++;

        $cols[$v]['justification'] = 'center';

        $pdf->selectFont('Montserrat-SemiBold');
        $col[get_column_part($v)] = $labels [$v];
    }
    $options = array(
        // 'showLines' => 1,
        //'showHeadings' => 1,
        'shadeHeadingCol' => [0.92549, 0.94117647, 0.945098],
        'lineCol' => array(0.9189189, 0.9189189, 0.9189189),
        'shaded' => 0,
        'shadeCol' => array(0.8, 0.8, 0.8),
        'fontSize' => $font,
        'titleFontSize' => $title_font,
        'rowGap' => 2,
        'xPos' => 'center',
        'xOrientation' => 'center',
        'maxWidth' => $max_width,
        'cols' => $cols);

    $exported_records_count = 0;

    foreach ($results as $key => $group) {

        $pdf->ezText("", 15, $prefrences);
        //  $pdf->setStrokeColor(0.15, 0.4, 0.7);
        $pdf->selectFont('Montserrat-SemiBold');
        $pdf->ezText("<b>" . $sub_totals["group_by"] . ": " . $key . "</b>", 16, array('justification' => 'left'));
        $pdf->selectFont('Montserrat-SemiBold');
        $pdf->ezText("", 15, $prefrences);
        $group_size = count($group);
        $data = array();
        $row_index = -1;
        $last_subgroup_in_report = $sub_totals_obj->get_last_sub_group();

        foreach ($group as $row) {
            $row_index++;
            $col_index = -1;
            $pdf->selectFont('Montserrat-Light');
            foreach ($row as $k => $v) {
                //
                $col_index++;
                $column = get_column_part($k);
                $data[$row_index][$column] = $v;
            }
            $exported_records_count++;
        }
        if (array_keys($results)[count($results) - 1] != $key || $group_size === $sub_totals_obj->get_records_count($key)) {
            $row_index++;
            $col_index = -1;

            foreach ($fields as $f) {
                $col_index++;
                $is_first = ($col_index === 0) ? true : false;
                $pdf->selectFont('Montserrat-Light');
                $data[$row_index][get_column_part($f)] = "<b>" . $sub_totals_obj->apply_sub_total_function($key, $f, false, $is_first) . "</b>";

                if ($is_first) {
                    $data[$row_index][get_column_part($f) . 'Fill'] = [1, 1, 1];
                    $data[$row_index][get_column_part($f) . 'Color'] = [0, 0, 0];
                } else {
                    $data[$row_index][get_column_part($f) . 'Fill'] = [0.902, 0.933, 0.992];
                    $data[$row_index][get_column_part($f) . 'Color'] = [0.2, 0.4784, 0.7176];
                }
            }
        }
        $pdf->ezTable($data, $col, "", $options);
    }
    $prefrences = array(
        'justification' => 'left'
    );
    $data = array();
    $pdf->setColor(0.2, 0.4784, 0.7176);
    $pdf->ezText("", 15, $prefrences);
    $pdf->ezText("<b>" . $sub_totals_obj->get_title() . "</b>", 16, array('justification' => 'left'));
    $pdf->ezText("", 15, $prefrences);
    $row_index = 0;
    $col_index = -1;

    foreach ($fields as $f) {
        $col_index++;
        $cell_grand_total = $sub_totals_obj->get_grand_totals($f);
        if($col_index == 0 && empty($cell_grand_total))$cell_grand_total = $sub_totals_obj->get_title();
        $data[$row_index][get_column_part($f)] = "<b>" .  $cell_grand_total . "</b>";
        $data[$row_index][get_column_part($f) . 'Fill'] = [1, 1, 1];
        $data[$row_index][get_column_part($f) . 'Color'] = [0.2, 0.4784, 0.7176];
    }

    $pdf->ezTable($data, $col, "", $options);

    $pdf->ezStream();
}

/*
 * #################################################################################################
 * export with ezpdf provider case no sub totals
 * ################################################################################################
 */

function export_pdf_ezpdf_provider($link, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug = 0) {
    global $records_per_page, $title, $cells, $file_name, $labels, $title, $empty_report_lang, $default_page_size, $fields, $header, $footer;


    // having two dimentional array
    /*header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header("Content-Disposition:attachment;filename=downloaded.pdf");
    header('Content-Transfer-Encoding: binary');
    $pdf = new Cezpdf($pagesize, $oriantation);

    $pdf->ezSetMargins($top + 40, $bottom + 10, $left, $right);
    $pattern = 'Page {PAGENUM} of {TOTALPAGENUM}';
    $date = "Date: " . date("Y-m-d H:i:s");
    if ($oriantation == "landscape")
        $pdf->ezStartPageNumbers(780, 30, 8, '', $pattern, 1);
    else
        $pdf->ezStartPageNumbers(530, 30, 8, '', $pattern, 1);


    $pdf->selectFont('../shared/pdf-old/fonts/Times-Roman.afm');

    $data = array(array());
    $cols = array();

    $prefrences = array('justification' => 'left');
///
    $all = $pdf->openObject();
    $pdf->saveState();
    if ($oriantation == "landscape") {

        // header line and text

        $pdf->addText(350, 540, 14, $header);
        // $pdf->line(20, 530, 820, 530);
        

        // footer line and text
        $pdf->line(10, 40, 820, 40);
        $pdf->addText(350, 30, 8, $footer);
        $pdf->addText(820, 560, 14, $date, 0, 'right');
    } else {


        // header line and text

        $pdf->addText(240, 800, 14, $header);
        // $pdf->line(20, 530, 820, 530);


        // footer line and text
        $pdf->line(20, 40, 580, 40);
        $pdf->addText(240, 30, 8, $footer);
        $pdf->addText(580, 820, 14, $date, 0, 'right');
    }
/////

    $pdf->restoreState();
    $pdf->closeObject();

    $pdf->addObject($all, 'all');

    $pdf->ezSetMargins(70, 50, 10, 10);

    $pdf->ezText("$title", 18, array('justification' => 'left'));
    $pdf->ezText("", 10, $prefrences);
    $pdf->setLineStyle(2);
    if ($oriantation == "landscape") {
        $pdf->line(10, 490, 820, 490);
        $pdf->line(10, 487, 820, 487);
    } else {
        $pdf->line(10, 735, 580, 735);
        $pdf->line(10, 732, 580, 732);
    }
    $pdf->ezText("", 15, array('justification' => 'center'));

    $col_index = -1;
    foreach ($fields as $v) {
        $col_index++;

        $cols[
                $v]['justification'] = 'center';
        $col[] = "<b>" . $labels [$v] . "</b>";
    }
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

    $exported_records_count = 0;



    $row_index = -1;
    foreach ($link as $row) {

        $row_index++;
        $col_index = -1;
        foreach ($row as $k => $v) {
            //
            $col_index++;
            $data[$row_index][$col_index] = $v;
        }
        $exported_records_count++;
    }


    $pdf->ezTable($data, $col, "", $options);



    $pdf->ezStream();*/
    
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header("Content-Disposition:attachment;filename=downloaded.pdf");
    header('Content-Transfer-Encoding: binary');
    
    $pdf = new Cezpdf($pagesize, $oriantation);
       //  $pdf->setFontFamily('arial-unicode-ms');
         $pdf->selectFont('arial-unicode-ms');
    $pdf->ezSetMargins($top, $bottom, $left, $right);
    $pattern = 'Page {PAGENUM} of {TOTALPAGENUM}';
    if ($oriantation == "landscape")
        $pdf->ezStartPageNumbers($width - 20, 560, 10, '', $pattern, 1);
    else
        $pdf->ezStartPageNumbers($width - 20, 810, 9, '', $pattern, 1);


   

    $data = array(array());
    $cols = array();

    $prefrences = array(
        'justification' => 'center'
    );

    $pdf->ezText($title, 15, $prefrences);
  
    $pdf->ezText($header, 10, $prefrences);
    $pdf->ezText("", 15, $prefrences);
    $col_index = -1;
    $row_index = -1;
    foreach ($fields as $v) {
        $col_index++;

        $cols[
                $v]['justification'] = 'center';
        $col[$col_index] =  $labels [$v] ;
       
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
   $fp =  fopen("debug.txt","a+");
   fwrite($fp, print_r($data,true));
   fclose($fp);

    $pdf->ezStream();
}


