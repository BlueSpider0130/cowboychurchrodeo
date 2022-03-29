<?php
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
            $last_subgroup_in_report = $sub_totals_obj->get_last_sub_group();
            $current_value = ($row[$sub_total_group_by] === Null || $row[$sub_total_group_by] === "" || $row[$sub_total_group_by] === 0 || empty($row[$sub_total_group_by]) || is_null($row[$sub_total_group_by])) ? "" : $row[$sub_total_group_by];
            $sub_total_max = $sub_totals_obj->get_records_count($current_value);
            $sub_total_previous_value_max = $sub_totals_obj->get_records_count($last_value);

            if ($current_value != $last_value) {
                //case last value is extended
                if (isset($sub_total_counter) && $sub_total_counter != 0 && $sub_total_counter != $sub_total_previous_value_max - 1) {
                    //extended group case

                    $sub_totals_obj->render_sub_total($actual_fields, $last_value, $layout);
                    if ($current_value === $last_subgroup_in_report)
                        $sub_totals_obj->draw_grand_total($actual_fields, $style_name);
                }
                $sub_total_counter = $sub_totals_obj->get_row_index_in_group($row, $current_value);
            } else {

                $sub_total_counter++;
            }
        }




        foreach ($group_by as $key => $val) {


            $cur_group_ar [$val] = render($row [get_field_part($val, $row)], $cells [$val], $val);
        }

        // print group by fields in case of grouping values variation



        if (count($last_group_ar) != 0) {

            $diff_index = grouping_diff_index($cur_group_ar, $last_group_ar);
        } else {

            $diff_index = 0;
        }

        if ($diff_index != - 1) {

            for ($i = $diff_index; $i < count($group_by_source); $i ++) {



                if ($i == 0 && $diff_index == 0) {

                    echo "<tr><td class='MainGroup'  colspan=" . $actual_columns_count . " ><span style='float:" . $align . "'>" . $labels [$group_by_source [$i]] . ":&nbsp;</span>" . render($row [get_field_part($group_by [$i], $row)], $cells [$group_by [$i]], $group_by [$i]) . " </td></tr>";
                } else {
                    echo "<tr><td class='SubGroup'  colspan=" . $actual_columns_count . " ><span style='float:" . $align . ";'>" . $labels [$group_by_source [$i]] . ":&nbsp;</span> " . render($row [get_field_part($group_by [$i], $row)], $cells [$group_by [$i]], $group_by [$i]) . "</td></tr>";
                }
            }

            // echo"<tr><td height='15' $span class='TableHeader'></td></tr>";

            if ($cur_row == 0) {
                ?>



                <tr>

                    <td>
                        <table <?php
                        // width of report

                       if ($_print_option != 0)
                          echo "width='700'";
                       else
                          echo "width ='100%'";
                        ?> cellspacing="0" cellpadding="2" align='center' class="inner-data-table">

                            <?php
                        }
                    }
                    ?>



                    <?php
                    // print table columns
                    draw_table_headers($group_by_count, $diff_index, $cur_row, $actual_fields_source, $group_by, $labels);

                    // print row data

                    echo "<tr class='data-row'>";

                    foreach ($actual_fields as $key => $val) {

                        if ($toggle_row == 0)
                            if ($row [get_field_part($val, $row)] === "")
                                echo "<td class='AlternateTableCell'>" . render("&nbsp;", $cells [$val], $val) . "</td>";
                            else
                                echo "<td class='AlternateTableCell'>" . render($row [get_field_part($val, $row)], $cells [$val], $val) . "</td>";

                        else

                        if ($row [get_field_part($val, $row)] === "")
                            echo "<td class='AlternateTableCell'>" . render("&nbsp;", $cells [$val], $val) . "</td>";
                        else
                            echo "<td class='AlternateTableCell'>" . render($row [get_field_part($val, $row)], $cells [$val], $val) . "</td>";
                    }
                    /* if (get_primary_key_column()) {
                      $Primary = get_record_primary_key_value($row);
                      echo '<td valign="middle" class="TableCell" ><a href="' . "Detailed-view.php" . '?detail=' . $Primary . '" title="' . $detail_view_lang . '" ><img src="../shared/images/icons/row-print.png" alt="Detail View"></a></td>';

                      } */
                    echo "</tr>";

                    // change toggling of rows

                    if ($toggle_row == 0)
                        $toggle_row = 1;
                    else
                        $toggle_row = 0;

                    // update new grouping

                    if ($diff_index != - 1) {

                        $last_group_ar = array();

                        foreach ($group_by as $key => $val) {

                            $last_group_ar [$val] = render($row [get_field_part($val, $row)], $cells [$val], $val);
                        }
                    }
                    if (isset($sub_totals_obj) && $sub_total_counter === $sub_total_max - 1) {
                        $sub_totals_obj->render_sub_total($actual_fields, $current_value, $layout);
                        if ($current_value === $last_subgroup_in_report) {
                            echo "<tr><td colspan='" . $actual_columns_count . "' class='MainGroup'><span style='float:left;'>" . $sub_totals_obj->get_title() . "</span></td></tr>";
                            draw_table_headers($group_by_count, $diff_index, $cur_row, $actual_fields_source, $group_by, $labels,true);
                            $sub_totals_obj->draw_grand_total($actual_fields,$style_name);
                        }
                    }

                    $cur_row ++;
                }
                ?>



            </table>
        </td>

    </tr>

    <!-- ******************** start custom footer ******************** !-->

    <?php
    if (!empty($footer)) {

        echo "<tr><td class='headerfooter'> $footer</td></tr>";
    }

    function draw_table_headers($group_by_count, $diff_index, $cur_row, $actual_fields_source, $group_by, $labels,$is_grand_total =false) {
        if ($is_grand_total ||(($group_by_count > 0 && $diff_index != - 1) || $cur_row == 0)) { // if there is a change in grouping
          
            echo "<tr>";
            foreach ($actual_fields_source as $key => $val) {
                if (strstr($val, ".")) {
                    $temp = explode('.', $val);
                    $field_ = $temp [1];
                } else {
                    $field_ = $val;
                }


                if (in_array($field_, $group_by))
                    continue;

        
                    

                echo "<td align='center' class='ColumnHeader'>$labels[$val]</td>";

               
            }
            echo "</tr>";
        }
    }
    ?>

    

