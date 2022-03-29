<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined ( 'DIRECTACESS' ) or die ( "Error 301: Access denied!" );
require_once("request.php");
require_once ("lib.php");
$_SESSION ['srm_f62014_page_key'] = "step_formatting"; // set current page
require_once 'activePages.php'; // require navigation handler

$columns = $_SESSION ["srm_f62014_fields"];
if (isset ( $_SESSION ["srm_f62014_cells"] )) {
	
	$cells = json_encode ( $_SESSION ["srm_f62014_cells"] );
} else {
	$cells = json_encode ( array () );
}

if (isset ( $_SESSION ["srm_f62014_conditional_formating"] )) {
	
	$formatting = json_encode ( $_SESSION ["srm_f62014_conditional_formating"] );
} else {
	$formatting = json_encode ( array () );
}

// print_r($_SESSION);
$index = - 1;
?>
<!--
<html>
	<head>
		<title>Cell Formatting</title>
	</head>
	<body  >
-->
<div id="tabs" class="container col-xs-12" ng-app="app"
	ng-controller="controller">
	<!-- -->
	<!-- Nav tabs nav nav-tabs -->
	<ul class="" style="font-size: 12px;">
		<li class="active"><a id="tables-nav" href="#tables" ng-click="setview('cells')" data-toggle="tab"><span
				class="glyphicon glyphicon-list-alt"></span> Cell Types</a></li>

		<li><a id="filters-nav" href="#filters" data-toggle="tab" ng-click="setview('conditional')"><span
				class="glyphicon glyphicon-filter"></span> Conditional Formatting</a></li>
	</ul>
	<!-- Tab panes -->
	 	
	<div class="tab-content"  >
 <div ng-show="loading"  style="background-color:#e6e6e6;width: 300px;  height: 30px; text-align:center; position: absolute;   top:200px;  bottom: 0; left: 0;
    right: 0;    margin: auto;border-style: solid;border-width: 2px;border-color: #e6e6e6;" >  

  <span ><strong style="background-color:#e6e6e6;color:blue;">Loading Please wait .... </strong></span>

