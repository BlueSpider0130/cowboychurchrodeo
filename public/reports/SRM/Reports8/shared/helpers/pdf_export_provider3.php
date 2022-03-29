<?php
/*
 * #################################################################################################
 * Expoerting with DOMPDF provider case there is sub totals
 * ################################################################################################
 */

function export_pdf_with_sub_total_dompdf_provider($link, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug = 0) {
    // validation of export parameters
    //  set_time_limit(180);
    global $sub_totals, $sub_totals_obj, $title, $header, $footer, $cells, $file_name, $labels, $title, $empty_report_lang, $default_page_size, $fields;
    if (!isset($default_page_size) || $default_page_size === "") {
        $default_page_size = "A3";
    }


    $span = "colspan='" . count($labels) . "'";
    $html = "";

    $col = array();
    $data = array(
        array()
    );
    $i = - 1;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="charset=utf-8" /> ';
    $html .= "<style>";


    $html .= ".Title {
    font-family: 'Montserrat', Montserrat-Regular;
    font-size: 20px;
    font-weight: bold;
    color: #337ab7;
    /*padding-left: 10px;*/
    padding: 9px 14px;
    margin-bottom: 14px;   
  
}";
    $html .= ".Group {
    font-family: 'Montserrat', Montserrat-Regular;
    font-size: 16px;
    font-weight: bold;
    color: #337ab7;
    /*padding-left: 10px;*/
    padding: 10px 10px;
    margin-bottom: 10px;   
  
}";

    $html .= ".ColumnHeader {
	    border-spacing: 1px 1px;
	    border-collapse: separate;
		font-family: 'Montserrat', Montserrat-Regular;
		font-size: 13px;
		font-weight: bold;
		color: #337ab7;
		background-color: #ecf0f1; /*#337ab7;*/
		height: 30px;
		
		
	   
	}";

    $html .= ".TableCell {
    font-family: 'Montserrat', Montserrat-Regular;
    font-size: 13px;
    color: #777;
    text-align: center;
    background-color: white;
    height: 30px;
    border-bottom-width: 1px;
    border-bottom-style: solid;
    border-bottom-color: #ddd;
   height: 20px;
    /*text-align: left;*/
}";

    $html .= "
             
            @page {
                margin: 0cm 0cm;
            }            
             body {
                margin-top: 2cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }


             header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 3cm;
            }
            
             .SubTotal {
               font-family: Montserrat, Arial, Helvetica, sans-serif;
               font-size: 12px;
               color: #337ab7;
               background-color: #ffffff;
              font-weight: bold;
              text-align: center;
              height: 30px;
              border-bottom-width: 1px;
              border-bottom-style: solid;
              border-top-width: 1px;
             border-top-style: solid; 
   
               }
          
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
            }";

    $html .= "</style>";
    $html .= '</head><body>';
    if ($oriantation === 'landscape')
        $page_number = '<script type="text/php">
        if ( isset($pdf) ) {
      
            $font = $fontMetrics->get_font("Montserrat, Helvetica, sans-serif", "normal");
            $size = 10;
            $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
            $y = 20;
            $x = 835;
            $pdf->text($x, $y, $pageText, $font, $size);
            
            
        }
    </script>';
    else
        $page_number = '<script type="text/php">
        if ( isset($pdf) ) {
      
            $font = $fontMetrics->get_font("Montserrat, Helvetica, sans-serif", "normal");
            $size = 10;
            $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
            $y = 15;
            $x = 800;
            $pdf->text($x, $y, $pageText, $font, $size);
     
        }
    </script>';
    $html .= '<header>';
    $html .= $page_number;
    $html .= '<h4 style="margin-left:50px;">' . $header . ' </h4>
        </header>

        <footer>
          <h4 style="margin-left:50px;">' . $footer . ' </h4>
        </footer>';
    $html .= "<main>";
    $html .= "<table align='center' cellpadding='4' cellspacing='1' class='MainTable' >";
    // header and title

    if (trim($title) != '') {

        $html .= "<tr>";

        $html .= "<td " . $span . "  valign='top' class='Title'>" . $title . "</td>";

        $html .= "</tr>";
    }



    if (count($link) > 0) {
        foreach ($link as $key => $group) {

            $html .= "<tr>";
            $html .= "<td " . $span . "  valign='top' class='TableCell'>" . "&nbsp;" . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td " . $span . "  valign='top' class='Group'>" . $sub_totals["group_by"] . ":" . $key . "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td " . $span . "  valign='top' class='TableCell'>" . "&nbsp;" . "</td>";
            $html .= "</tr>";

            $html .= "<tr>";

            foreach ($fields as $v) {

                $html .= "<td cellpadding='4' cellspacing='1' class='ColumnHeader' align='center' >";
                $html .= "<b>" . $labels [$v] . "</b>";
                $html .= "</td>";
            }
            $html .= "</tr>";
            foreach ($group as $row) {
                $html .= "<tr>";

                foreach ($fields as $f) {

                    $html .= "<td style='height:15px;' cellpadding='4' cellspacing='1' class='TableCell' >";
                    $html .= render($row[get_field_part($f, $row)], $cells[$f], $f, true, false, false);

                    $html .= "</td>";
                }
                $html .= "</tr>";
            }
            $html .= "<tr>";
            $col_index = -1;
            foreach ($fields as $f) {
                $col_index++;
                $is_first = ($col_index === 0) ? true : false;
                $html .= "<td style='height:15px;' cellpadding='4' cellspacing='1' class='SubTotal' >" . $sub_totals_obj->apply_sub_total_function($key, $f, false, $is_first) . "</b></td>";
            }
            $html .= "</tr>";
        }
    } else {
        $html .= "<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='TableCell'>$empty_report_lang</td></tr>";
    }
    $html .= "</table>";
    $html .= "</main>";
    $html .= "</body>";

    $html .= "</html>";


    $pdf = new Dompdf ();

    $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
    $pdf->set_option("isPhpEnabled", true);

    $pdf->load_html($html);
    $pdf->setPaper($default_page_size, $oriantation);
    $pdf->render();
    $pdf->stream($file_name);
}

