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
	$_SESSION['srm_f62014_page_key'] = "data_source";
	require_once 'activePages.php';
	
	
	$auto_tables = array();
	$auto_columns = array();
	$db = clean_input($db);
	$result = $dbHandler->query("show tables from `$db`"); // get tables from current database to use it in auto complete
	// get columns from current database to use it in auto complete
	foreach($result as $key => $value){
		$auto_tables[] = "`".$value[0]."`";
		$result2 = $dbHandler->query("show columns from `".$value[0]."`");
		foreach($result2 as $k => $val){
			$auto_columns[] = "`".$val[0]."`";
		}
	}
	
	$listOfViews = array();
	$views = $dbHandler->query("SHOW FULL TABLES IN `$db` WHERE TABLE_TYPE LIKE 'VIEW'");
	foreach($views as $value) $listOfViews[] = $value[0];
	$numOfViews = $dbHandler->get_num_rows();
	
	$sql = (isset($_SESSION['srm_f62014_sql'])) ? $_SESSION['srm_f62014_sql'] : '';
	
    $sql = make_valid($sql); // validate sql
?>
<!--
<html>
	<head>
		<title>Query based report</title>
	</head>
	<body>
-->
		<div class="container col-xs-12">
			<form action="<?php echo($_SERVER['PHP_SELF']);?>" role="form" method="post" onsubmit="return false;">
				<input type='hidden' id='csrfToken' name='csrfToken'
			value='<?php echo $request_token_value; ?>' />
				
				<div class="row">
					<div class="col-xs-1"></div>
					<div id="error-container" class="col-xs-10">
						<!-- .alert -->
					</div>
					<div class="col-xs-1"></div>
				</div><!-- .row (error) -->
				
				<?php if(is_numeric($numOfViews) && $numOfViews !== '' && $numOfViews > 0) { ?>
					<div class="row">
						<div class="col-xs-1" ></div>
						<div class="form-group col-xs-10">
							<label for="sql">Load SQL query from an existed view (Optional)</label>
							<div class="left-inner-addon">
								<i class="glyphicon glyphicon-pushpin"></i>
								<select id="views" class="form-control" name="views">
									<option value="None">None</option>
									<?php 
										$selected = '';
										foreach($listOfViews as $value)
										{
											if(isset($_SESSION['srm_f62014_view']) && $value === $_SESSION['srm_f62014_view']) $selected = 'selected';
											else $selected = '';
											echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>'; 
										}
									?>
								</select>
							</div>
						</div>
						<div class="help-container col-xs-1">
							<a href="" id="viewsHelp" onClick="return false;">
								<img src="includes/images/help.png" width="15" height="15" border="0">
							</a>
						</div>
					</div><!-- .row ( views ) -->
				<?php } ?>
				
				<div class="row">
					<div class="col-xs-1" ></div>
					<div class="form-group col-xs-10">
						<label for="sql">Enter Query</label>
						<div class="left-inner-addon">
							<i class="glyphicon glyphicon-edit"></i>
							<textarea id="sql" class="form-control textarea-btn-inside" name="sql" rows="7" ><?php echo $sql; ?></textarea>
						</div>
					</div>
					<div class="help-container col-xs-1">
						<a href="" id="sqlHelp" onClick="return false;">
							<img src="includes/images/help.png" width="15" height="15" border="0">
						</a>
					</div>
				</div><!-- .row ( sql textarea ) -->
				
				
				
				<div class="row" style="position: relative;top: -10px;">
					<div class="col-xs-8"></div>
					<div class="col-xs-3">
						<button name="btn_validate" class="btn btn-primary btn-block btn-xs" style="font-size: 12px;" id="btn_validate" >Validate</button/> 
					</div>
					<div class="col-xs-1"></div>
				</div><!-- .row (validate btn) -->
				
				<br />
					
				<div class="row">
					<div class="col-xs-1"></div>
					<div class="col-xs-4">
						<button name="btn_back" id="btn_back" class="btn btn-sunny btn-block">
							<span class="icon glyphicon glyphicon-backward"></span><span class="separator"></span> Back
						</button>
					</div>
					<div class="col-xs-2"></div>
					<div class="col-xs-4">
						<button name="btn_continue" id="btn_cont" class="btn btn-sunny btn-block">
							<span class="icon glyphicon glyphicon-forward"></span><span class="separator"></span> Next
						</button>
					</div>
					<div class="col-xs-1"></div>
				</div><!-- .row (navigation buttons) -->
			</form>
		</div><!-- end of this page -->
		<!-- to complete index tags -->
		</div>
		
	</div>
