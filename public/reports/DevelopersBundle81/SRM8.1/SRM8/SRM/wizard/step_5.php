<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
	defined('DIRECTACESS') or die ("Error 301: Access denied!");
	require_once("request.php");
	require_once("lib.php");
	require_once 'checkSession.php';
	if(sessionBe4Step4() === false) 
	{
		header("location: $url?id=1");
		exit();
	}else if(sessionBe4Step5() === false)
	{
		header("location: $url?id=2");
		exit();
	}
	$_SESSION['srm_f62014_page_key'] = "step_5";
	require_once 'activePages.php';
	
	if(isset($_SESSION['srm_f62014_fields']) && is_array($_SESSION['srm_f62014_fields']) && count($_SESSION['srm_f62014_fields']) > 0) $allFields = $_SESSION['srm_f62014_fields'];
	else header("location: $url?id=2");
	// set sort by and group by fields to select from
	function print_table_fields()
	{
		global $allFields;
		static	$i = 0;

		$text ='<option value="None">None</option>';
		if (count($allFields) > 0)
		{
			foreach($allFields as $key => $value)
			{
				if(isset($_SESSION['srm_f62014_sort_by']) && isset($_SESSION['srm_f62014_sort_by'][$i][0]) && $value === $_SESSION['srm_f62014_sort_by'][$i][0])
					$text .= "<option value='$value' selected>$value</option>";
				else $text .= "<option value='$value'>$value</option>";			
			}
			$i++;
			echo $text;
		}
	}
	// get status if sort by descending or ascending
	function get_status($n)
	{
		if(isset($_SESSION['srm_f62014_sort_by']) && isset($_SESSION['srm_f62014_sort_by'][$n][1]) && $_SESSION['srm_f62014_sort_by'][$n][1] === 1) return 'checked';
		else return '';
	}

