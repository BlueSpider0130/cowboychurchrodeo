 <?php
	if (! defined ( 'DIRECTACESS' ))
		exit ( 'No direct script access allowed' );
	

												
												
												foreach ( $result as $row ) {
													
													
													// fill array with current grouping fields
													
													foreach ( $group_by as $key => $val ) {
														
														
														$cur_group_ar [$val] = render ( $row [get_field_part($val,$row)], $cells [$val], $val );
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
																echo "<tr><td class='MainGroup'  colspan=" . $actual_columns_count . " >  <span style='float:".$align.";'>" . $labels [$group_by_source [$i]] . ":&nbsp; </span>" . render ( $row [get_field_part($group_by [$i],$row)], $cells [$group_by [$i]], $group_by [$i] ) . " </td></tr>";
															else
																echo "<tr><td class='SubGroup'  colspan=" . $actual_columns_count .  " >  <span style='float:".$align.";'>" . $labels [$group_by_source [$i]] . ":&nbsp; </span>" . render ( $row [get_field_part($group_by [$i],$row)], $cells [$group_by [$i]], $group_by [$i] ) . "</td></tr>";
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
													
// 													if (($group_by_count > 0 && $diff_index != - 1) || $cur_row == 0) { // if there is a change in grouping
// 														$i = 0;
														
// 														foreach ( $actual_fields_source as $key => $val ) {
// 															$temp = explode ( '.', $val );
// 															@$field_ = (count ( $table ) == 0) ? $val : $temp [1];
															
// 															if (in_array ( $field_, $group_by ))
// 																continue;
															
// 															if ($i == 0)
// 																echo "<tr>";
															
// 															echo "<td align='center' class='ColumnHeader'>$labels[$val]</td>";
															
// 															if ($i == $actual_columns_count - 1)
// 																echo "<td class='ColumnHeader' align='center'><!-- Detail View header --></td></tr>";
															
// 															$i ++;
// 														}
// 													}
													
													// print row data
													
													echo "<tr>";
													
													foreach ( $actual_fields as $key => $val ) {
														
													
															if ($row [get_field_part($val,$row)]  === "")
																echo "<tr><td align='left'   class='HColumnHeader'>$labels[$val]</td><td class='HTableCell' >" . render ( "&nbsp;", $cells [$val], $val ) . "</td><td class='HTableCell'  > &nbsp;</td></tr>";
															else
																echo "<tr><td align='left'  class='HColumnHeader'>$labels[$val]</td><td class='HTableCell' >" . render ( $row [get_field_part($val,$row)], $cells [$val], $val,false,false,true ) . "</td><td  class='HTableCell'  > &nbsp;</td></tr>";
														
													
													}
													$start = isset ( $_startRecord_index ) ? $_startRecord_index : 0;
													//echo '<td valign="middle" class="TableCell" ><a href="' . "Detailed-view.php" . '?detail=' . urlencode ( ( int ) ($start + $cur_row) ) . '" title="' . $detail_view_lang . '" ><img src="../shared/images/icons/row-print.png" alt="Detail View"></a></td>';
													echo '<tr><td colspan="2"> &nbsp;</td></tr>';
													echo "</tr>";
													
													
														// update new grouping
													
													if ($diff_index != - 1) {
														
														$last_group_ar = array ();
														
														foreach ( $group_by as $key => $val ) {
															
															$last_group_ar [$val] = render ( $row [get_field_part($val,$row)], $cells [$val], $val );
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