</div>
</div>
<!-- end index tags -->
		<script type="text/javascript">
				 
			
			$(function() {
				// set header images
				$("#page-header").empty();
				$("#page-header").append('<div id="img-container"><img src="includes/images/sql.png" width="70" height="70"/></div>');
				$("#page-header").append('<div id="text-container"><h4>Enter SQL Query</h4>Please enter valid SQL Query</div>');
				// set auto complete
				var availableTags = [
					"ACCESSIBLE`",  "ADD", "ALL ", "ALTER", "ANALYZE", "AND ", "AS",
					"ASC", "ASENSITIVE ", "BEFORE", "BETWEEN", "BIGINT ", "BINARY",
					"BLOB", "BOTH ", "BY", "CALL", "CASCADE ", "CASE", "CHANGE",
					"CHAR ", "CHARACTER", "CHECK", "COLLATE ", "COLUMN", "CONDITION",
					"CONSTRAINT ", "CONTINUE", "CONVERT", "CREATE ", "CROSS", "CURRENT_DATE",
					"CURRENT_TIME ", "CURRENT_TIMESTAMP", "CURRENT_USER", "CURSOR ", "DATABASE",
					"DATABASES", "DAY_HOUR ", "DAY_MICROSECOND", "DAY_MINUTE", "DAY_SECOND ",
					"DEC", "DECIMAL", "DECLARE ", "DEFAULT", "DELAYED", "DELETE ", "DESC",
					"DESCRIBE", "DETERMINISTIC ", "DISTINCT", "DISTINCTROW", "DIV ", "DOUBLE",
					"DROP", "DUAL ", "EACH", "ELSE", "ELSEIF ", "ENCLOSED", "ESCAPED", "EXISTS ",
					"EXIT", "EXPLAIN", "FALSE ", "FETCH", "FLOAT", "FLOAT4 ", "FLOAT8", "FOR", "FORCE ",
					"FOREIGN", "FROM", "FULLTEXT ", "GENERAL[a]", "GRANT", "GROUP ", "HAVING", "HIGH_PRIORITY",
					"HOUR_MICROSECOND ", "HOUR_MINUTE", "HOUR_SECOND", "IF ", "IGNORE", "IGNORE_SERVER_IDS[b]",
					"IN ", "INDEX", "INFILE", "INNER ", "INOUT", "INSENSITIVE", "INSERT ", "INT", "INT1", "INT2 ",
					"INT3", "INT4", "INT8 ", "INTEGER", "INTERVAL", "INTO ", "IS", "ITERATE", "JOIN ", "KEY", "KEYS",
					"KILL ", "LEADING", "LEAVE", "LEFT ", "LIKE", "LIMIT", "LINEAR ", "LINES", "LOAD", "LOCALTIME ",
					"LOCALTIMESTAMP", "LOCK", "LONG ", "LONGBLOB", "LONGTEXT", "LOOP ", "LOW_PRIORITY", "MASTER_HEARTBEAT_PERIOD[c]",
					"MASTER_SSL_VERIFY_SERVER_CERT ", "MATCH", "MAXVALUE", "MEDIUMBLOB ", "MEDIUMINT", "MEDIUMTEXT", "MIDDLEINT ",
					"MINUTE_MICROSECOND", "MINUTE_SECOND", "MOD ", "MODIFIES", "NATURAL", "NOT ", "NO_WRITE_TO_BINLOG", "NULL",
					"NUMERIC ", "ON", "OPTIMIZE", "OPTION ", "OPTIONALLY", "OR", "ORDER ", "OUT", "OUTER", "OUTFILE ", "PRECISION",
					"PRIMARY", "PROCEDURE ", "PURGE", "RANGE", "READ ", "READS", "READ_WRITE", "REAL ", "REFERENCES", "REGEXP", "RELEASE ",
					"RENAME", "REPEAT", "REPLACE ", "REQUIRE", "RESIGNAL", "RESTRICT ", "RETURN", "REVOKE", "RIGHT ", "RLIKE", "SCHEMA",
					"SCHEMAS ", "SECOND_MICROSECOND", "SELECT", "SENSITIVE ", "SEPARATOR", "SET", "SHOW ", "SIGNAL", "SLOW[d]", "SMALLINT ",
					"SPATIAL", "SPECIFIC", "SQL ", "SQLEXCEPTION", "SQLSTATE", "SQLWARNING ", "SQL_BIG_RESULT", "SQL_CALC_FOUND_ROWS",
					"SQL_SMALL_RESULT ", "SSL", "STARTING", "STRAIGHT_JOIN ", "TABLE", "TERMINATED", "THEN ", "TINYBLOB", "TINYINT",
					"TINYTEXT ", "TO", "TRAILING", "TRIGGER ", "TRUE", "UNDO", "UNION ", "UNIQUE", "UNLOCK", "UNSIGNED ", "UPDATE",
					"USAGE", "USE ", "USING", "UTC_DATE", "UTC_TIME ", "UTC_TIMESTAMP", "VALUES", "VARBINARY ", "VARCHAR", "VARCHARACTER",
					"VARYING ", "WHEN", "WHERE", "WHILE ", "WITH", "WRITE", "XOR ", "YEAR_MONTH", "ZEROFILL",
					<?php
						foreach($auto_tables as $t) echo '"'."$t".'",' ;
						foreach($auto_columns as $c) echo '"' . "$c". '",';
					?>
				];
					
				function split( val ) {
					return val.split( / / );
				}
					
				function extractLast( term ) {
					return split( term ).pop();
				}
				
				// don't navigate away from the field on tab when selecting an item
				$( "#sql" ).bind( "keydown", function( event ) {
					if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "ui-autocomplete" ).menu.active ) 
					{
						event.preventDefault();
					}
				})
				.autocomplete({
					minLength: 0,
					source: function( request, response ) {
						// delegate back to autocomplete, but extract the last term
						response( $.ui.autocomplete.filter(
						availableTags, extractLast( request.term ) ) );
					},
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					select: function( event, ui ) {
						var terms = split( this.value );
						// remove the current input
						terms.pop();
						// add the selected item
						terms.push( ui.item.value );
						// add placeholder to get the comma-and-space at the end
						terms.push( "" );
						this.value = terms.join( " " );
						return false;
					}
				});
			});
			// -----------------------------------------------------------------------------------
			$(document).ready(function(){
			
				$("#views").change(function(){
					var selectedView = $(this).val();
					$.ajax({
						url: "services/step_3_sql.php",
						type: "post",
						data: "selected_view="+selectedView,
						success: function(data){
							$("#sql").val(data);
						},
						error: function(){
							
						}
					});
				});
			
				$("#btn_validate").mousedown(function(){
					var sql = $("#sql").val();
					$.ajax({
						url: "services/step_3_sql.php",
						type: "post",
						data: "validate_sql="+encodeURIComponent(sql),
						success: function(data){
							$("#error-container").empty();
							var is_success = data.search("success");
							if(is_success !== -1){ //SELECT * FROM `orders` 
								data = data.split("|");
								$("#error-container").append("<div class='alert alert-success'>Valid SQL statement, Returns "+data[1]+" rows</div>");
								
							}else{
								if(data === '') $("#error-container").append("<div class='alert alert-danger'> Server Error! </div>");
								else $("#error-container").append("<div class='alert alert-danger'>"+data+"</div>");
								
							}
						},
						error: function(){
						
						}
					});
					
				});
				
				$("#btn_cont").mousedown(function(){
					var sql = $("#sql").val();
					$.ajax({
						url: "services/step_3_sql.php",
						type: "post",
						data: "continue_sql="+encodeURIComponent(sql),
						success: function(data){
							$("#error-container").empty();
							var is_success = data.search("success");
							if(is_success !== -1){
								//data = data.split("|");
								//if(data[0] === "success"){
									nextToPage("2");
									SwitchStatusDone();
								//}else{
								//	 $("#error-container").append("<div class='alert alert-danger'> Invalid SQL statement </div>");
								//	 SwitchStatusError();
								//}
							}else{
								if(data === '') $("#error-container").append("<div class='alert alert-danger'> No response from serevr </div>");
								else $("#error-container").append("<div class='alert alert-danger'>"+data+"</div>");
								SwitchStatusError();
							}
						},
						error: function(){
						
						}
					});
				});
				
				
				$("#btn_back").mousedown(function(){
					backToPage("0");
				});	
			});
			
		</script>
<!--
	</body>
</html>
-->
