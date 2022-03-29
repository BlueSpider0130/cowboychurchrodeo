 <?php
	if (! defined ( 'DIRECTACESS' ))
		exit ( 'No direct script access allowed' );
	$span = "colspan='" . count ( $fields ) . "'";
       $align = ($language == "ar" || $language == "iw" ) ? "right" : "left";
    
    
       
	?>


<table border="0"
	<?php
	// width of report
	
	if ($_print_option == 1 || $_print_option == 2)
		echo "width='700'";
	else
		echo "width ='100%'";
	?>
	align="center" cellpadding="2" cellspacing="0" class="MainTable">


	<!-- ******************** start custom header ******************** !-->

            <?php
												if (! empty ( $header )) {
													?>

                <tr>

		<td colspan="<?php echo $actual_columns_count ?>" valign="top"><?php echo($header); ?></td>

	</tr>

	<tr>

		<td colspan="<?php echo $actual_columns_count ?>" valign="top"
			class="Separator"></td>

	</tr>

                <?php
												}
												?>

            <!-- ******************** end custom header ******************** !-->

            <?php if (trim($title) != '') { ?>
                <tr>

		<td colspan="<?php echo $actual_columns_count ?>" valign="top"
			class="Title"><?php echo($title); ?></td>

	</tr>
            <?php } ?>
            <tr>

		<td class="Separator" colspan="<?php echo $actual_columns_count ?>"></td>

	</tr>

            <?php
												
												if ($empty_search_parameters) {
													if (check_debug_mode () == 1) {
														send_log_info ( $maintainance_email );
													}
													die ( "<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$empty_search_parameters_lang</td></tr>" );
												}
												
												if ($possible_attack == true) {
													if (check_debug_mode () == 1) {
														send_log_info ( $maintainance_email );
													}
													die ( "<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$no_specials_lang</td></tr>" );
													exit ();
												}
												
												if ($nRecords === 0 || $empty_Report ) {
													if (check_debug_mode () == 1) {
														send_log_info ( $maintainance_email );
													}
                                                                                                                                               
                                                                                                                                                                                                                                                    
													die ( "<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$empty_report_lang </td></tr>" );
												}
												
												
												
												foreach ( $result as $row ) {
													
													$row = arr_to_lower($row);
													// fill array with current grouping fields
													
													foreach ( $group_by as $key => $val ) {
														
														
														$cur_group_ar [$val] = render ( $row [get_field_part($val,$row)], array_change_key_case($cells, CASE_LOWER) [strtolower($val)], $val );
													}
													
													// print group by fields in case of grouping values variation
													
													if (count ( $last_group_ar ) != 0) {
														
														$diff_index = grouping_diff_index ( $cur_group_ar, $last_group_ar );
													} else {
														
														$diff_index = 0;
													}
													
													if ($diff_index != - 1) {
														
														for($i = $diff_index; $i < count ( $group_by_source ); $i ++) {
															
															if ($i == 0 && $diff_index == 0)
																echo "<tr><td class='MainGroup'  colspan=" . $actual_columns_count . " ><span style='float:".$align."'>" . array_change_key_case($labels, CASE_LOWER) [strtolower($group_by_source [$i])] . ": </span>" . render ( $row [get_field_part($group_by [$i],$row)], array_change_key_case($cells, CASE_LOWER) [strtolower($group_by [$i])], $group_by [$i] ) . " </td></tr>";
															else
																echo "<tr><td class='SubGroup'  colspan=" . $actual_columns_count . " ><span style='float:".$align.";'>" . array_change_key_case($labels, CASE_LOWER) [strtolower($group_by_source [$i])] . ":</span> " . render ( $row [get_field_part($group_by [$i],$row)], array_change_key_case($cells, CASE_LOWER) [strtolower($group_by [$i])], $group_by [$i] ) . "</td></tr>";
														}
														
														// echo"<tr><td height='15' $span class='TableHeader'></td></tr>";
														
														if ($cur_row == 0) {
															?>



                        <tr>

		<td><table
				<?php
															// width of report
															
															if ($_print_option != 0)
																echo "width='700'";
															else
																echo "width ='100%'";
															?>
				cellspacing="0" cellpadding="2" align='center'
				class="inner-data-table">

                                    <?php
														}
													}
													?> 



                            <?php
													// print table columns
													
													if (($group_by_count > 0 && $diff_index != - 1) || $cur_row == 0) { // if there is a change in grouping
														$i = 0;
														
														foreach ( $actual_fields_source as $key => $val ) {
															$temp = explode ( '.', $val );
															@$field_ = (count ( $table ) == 0) ? $val : $temp [1];
															
															if (in_array ( $field_, $group_by ))
																continue;
															
															if ($i == 0)
																echo "<tr>";
															
															echo "<td align='center' class='ColumnHeader'>".array_change_key_case($labels, CASE_LOWER)[strtolower($val)]."</td>";
															
															if ($i == $actual_columns_count - 1)
																echo "<td class='ColumnHeader' align='center'><!-- Detail View header --></td></tr>";
															
															$i ++;
														}
													}
													
													// print row data
													
													echo "<tr class='data-row'>";
													
													foreach ( $actual_fields as $key => $val ) {
														
														if ($toggle_row == 0)
															if ($row [get_field_part($val,$row)]  === "")
																echo "<td class='TableCell'>" . render ( "&nbsp;", $array_change_key_case($cells, CASE_LOWER) [strtolower($val)], $val ) . "</td>";
															else
																echo "<td class='TableCell'>" . render ( $row [get_field_part($val,$row)], array_change_key_case($cells, CASE_LOWER) [strtolower($val)], $val ) . "</td>";
														
														else 

														if ($row [get_field_part($val,$row)]  === "")
															echo "<td class='AlternateTableCell'>" . render ( "&nbsp;", array_change_key_case($cells, CASE_LOWER) [strtolower($val)], $val ) . "</td>";
														else
															echo "<td class='AlternateTableCell'>" . render ( $row [get_field_part($val,$row)], array_change_key_case($cells, CASE_LOWER) [strtolower($val)], $val ) . "</td>";
													}
													//$start = isset ( $_startRecord_index ) ? $_startRecord_index : 0;
													//echo '<td valign="middle" class="TableCell" ><a href="' . "Detailed-view.php" . '?detail=' . urlencode ( ( int ) ($start + $cur_row) ) . '" title="' . $detail_view_lang . '" ><img src="../shared/images/icons/row-print.png" alt="Detail View"></a></td>';
													echo "</tr>";
													
													// change toggling of rows
													
													if ($toggle_row == 0)
														$toggle_row = 1;
													else
														$toggle_row = 0;
														
														// update new grouping
													
													if ($diff_index != - 1) {
														
														$last_group_ar = array ();
														
														foreach ( $group_by as $key => $val ) {
															
															$last_group_ar [$val] = render ( $row [get_field_part($val,$row)], array_change_key_case($cells, CASE_LOWER) [strtolower($val)], $val );
														}
													}
													
													// increment current rows
													
													$cur_row ++;
												}
												?>



                    </table></td>

	</tr>

	<!-- ******************** start custom footer ******************** !-->

            <?php
												if (! empty ( $footer )) {
													
													echo "<tr><td > $footer</td></tr>";
												}
												?>

            <!-- ******************** end custom footer ******************** !-->