/*
 * #################################################################################################
 * Expoerting with Dompdf provider case there is no sub totals
 * ################################################################################################
 */

function export_pdf_dompdf_provider($link, $pagesize, $oriantation, $top, $bottom, $left, $right, $width, $max_width, $font, $title_font, $limits, $start, $duration, $records_count, $debug = 0) {
    // validation of export parameters


    set_time_limit(180);
    global $datasource, $title, $cells, $file_name, $labels, $title, $empty_report_lang, $default_page_size, $fields;
    if (!isset($default_page_size) || $default_page_size === "") {
        $default_page_size = "A3";
    }


    $span = "colspan='" . count($labels) . "'";
    $html = "";

    $col = array();
    $data = array(
        array()
    );
    $i = - 1;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="charset=utf-8" /> ';
    // $html .= "<meta charset='UTF-8'>";
    // $html .= "<meta http-equiv='Content-Type' content='text/html;charset=UTF-8' />";

    $html .= "<style>";

    $html .= ".Title {
    font-family: 'DejaVu Sans Mono', monospace;
    font-size: 20px;
    font-weight: bold;
    color: #337ab7;
    /*padding-left: 10px;*/
    padding: 9px 14px;
    margin-bottom: 14px;   
  
}";

    $html .= ".ColumnHeader {
	    border-spacing: 1px 1px;
	    border-collapse: separate;
		font-family: 'DejaVu Sans Mono', monospace;
		font-size: 13px;
		font-weight: bold;
		color: #337ab7;
		background-color: #ecf0f1; /*#337ab7;*/
		height: 30px;
		
		
	   
	}";

    $html .= ".TableCell {
    font-family: 'DejaVu Sans Mono', monospace;
    font-size: 13px;
    color: #777;
    text-align: center;
    background-color: white;
    height: 30px;
    border-bottom-width: 1px;
    border-bottom-style: solid;
    border-bottom-color: #ddd;
   height: 20px;
    /*text-align: left;*/
}";

    $html .= "</style>";
    $html .= "</head><body>";
    $html .= "<table align='center' cellpadding='4' cellspacing='1' class='MainTable' >";
    // header and title

    if (trim($title) != '') {

        $html .= "<tr>";

        $html .= "<td " . $span . "  valign='top' class='Title'>" . $title . "</td>";

        $html .= "</tr>";
    }

    if (count($fields) > 0) {

        $html .= "<tr>";
        foreach ($fields as $v) {

            $html .= "<td cellpadding='4' cellspacing='1' class='ColumnHeader' align='center' >";
            $html .= "<b>" . utf8_decode($labels [$v]) . "</b>";
            $html .= "</td>";
        }
        $html .= "</tr>";
    }

    if (count($link) > 0) {
        foreach ($link as $row) {

            $html .= "<tr>";

            foreach ($fields as $f) {

                $html .= "<td style='height:15px;' cellpadding='4' cellspacing='1' class='TableCell' >";
                $html .= render($row[get_field_part($f, $row)], $cells[$f], $f, true, false, false);

                $html .= "</td>";
            }
            $html .= "</tr>";
        }
    } else {
        $html .= "<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='TableCell'>$empty_report_lang</td></tr>";
    }
    $html .= "</table>";
    $html .= "</body></html>";


    $dompdf = new Dompdf ();

    $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");

    $dompdf->load_html($html);
    $dompdf->setPaper($default_page_size, $oriantation);
    $dompdf->render();
    $dompdf->stream($file_name);
}

