 <?php
	if (! defined ( 'DIRECTACESS' ))
		exit ( 'No direct script access allowed' );
	$span = "colspan='" . count ( $fields ) . (+ 1) . "'";
	?>
<table border="0"
	<?php
	// width of report
	
	 if ($_print_option ==1 || $_print_option ==2)
		echo "width='700'";
	else
		echo "width ='100%'";
	?>
	align="center" cellpadding="2" cellspacing="2" class="MainTable">




	<!-- ******************** start custom header ******************** !-->

            <?php
												if (! empty ( $header )) {
													?>

                <tr>

		<td <?php echo $span; ?> valign="top"><?php echo $header; ?></td>

	</tr>

	<tr>

		<td <?php echo $span; ?> valign="top" class="Separator"></td>

	</tr>

                <?php
												}
												?>

            <!-- ******************** end custom header ******************** !-->
            <?php if (trim($title) != '') { ?>
                <tr>

		<td <?php echo $span; ?> height="33" valign="top" class="Title"><?php echo $title; ?></td>

	</tr>
            <?php } ?>
            <?php
												if (! empty ( $group_by )) {
													?>





                <tr>

		<td <?php echo $span; ?> class="Separator">&nbsp</td>

	</tr>



                <?php
												}
												
												$cur_grouped = array ();
												
												$saved_grouped = array ();
												
												$records = 0;
												
												$state = true; // flag for toggling
												
												if ($empty_search_parameters) {
													if (check_debug_mode() == 1) {
														send_log_info($maintainance_email);
													}
													die("<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$empty_search_parameters_lang</td></tr>");
												}
												
												if ($possible_attack === true) {
													if (check_debug_mode () == 1) {
														send_log_info ( $maintainance_email );
													}
													die ( "<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$no_specials_lang</td></tr>" );
													exit ();
												}
												
												if ($nRecords == 0 || $empty_Report ||count ( $result ) < 1 || empty ( $result )) {
													if (check_debug_mode () == 1) {
														send_log_info ( $maintainance_email );
													}
													die ( "<tr><td style=\"text-align: left;padding-left: 39px;\" $span class='MainGroup'>$empty_report_lang</td></tr>" );
												} else {
													
													/* Table headers **************************** */
													
													// the table header
													
													echo "<tr><td ></td>

	  </tr>";
													
													// the columns header
													
													echo "<tr >";
													
													// drawing the other fields
													
													foreach ( $group_by_source as $g ) {
														
														echo "<td  align='center' class='ColumnHeader'><b>".array_change_key_case($labels,CASE_LOWER)[strtolower($g)]."</b> </td>";
													}
													
													foreach ( $actual_fields_source as $key => $val ) {
														$temp = explode ( '.', $val );
														$field_ = "";
														if (count ( $table ) == 0) {
															$field_ = $val;
														} elseif (isset ( $temp [1] )) {
															$field_ = $temp [1];
														}
														if (in_array ( $field_, $group_by ))
															continue;
														else {
															
															echo "<td  align='center' class='ColumnHeader'><b>".array_change_key_case($labels,CASE_LOWER)[strtolower($val)]."</b> </td>";
														}
													}
													
													echo "<td class='ColumnHeader' align='center'><!-- Detail View header --></td>";
													echo "</tr>";
												}
												/* header ends here ******************************* */
												
												/* * *********************************************** */
												
												/* body here *************************************** */
												
												foreach ( $result as $row ) {
													
													// $row = mysql_fetch_array($link,MYSQL_ASSOC);
													// filling array with current grouping vals
													$row = arr_to_lower($row);
													foreach ( $group_by as $val ) {
														
														$cur_grouped [$val] = $row [get_field_part($val,$row)];
													}
													
													// checking the variations
													
													if (count ( $saved_grouped ) != 0) {
														
														$index = grouping_diff_index ( $cur_grouped, $saved_grouped );
													} else {
														
														if ($records == 0) {
															
															$index = 0; // intialize the structure
														} else {
															
															$index = - 1; // No grouping and the structure is intialized
														}
													}
													echo "<tr class='mainpage' >";
												
													if ($index == - 1) {
														
														for($i = 0; $i < $levels; $i ++) {
															
															echo "<td class='mainpage'>&nbsp</td>";
														}
													} else {
														
														for($i = 0; $i < $index; $i ++) {
															
															echo "<td class='mainpage'>&nbsp</td>";
														}
													}
													
													if ($index != - 1) {
														
														// things that done only when there is variations
														// if($records != 0 )echo"</table></td></tr>";
														
														if (! empty ( $group_by )) {
															
															for($i = $index; $i < $levels; $i ++) {
																
																if ($i == 0 && $index == 0) {
																	
																	// main grouping
																	
																	echo "<td align='center' class='TableCell' >" . render ( $row [get_field_part($group_by [0],$row)], array_change_key_case($cells,CASE_LOWER) [strtolower($group_by [0])], $group_by [0] ) . "</td>";
																} else {
																	
																	// sub grouping
																	
																	echo "<td class='TableCell' align='center' >" . render ( $row [get_field_part($group_by [$i],$row)], array_change_key_case($cells,CASE_LOWER) [strtolower($group_by [$i])], $group_by [$i] ) . "</td>";
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
													
													foreach ( $fields as $f ) {
														
														if ($row [get_field_part($f,$row)] === "")
															$row [$f] = "&nbsp&nbsp";
														
														if (in_array ( $f, $group_by )) {
															
															continue;
														}
														if ($state)
															
															echo "<td align='center' class='AlternateTableCell'>" . render ( $row [get_field_part($f,$row)], array_change_key_case($cells,CASE_LOWER) [strtolower($f)], $f ) . "</td>";
														
														else
															echo "<td align='center' class='AlternateTableCell'>" . render ( $row [get_field_part($f,$row)], array_change_key_case($cells,CASE_LOWER) [strtolower($f)], $f ) . "</td>";
														
														$state = ! $state;
													}
													//$start = isset($_startRecord_index)? $_startRecord_index : 0;
													// echo '<td valign="middle" class="TableCell" ><a href="'."Detailed-view.php".'?detail='. urlencode((int)($start +  $records)).'" title="'.$detail_view_lang .'" ><img src="../shared/images/icons/row-print.png" alt="Detail View"></a></td>';
													echo "</tr>";
													
													// updating saved array
													
													foreach ( $group_by as $v ) {
														
														$saved_grouped [$v] = $row [get_field_part($v,$row)];
													}
													
													$records ++;
												} // ending of main while loop
												  // echo"</table></td></tr>";
												?>

            <!--*****************************-->

	<!-- ******************** start custom footer ******************** !-->

            <?php
												if (! empty ( $footer )) {
													
													echo "<tr><td $span > $footer</td></tr>";
												}
												?>

            <!-- ******************** end custom footer ******************** !-->