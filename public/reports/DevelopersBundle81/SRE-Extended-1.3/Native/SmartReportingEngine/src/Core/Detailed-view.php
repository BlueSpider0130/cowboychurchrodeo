<?php
/**
 * Smart Report Engine
 * Version 1.3.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : https://mysqlreportsengine.com/
 *
 */
define ( "DIRECTACESS", "true" );
/*
 * #################################################################################################
 * Load Report Files .
 * ################################################################################################
 */
require_once("request.php");
$is_email = false;
require_once("auto_load.php");

$report_url = basename(__DIR__);
$report_url .= ".php";
if(!file_exists($report_url)){
	$report_url = $file_name . ".php"	;
}

/*
 * #################################################################################################
 * Handling Detailed view
 * ################################################################################################
 */

if(isset($_CLEANED["detail"]) && check_numeric_parameter($_CLEANED["detail"]) && $_CLEANED["detail"] >= 0 && $_CLEANED["detail"] < $nRecords ){
	$selected_record = (int)$_CLEANED["detail"];
	if ($used_extension == "mysqli" || $used_extension == "mysql") {
		$sql [0] .= " limit ?,?";
		array_push ( $sql [1], $selected_record, 1 );
		$sql [2] .= "ii";
	} else {
		
			
		$sql [0] .= " limit $selected_record,1";
	}
	$flush = true;
	$result = query ( $sql [0], "Detailed view", $sql [1], $sql [2] );

}
else{
	Die("No selected records");
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Detail View</title>
<link href="../shared/styles/general.min.css" rel="stylesheet">
<link href="../shared/styles/blue.css" rel="stylesheet">
<link rel="stylesheet" href="../shared/styles/print.css" type="text/css"
	media="print" />
<link rel="stylesheet" href="../shared/Js/lightbox/css/lightbox.min.css" />

<style>
.panel-title {
	font-size: 24px;
	color: #337ab7;
}
</style>
</head>

<body>
	<div class="col-xs-8 col-xs-push-2 col-md-8 col-md-push-2">
		<div class="panel-heading">
			<h3 class="panel-title">
				<strong><?php echo escape($detail_view_lang);?></strong>
				<div class="hidden-print pull-right">
					<div class="btn-group">
						<button type="button" id="print" onclick="window.print();"
							title="Print" class="btn btn-primary">
							<img src="../shared/images/icons/print.png"
								style="vertical-align: middle; width: 16px; margin-right: 5px;">
							<?php echo escape($print_lang);?>
						</button>
						<button type="submit" id="back" title="Cancel Printing"
							class="btn btn-default" onclick="window.location = '<?php echo $report_url ;?>' ">
							<?php echo escape($back_lang);?>
							</button>
					</div>
				</div>
				<div class="clearfix"></div>
			</h3>
		</div>
		<div class="panel-body">
<?php
foreach ( $result as $row ) {
    $row = arr_to_lower($row);
	foreach ( $labels as $k => $v ) {
		
		?>
<fieldset class="form-horizontal">
				<div class="form-group">
					<label class="col-xs-6 control-label"><?php echo $labels[$k]; ?></label>
					<div class="col-xs-6"><?php echo render(array_get_insensetive_element(get_field_part($k,$row),$row),$cells[$k],$k,false,false); ?></div>

				</div>
			</fieldset>
<?php
	}
}
?>
</div>
	</div>
<script type="text/javascript" src="../shared/Js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="../shared/Js/lightbox/js/lightbox.min.js"></script>
</body>
</html>