</div> 
	
		<div class="tab-pane" id="tables" >
		
			<form name="labelForm" id="labelForm" role="form" method="post"
				action="<?php echo $_SERVER['PHP_SELF']; ?>"
				onsubmit="return false;">
				<div class="row">
					<div id="labels-error-container" class="col-xs-12"></div>
				</div>
				
               
				<div class="row row-as-ftr" id="cellsDiv" style="display:none;">
				
					
					<div class="row col-xs-12 div-as-th">
						<div class="col-xs-4"><span class="glyphicon glyphicon-list-alt"></span> Column </div>
						<div class="col-xs-8"><span class="glyphicon glyphicon-cog"></span>Cell Type <a href="" onclick="return false;" id="celltypeHelp"
								data-toggle="popover" data-original-title="" title=""> <img
								src="includes/images/help.png" width="15" height="15"
								border="0">
							</a>






						</div>
					</div>
					
				</div>
				<div class="row" style="min-height: 190px;">
					
					<div class="col-xs-12 table-container">

						<table id="labels-table" class="table table-hover">
									<?php
									
									foreach ( $columns as $val ) {
										$index ++;
										?>
										<tr>
								<td style="width: 37%;"><label
									for="lbl_<?php echo str_replace(array('.', ' '), array('_', '_'), $val); ?>"><?php echo $val; ?></label></td>
								<td style="width: 63%;">
									<div class="left-inner-addon">

										<i class="glyphicon glyphicon-edit"></i> <select
											ng-model="SelecetdcellTypes[<?php echo $index; ?>].cellType"
											ng-click="CellTypesClicked(<?php echo $index; ?>)"
											ng-init="SelecetdcellTypes[<?php echo $index; ?>].column = <?php echo "'".$val."'";  ?>">
											<option ng-repeat="type in cellTypes" value="{{type.value}}"
												ng-selected="type.value == SelecetdcellTypes[<?php echo $index; ?>].cellType ">{{type.key}}</option>
										</select> 
										
										<input id="<?php echo "appendedText".$index; ?>"
											name="<?php echo "appendedText".$index; ?>"
											ng-model="SelecetdcellTypes[<?php echo $index; ?>].appendedText"
											size="8" type="text"
											ng-disabled="disableAppendedText[<?php echo $index; ?>]" />
									</div>
								</td>
							</tr>
									<?php } ?>
								</table>
					</div>
					
				</div>
			</form>
		</div>
		<!-- end of first tab -->




		<div class="tab-pane" id="filters">


			<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post"
				name="myform" onsubmit="return false;">
				<!-- Tables Filters -->

				<div class="row">


					<div class="row col-xs-12">
						<div class="form-group col-xs-6 padding-left-xs">
							<label for="filter_left_field">Field</label>
							<div class="left-inner-addon">
								<i class="glyphicon glyphicon-pushpin"></i> <select
									name="filter_left_field" id="filter_left_field"
									class="form-control" ng-model="column">
									  
									  <?php
											foreach ( $columns as $val ) {
												
												echo "<option>$val</option>";
											}
											?>
									
									</select>
							</div>
						</div>
						<!-- field for filter -->
						<div class="form-group col-xs-5 padding-left-xs">
							<label for="filterTypes">Filters</label>
							<div class="left-inner-addon">
								<i class="glyphicon glyphicon-filter"></i> <select
									name="filterTypes" class="form-control"
									ng-model="selectedFilter"
									ng-options="filter as filter.key for filter in filters track by filter.value">
									<option>Select a filter</option>
								</select>
							</div>
						</div>

						<div class="help-container col-xs-1">
							<a href="" onclick="return false;" id="conditionalFormattingHelp"
								data-toggle="popover" data-original-title="" title=""> <img
								src="includes/images/help.png" width="15" height="15"
								border="0">
							</a>
						</div>
					</div>
					<!-- filter type -->



					<div class="form-group col-xs-12 padding-left-xs"
						id="filterValueContainer">
						<div class="row" style="margin: 0px;">Filter value</div>
						<div class="left-inner-addon col-xs-3" style="padding: 0px;">
							<i class="glyphicon glyphicon-edit"></i> <input type="text"
								ng-model="filterValue1" class="form-control" name="filter_value"
								id="filter_value">
						</div>
						<div ng-show=" selectedFilter.value == 'between' ">
							<div class="col-xs-2"
								style="text-align: center; position: relative; left: -2px; top: 5px; font-size: 12px;">AND</div>
							<div class="left-inner-addon col-xs-4" style="padding: 0px;">
								<i class="glyphicon glyphicon-edit"></i> <input type="text"
									ng-model="filterValue2" class="form-control"
									name="filter_value2" id="filter_value2">
							</div>
						</div>


					</div>

					<div class="form-group col-xs-12 padding-left-xs" id="fontcolor">
						<div class="row" style="margin: 0px;">Color</div>
						<div class="left-inner-addon col-xs-4" style="padding: 0px;">

							<input ng-model="color" id='colorpicker' />
							<span> {{color}}</span>
						</div>

					</div>

					<div ng-show=false
						class="alert alert-info col-xs-12 padding-left-xs" id="fontcolor">



						{{error}}</div>











					<div class="row col-xs-8">
						<div class="form-group col-xs-12 padding-left-xs"
							id="filterValueContainer"></div>
					</div>
					<!-- add input or two for filter value -->

					<!--  if( $type == 2)  -->
					<div class="row col-xs-8" id="filters-date-info"></div>
					<!-- alert is date -->

					<div class="row col-xs-8">
						<div class="col-xs-3 padding-left-xs">
							<button class="btn btn-primary btn-block btn-xs"
								style="font-size: 12px;" ng-click="AddValidationRule()">Add</button/>
						</div>
						<div class="col-xs-3 padding-left-xs"
							style="position: relative; left: -15px;">
							<button name="filters_btn_remove"
								class="btn btn-primary btn-block btn-xs"
								style="font-size: 12px;" id="filters_btn_remove"
								ng-click="remove_rule(slectedRule)">Remove</button/>
						</div>
						<div class="col-xs-6"></div>
					</div>
					<!-- add, remove filters -->


					<div class="row col-xs-12" style="margin-top: 5px;">
						<div class="form-group col-xs-12 padding-left-xs">
							<div class="left-inner-addon">
								<i class="glyphicon glyphicon-pushpin"></i> <select
									name="tables_filters" size="5" class="form-control"
									id="tables_filters" style="height: 90px;" multiple
									ng-model="slectedRule">
									<option ng-repeat="rule in jsonConditionalFormattingRules"
										value="{{rule.id}}">{{rule.value}}</option>
								</select>
							</div>
						</div>
					</div>
					<!-- select filters -->
				</div>
				<!-- .row ( filter container ) -->

			</form>

		</div>
	</div>
	<div class="row" style="display:none;" id="buttonsDIV">
		<div class="col-xs-1"></div>
		<div class="col-xs-4">
			<button  name="btn_back" id="btn_back" class="btn btn-sunny btn-block" ng-click="back()">
				<span class="icon glyphicon glyphicon-backward"></span><span
					class="separator"></span> Back
			</button>
		</div>
		<div class="col-xs-2"></div>
		<div class="col-xs-4">
			<button  name="continue" id="btn_cont" ng-click="next()"
				class="btn btn-sunny btn-block">
				<span class="icon glyphicon glyphicon-forward"></span><span
					class="separator"></span> Next
			</button>
		</div>
		<div class="col-xs-1"></div>
	</div>
	<!-- .row (navigation buttons) -->

</div>
<!-- to complete index tags -->
</div>

</div>
</div>
</div>
<script src='includes/spectrum/spectrum.js'></script>
<script src='includes/angular/cellsApp.js'></script>
<script>
function get_time(){
	var currentdate = new Date(); 
	var datetime = "Last Sync: " + currentdate.getDate() + "/"
	                + (currentdate.getMonth()+1)  + "/" 
	                + currentdate.getFullYear() + " @ "  
	                + currentdate.getHours() + ":"  
	                + currentdate.getMinutes() + ":" 
	                + currentdate.getSeconds();
	return datetime;
}
    
    angular.module("app").value("cellCount",<?php echo count($columns)?>);
    angular.module("app").value("cells",<?php echo $cells;?>);
    angular.module("app").value("formatting",<?php echo $formatting;?>); 
    
</script>

<script>
			
			
			
			$(document).ready(function(){
				// step_3 
				$("#tabs").tabs();
				$("#colorpicker").spectrum({
			        color: "#fff",
			        preferredFormat: "hex",
			        showPaletteOnly: true,
			        togglePaletteOnly: true,
			        togglePaletteMoreText: 'more',
			        togglePaletteLessText: 'less',
			        hideAfterPaletteSelect:true,
			        palette: [
			                  ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
			                  ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
			                  ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
			                  ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
			                  ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
			                  ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
			                  ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
			                  ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			              ]
			    });
				
				// set images in header
				$("#page-header").empty();
				$("#page-header").append('<div id="img-container"><img src="includes/images/appearance.png" width="70" height="70"/></div>');
				$("#page-header").append('<div id="text-container"><h4>Conditional Formatting</h4>Set types of cells and their conditional formatting.</div>');
				// set images in header
				
				
			

		
			});
				
					
			
			
	</script>
<!-- end index tags -->
<!--
	</body>
</html>
-->
