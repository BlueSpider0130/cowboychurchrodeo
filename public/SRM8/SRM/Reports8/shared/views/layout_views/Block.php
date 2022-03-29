<?php
if (!defined('DIRECTACESS'))
    exit('No direct script access allowed');
$span = "colspan='" . count($fields) . (+ 1) . "'";
?>
   
    <?php
    if (!empty($group_by)) {
        ?>





        <tr>

            <td <?php echo $span; ?> class="Separator">&nbsp</td>

        </tr>



        <?php
    }

    $cur_grouped = array();

    $saved_grouped = array();

    $records = 0;

    $state = true; // flag for toggling

    
        /* Table headers **************************** */

        // the table header

        echo "<tr><td ></td>

          </tr>";

        // the columns header

        echo "<tr >";

        // drawing the other fields

        foreach ($group_by_source as $g) {

            echo "<td  align='center' class='ColumnHeaderBlock'><b>$labels[$g]</b> </td>";
        }

        foreach ($actual_fields_source as $key => $val) {
            $temp = explode('.', $val);
            $field_ = "";
            if (count($table) == 0) {
                $field_ = $val;
            } elseif (isset($temp [1])) {
                $field_ = $temp [1];
            }
            if (in_array($field_, $group_by))
                continue;
            else {

                echo "<td  align='center' class='ColumnHeaderBlock'><b>$labels[$val]</b> </td>";
            }
        }

        echo "<td class='ColumnHeader' align='center'><!-- Detail View header --></td>";
        echo "</tr>";
 
    /* header ends here ******************************* */

    /*     * *********************************************** */

    /* body here *************************************** */
    $current_value = "intial";
    foreach ($result as $row) {
        if (isset($sub_totals_obj) && strstr($sub_totals["group_by"], ".")) {
            $tmp = explode(".", $sub_totals["group_by"]);
            $sub_total_group_by = $tmp[1];
        } else {
            $sub_total_group_by = isset($sub_totals["group_by"]) ? $sub_totals["group_by"] : "";
        }


        if (isset($sub_totals_obj) &&
                array_key_exists($sub_total_group_by, $row)) {
            $last_value = $current_value;
            $current_value = ($row[$sub_total_group_by] === Null || $row[$sub_total_group_by] === "" || $row[$sub_total_group_by] === 0 || empty($row[$sub_total_group_by]) || is_null($row[$sub_total_group_by])) ? "" : $row[$sub_total_group_by];
            $last_subgroup_in_report = $sub_totals_obj->get_last_sub_group();
            $sub_total_max = $sub_totals_obj->get_records_count($current_value);

            $sub_total_previous_value_max = $sub_totals_obj->get_records_count($last_value);


            if ($current_value != $last_value) {
                //case last value is extended
                if (isset($sub_total_counter) && $sub_total_counter != 0 && $sub_total_counter != $sub_total_previous_value_max - 1) {
                    //extended group case

                    $sub_totals_obj->render_sub_total($actual_fields, $last_value, $layout);
                    if ($current_value === $last_subgroup_in_report)
                        $sub_totals_obj->draw_grand_total($actual_fields);
                }
                $sub_total_counter = $sub_totals_obj->get_row_index_in_group($row, $current_value);
            } else {

                $sub_total_counter++;
            }
        }


        foreach ($group_by as $val) {

            $cur_grouped [$val] = $row [get_field_part($val, $row)];
        }

        // checking the variations

        if (count($saved_grouped) != 0) {

            $index = grouping_diff_index($cur_grouped, $saved_grouped);
        } else {

            if ($records == 0) {

                $index = 0; // intialize the structure
            } else {

                $index = - 1; // No grouping and the structure is intialized
            }
        }
        echo "<tr class='mainpage' >";

        if ($index == - 1) {

            for ($i = 0; $i < $levels; $i ++) {

                echo "<td class='mainpage'>&nbsp</td>";
            }
        } else {

            for ($i = 0; $i < $index; $i ++) {

                echo "<td class='mainpage'>&nbsp</td>";
            }
        }

        if ($index != - 1) {

            // things that done only when there is variations
            // if($records != 0 )echo"</table></td></tr>";

            if (!empty($group_by)) {

                for ($i = $index; $i < $levels; $i ++) {

                    if ($i == 0 && $index == 0) {

                        // main grouping

                        echo "<td align='center' class='TableCell' >" . render($row [get_field_part($group_by [0], $row)], $cells [$group_by [0]], $group_by [0]) . "</td>";
                    } else {

                        // sub grouping

                        echo "<td class='TableCell' align='center' >" . render($row [get_field_part($group_by [$i], $row)], $cells [$group_by [$i]], $group_by [$i]) . "</td>";
                    }
                }
            }

            // columns and table head
            // echo"<tr><td><table width='100%' cellspacing='0' cellpadding='10'>";
            // that's all the things that done only when there is variation in grouping array
        }

        // things that should be done weather or not there is a variations
        // adding a data row
        // $state = !$state;

        foreach ($fields as $f) {

            if ($row [get_field_part($f, $row)] === "")
                $row [$f] = "&nbsp";

            if (in_array($f, $group_by)) {

                continue;
            }
            if ($state)
                echo "<td align='center' class='AlternateTableCell'>" . render($row [get_field_part($f, $row)], $cells [$f], $f) . "</td>";
            else
                echo "<td align='center' class='AlternateTableCell'>" . render($row [get_field_part($f, $row)], $cells [$f], $f) . "</td>";

            $state = !$state;
        }
        /*  $start = isset($_startRecord_index)? $_startRecord_index : 0;
          echo '<td valign="middle" class="TableCell" ><a href="'."Detailed-view.php".'?detail='. urlencode((int)($start +  $records)).'" title="'.$detail_view_lang .'" ><img src="../shared/images/icons/row-print.png" alt="Detail View"></a></td>';
         */

        echo "</tr>";

        // updating saved array

        foreach ($group_by as $v) {

            $saved_grouped [$v] = $row [get_field_part($v, $row)];
        }
        if (isset($sub_totals_obj) && $sub_total_counter === $sub_total_max - 1) {
            $sub_totals_obj->render_sub_total(array_unique(array_merge($group_by_source, $fields)), $current_value, $layout);
            if ($current_value === $last_subgroup_in_report)
                $sub_totals_obj->draw_grand_total(array_unique(array_merge($group_by_source, $fields)));
        }
        $records ++;
    } // ending of main while loop
    // echo"</table></td></tr>";
    ?>

    <!--*****************************-->

    <!-- ******************** start custom footer ******************** !-->

    <?php
    if (!empty($footer)) {

        echo "<tr><td class='headerfooter' $span > $footer</td></tr>";
    }
    ?>

    <!-- ******************** end custom footer ******************** !-->