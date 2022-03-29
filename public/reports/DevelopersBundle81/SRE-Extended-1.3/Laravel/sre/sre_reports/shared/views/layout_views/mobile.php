<?php


if ($empty_search_parameters) {
    if (check_debug_mode() == 1) {
        send_log_info($maintainance_email);
    }
    die('<div class="nav" style="padding: 8px;text-align: center;color: white;">' . $empty_search_parameters_lang . '</div>');
    dump;
}

if ($possible_attack === true) {
    if (check_debug_mode() == 1) {
        send_log_info($maintainance_email);
    }
    die('<div class="nav" style="padding: 8px;text-align: center;color: white;">' . $no_specials_lang . '</div>');
    dump;
}

if ($nRecords == 0 || $empty_Report || count($result) < 1 || empty($result)) {
    if (check_debug_mode() == 1) {
        send_log_info($maintainance_email);
    }
    die('<div class="nav" style="padding: 8px;text-align: center;color: white;">' . $empty_report_lang . '</div>');
}
?>
<div class="grid">
    <table class="report-table">
<?php

use Sre\SmartReportingEngine\src\Engine\Constants;
foreach ($result as $row) {
    $row = arr_to_lower($row);

    //fill array with current grouping fields
    foreach ($group_by as $key => $val) {
        array_change_key_case($cur_group_ar, CASE_LOWER) [strtolower($val)] = array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))];
    }



    //print group by fields in case of grouping values variation
    if (count($last_group_ar) != 0) {

        $diff_index = grouping_diff_index($cur_group_ar, $last_group_ar);
    } else {

        $diff_index = 0;
    }



    if ($diff_index != -1) {
        if (count($group_by_source) > 0)
            echo '</table><br / >';
        for ($i = $diff_index; $i < count($group_by_source); $i++) {

            if ($i == 0 && $diff_index == 0)
                echo "<div class='group-row'>" . array_change_key_case($labels, CASE_LOWER)[strtolower($group_by_source[$i])] . ": " . render(array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($group_by[$i], $row))], array_change_key_case($cells, CASE_LOWER)[strtolower($group_by[$i])], $group_by[$i]) . " </div>";
            else
                echo "<div class='sub-row'>" . array_change_key_case($labels, CASE_LOWER)[strtolower($group_by_source[$i])] . ": " . render($row[get_field_part($group_by[$i], $row)], array_change_key_case($cells, CASE_LOWER)[strtolower($group_by[$i])], $group_by[$i]) . "</div>";
        }
        if (count($group_by_source) > 0)
            echo '<table class="report-table">';

        //echo"<tr><td height='15' $span class='TableHeader'></td></tr>";





        if ($cur_row == 0) {
            ?>





                <?php }
            }
            ?> 



            <?php
            //print table columns

            if (($group_by_count > 0 && $diff_index != -1) || $cur_row == 0) { //if there is a change in grouping

                $i = 0;

                foreach ($actual_fields_source as $key => $val) {
                    $temp = explode('.', $val);
                    @$field_ = (count($table) == 0) ? $val : $temp[1];
                    if (in_array($field_, $group_by))
                        continue;

                    if ($i == 0)
                        echo "<thead><tr>";
                    $mobile_hide = '';
                    if ($i > 1 && $i < 5)
                        $mobile_hide = 'data-hide="phone"';
                    else if ($i > 4)
                        $mobile_hide = 'data-hide="phone,tablet"';
                    if ($i == 0)
                        $mobile_hide .= ' data-class="expand"';
                    echo "<th $mobile_hide >" . array_change_key_case($labels, CASE_LOWER)[strtolower($val)] . "</th>";

                    if ($i == $actual_columns_count - 1)
                        echo "</tr></thead>";

                    $i++;
                }
            }





            //print row data

            echo "<tr>";

            foreach ($actual_fields as $key => $val) {
                if ($toggle_row == 0)
                    if (array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))] === "" || is_null(array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))]))
                        echo "<td align='center' class='TableCell'>" . "&nbsp;" . "</td>";
                    else
                        echo "<td align='center' class='TableCell'>" . render(array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))], array_change_key_case($cells, CASE_LOWER)[strtolower($val)], $val) . "</td>";

                else

                if (array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))] === "" || is_null(array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))]))
                    echo "<td align='center' class='AlternateTableCell'>" . "&nbsp;" . "</td>";
                else
                    echo "<td align='center' class='AlternateTableCell'>" . render(array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))], array_change_key_case($cells, CASE_LOWER)[strtolower($val)], $val) . "</td>";
            }

            echo "</tr>";



            //change toggling of rows

            if ($toggle_row == 0)
                $toggle_row = 1;
            else
                $toggle_row = 0;



            //update new grouping

            if ($diff_index != -1) {

                $last_group_ar = array();

                foreach ($group_by as $key => $val) {

                    $last_group_ar[$val] = array_change_key_case($row, CASE_LOWER)[strtolower(get_field_part($val, $row))];
                }
            }



            //increment current rows

            $cur_row ++;
        }
        ?>

    </table>
</div>