?>
<div id="tabs" class="container col-xs-12"><!-- -->
	<!-- Nav tabs nav nav-tabs -->
	<ul class="" style="font-size: 12px;">
		<li class="active"><a id="groups-nav" href="#groups" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Group by</a></li>
		<li><a id="sorts-nav" href="#sorts" data-toggle="tab"><span class="glyphicon glyphicon-stats"></span> Sort by</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="groups">
			<form name="form1" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return false;">
			<!-- Please select grouping levels  -->
			</select>
				<div class="row">
					<div class="col-xs-1"></div>
					<div class="form-group col-xs-5" style="margin: 0px; padding: 0px 5px 0px 0px;">
						<label for="allFields">Available Fields</label>
						<div class="left-inner-addon">
							<i class="glyphicon glyphicon-pushpin"></i>
							<select size="5" name="allFields" id="allFields" class="form-control" style="height: 150px;" multiple>							          
							<?php
								foreach($allFields as $val)
								{
									if(isset($_SESSION["srm_f62014_group_by"]))
									{
										if (!in_array($val, $_SESSION["srm_f62014_group_by"])) 
											echo "<option value='$val'>$val</option>";
									}else 
										echo "<option value='$val'>$val</option>";
								}
							?>
							</select>
						</div>
					</div>
					<div class="col-xs-1" style="padding-top: 15px;">
						<br />
						<div class="row" style="margin-top: 5px;">
							<button name="add" id="add" class="btn btn-default btn-block btn-xs">&gt;</button></div>
						
						<div class="row" style="margin-top: 5px;">
							<button name="remove" id="remove" class="btn btn-default btn-block btn-xs">&lt;</button></div>
						
						<div class="row" style="margin-top: 5px;">
							<button name="addAll" id="addAll" class="btn btn-default btn-block btn-xs">&gt;&gt;</button></div>
						
						<div class="row" style="margin-top: 5px;">
							<button name="removeAll" id="removeAll" class="btn btn-default btn-block btn-xs">&lt;&lt;</button></div>
					</div>
					<div class="form-group col-xs-5" style="margin: 0px; padding: 0px 0px 0px 5px;">
						<label for="selectedFields">Group By</label>
						<div class="left-inner-addon">
							<i class="glyphicon glyphicon-pushpin"></i>
							<select size="2" name="selectedFields" id="selectedFields" class="form-control" style="height: 150px;" multiple>
								<?php
									if(isset($_SESSION["srm_f62014_group_by"]))
									{
										foreach($_SESSION["srm_f62014_group_by"] as $val)
										{
											if(in_array($val, $allFields)) echo "<option value='$val'>$val</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
				</div>	<!-- .row (selectColumns) -->
				
				<div class="row" style="position: relative;top: -175px; left: 523px;width: 20px;">
					<a href="" id="groupbyHelp" onClick="return false;">
						<img src="includes/images/help.png" width="15" height="15" border="0">
					</a>
				</div><!-- help -->

			</form>
		</div>
		<div class="tab-pane active" id="sorts">
			<form id="sortForm" name="sortForm" action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return false;">
			
				<div class="row form-group">
					<div class="col-xs-2 to-right"><label for="fields1">1</label></div>
					<div class="col-xs-5">
						<select name="fields1" class="form-control" size="1" id="fields1"><?php print_table_fields();?></select>
					</div>
					<div class="col-xs-2">
						<label for="desc1">Descending</label>
					</div>
					<div class="col-xs-1"><input name="desc1" type="checkbox"  id="desc1" value="ON"  <?php echo get_status(0); ?>/></div>
					<div class="col-xs-2" style="position: relative; top: -10px;">
						<a href="" id="sortHelp" onClick="return false;">
							<img src="includes/images/help.png" width="15" height="15" border="0">
						</a>
					</div>
				</div>			
				
				<div class="row form-group">
					<div class="col-xs-2 to-right"><label for="fields2">2</label></div>
					<div class="col-xs-5">
						<select name="fields2" class="form-control" size="1" id="fields2"><?php print_table_fields();?></select>
					</div>
					<div class="col-xs-2">
						<label for="desc2">Descending</label>
					</div>
					<div class="col-xs-1"><input name="desc2" type="checkbox"  id="desc2" value="ON"  <?php echo get_status(1); ?>/></div>
					<div class="col-xs-2"></div>
				</div>						
				
				<div class="row form-group">
					<div class="col-xs-2 to-right"><label for="fields3">3</label></div>
					<div class="col-xs-5">
						<select name="fields3" class="form-control" id="fields3"><?php print_table_fields();?></select>
					</div>
					<div class="col-xs-2">
						<label for="desc3">Descending</label>
					</div>
					<div class="col-xs-1"><input name="desc3" type="checkbox"  id="desc3" value="ON"  <?php echo get_status(2); ?>/></div>
					<div class="col-xs-2"></div>
				</div>
				
				
				<div class="row form-group">
					<div class="col-xs-2 to-right"><label for="fields4">4</label></div>
					<div class="col-xs-5">
						<select name="fields4" class="form-control" id="fields4"><?php print_table_fields();?></select>
					</div>
					<div class="col-xs-2">
						<label for="desc4">Descending</label>
					</div>
					<div class="col-xs-1"><input name="desc4" type="checkbox"  id="desc4" value="ON"  <?php echo get_status(3); ?>/></div>
					<div class="col-xs-2"></div>
				</div>			

				
				<div class="row form-group">
					<div class="col-xs-2 to-right"><label for="fields5">5</label></div>
					<div class="col-xs-5">
						<select name="fields5" class="form-control" id="fields5"><?php print_table_fields();?></select>
					</div>
					<div class="col-xs-2">
						<label for="desc5">Descending</label>
					</div>
					<div class="col-xs-1"><input name="desc5" type="checkbox"  id="desc5" value="ON"  <?php echo get_status(4); ?>/></div>
					<div class="col-xs-2"></div>
				</div>

			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-1"></div>
		<div class="col-xs-4">
			<button name="btn_back" id="btn_back" class="btn btn-sunny btn-block">
				<span class="icon glyphicon glyphicon-backward"></span><span class="separator"></span> Back
			</button>
		</div>
		<div class="col-xs-3"></div>
		<div class="col-xs-4">
			<button name="continue" id="btn_cont" class="btn btn-sunny btn-block" >
				<span class="icon glyphicon glyphicon-forward"></span><span class="separator"></span> Next
			</button>
		</div>
	</div><!-- .row (navigation buttons) -->
</div>
<!-- end of the page -->
		<!-- to complete index tags -->
		</div>
	</div>
</div>
</div>
<!-- end index tags -->
<script>
	var fieldsInfo = <?php if(count($allFields) > 0) echo 'new Array("' . implode('", "', $allFields) . '")';
					else echo 'new Array()';?>;
					
	$(function(){
		$("#tabs").tabs();
		
		$("#page-header").empty();
		$("#page-header").append('<div id="img-container"><img src="includes/images/groupby.png" width="70" height="70"/></div>');
		$("#page-header").append('<div id="text-container"><h4>Group By</h4>Choose columns to group by it</div>');
		
		$("#groups-nav").click(function(){
		
			$("#page-header").empty();
			$("#page-header").append('<div id="img-container"><img src="includes/images/groupby.png" width="70" height="70"/></div>');
			$("#page-header").append('<div id="text-container"><h4>Group By</h4>Choose columns to group by it</div>');
			
		});
		
		$("#sorts-nav").click(function(){
		
			$("#page-header").empty();
			$("#page-header").append('<div id="img-container"><img src="includes/images/sort.png" width="70" height="70"/></div>');
			$("#page-header").append('<div id="text-container"><h4>Sort By</h4>Choose columns to sort by it</div>');
			
		});
		
		$("#add").mousedown(function(){
			add("allFields", "selectedFields");
		});
		
		$("#remove").mousedown(function(){
			remove("selectedFields", "allFields", fieldsInfo);
		});
		
		$("#addAll").mousedown(function(){
			addAll("allFields", "selectedFields");
		});
		
		$("#removeAll").mousedown(function(){
			removeAll("selectedFields", "allFields", fieldsInfo);
		});
		
		$("#btn_cont").mousedown(function(){
			$("#selectedFields option").prop("selected" ,true);
			var groupbyFields = $("#selectedFields").val();
			var groupbyAjax = (groupbyFields !== null) ? groupbyFields.join() : null;
			var sortsValues = $("#sortForm").serialize();
			$.ajax({
				url: "services/step_5.php",
				type: "post",
				data: "groupbyFields="+groupbyFields+"&"+sortsValues,
				success: function(data){
					data = data.trim();
					if(data === "success"){
						nextToPage("5");
						SwitchStatusDone();
					}else{
						// backToPage("2"); unexpected error
						// SwitchStatusError();
					}
				},
			});
		});
		
		$("#btn_back").mousedown(function(){
			backToPage("3");
		});
	});
</script>