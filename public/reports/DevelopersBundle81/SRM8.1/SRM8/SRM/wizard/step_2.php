<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined ( 'DIRECTACESS' ) or die ( "Error 301: Access denied!");
require_once("request.php");

$_SESSION ['srm_f62014_page_key'] = "step_2";
require_once 'activePages.php';
$host_name = (isset ( $_SESSION ['srm_f62014_host'] )) ? $_SESSION ['srm_f62014_host'] : $default_mysql_host;
$user_name = (isset ( $_SESSION ['srm_f62014_user'] )) ? $_SESSION ['srm_f62014_user'] : $default_mysql_user;
$password = (isset ( $_SESSION ['srm_f62014_pass'] )) ? $_SESSION ['srm_f62014_pass'] :  $default_mysql_pass;
$database_name = (isset ( $_SESSION ['srm_f62014_db'] )) ? $_SESSION ['srm_f62014_db'] : $default_mysql_db;
$template_value = (isset ( $_SESSION ['template_value'] ) && ! empty ( $_SESSION ['template_value'] )) ? $_SESSION ['template_value'] : 'no';
$all_templates = (isset ( $_SESSION ["all_templates"] ) && ! empty ( $_SESSION ["all_templates"] ) && is_array ( $_SESSION ["all_templates"] )) ? $_SESSION ["all_templates"] : array ();
$data_source = (isset ( $_SESSION ['srm_f62014_datasource'] )) ? $_SESSION ['srm_f62014_datasource'] : 'table';
require_once "services/functions.php"
?>
<div class="container col-xs-12">
	<!-- style="border: 1px solid black;" -->
	<form id="myForm" name="myForm"
		action="<?php echo($_SERVER['PHP_SELF']); ?>" role="form"
		method="post" onsubmit="return false;">

		<input type='hidden' id='csrfToken' name='csrfToken'
			value='<?php echo $request_token_value; ?>' />

		<div class="row">
			<div class="col-xs-10"></div>
			<div class="col-xs-2"></div>
		</div>
		<!-- .row (title) -->

		<div class="row">
			<div class="col-xs-1"></div>
			<div id="error-container" class="col-xs-10">
				<!-- .alert -->
			</div>
			<div class="col-xs-1"></div>
		</div>
		<!-- .row (error) -->

		<div class="row">
			<div class="col-xs-1"></div>
			<div class="form-group col-xs-10">
				<label for="host_name">Host name</label>
				<div class="left-inner-addon">
					<i class="glyphicon glyphicon-tasks"></i> <input name="host_name"
						class="form-control font-size-lg" type="text" id="host_name"
						placeholder="Host name" value="<?php echo $host_name; ?>" />
				</div>
			</div>
			<div class="help-container col-xs-1">
				<a href="" onClick="return false;" id="hostHelp"> <img
					src="includes/images/help.png" width="15" height="15" border="0">
				</a>
			</div>
		</div>
		<!-- .row (host name) -->

		<div class="row">
			<div class="col-xs-1"></div>
			<div class="form-group col-xs-10">
				<label for="user_name">Username</label>
				<div class="left-inner-addon">
					<i class="glyphicon glyphicon-user"></i> <input name="host_name"
						class="form-control font-size-lg" type="text" id="user_name"
						placeholder="Username" value="<?php echo $user_name; ?>" />

				</div>
			</div>
			<div class="help-container col-xs-1">
				<a href="" id="userHelp" onClick="return false;"> <img
					src="includes/images/help.png" width="15" height="15" border="0">
				</a>
			</div>
		</div>
		<!-- .row (user name) -->

		<div class="row">
			<div class="col-xs-1"></div>
			<div class="form-group col-xs-10">
				<label for="password">Password</label>
				<div class="left-inner-addon">
					<i class="glyphicon glyphicon-lock"></i> <input name="password"
						class="form-control font-size-lg" type="password" id="password"
						placeholder="Password" />
				</div>
			</div>
			<div class="help-container col-xs-1">
				<a href="" id="passHelp" onClick="return false;"> <img
					src="includes/images/help.png" width="15" height="15" border="0">
				</a>
			</div>
		</div>
		<!-- .row (password) -->

		<div class="row">
			<div class="col-xs-1"></div>
			<div class="form-group col-xs-10">
				<label for="database_name">Database</label>
				<div class="left-inner-addon">
					<i class="glyphicon glyphicon-floppy-disk"></i> <input type="text"
						name="database_name" placeholder="Database"
						class="form-control font-size-lg" id="database_name"
						value="<?php echo $database_name; ?>">
				</div>
			</div>
			<div class="help-container col-xs-1">
				<a href="" id="dbHelp" onClick="return false;"> <img
					src="includes/images/help.png" width="15" height="15" border="0">
				</a>
			</div>
		</div>
		<!-- .row (select database) -->



		<div class="row">
			<div class="col-xs-4"></div>
			<div class="col-xs-4">
				<button name="btn_connect" class="btn btn-sunny btn-block"
					id="btn_connect">
					
						<?php
						if (! is_connected ())
							echo '<span class="icon glyphicon glyphicon-link"></span><span
						class="separator"></span> Connect';
						else
							echo "Disconnect";
						?>
						
				</button>
			</div>
			<div class="col-xs-4"></div>
		</div>
		<!-- .row (connect btn) -->

		<div id="form-two"
			<?php
			
			if (! isset ( $_SESSION ['srm_f62014_validate_key'] ) || (isset ( $_SESSION ['srm_f62014_validate_key'] ) && $_SESSION ['srm_f62014_validate_key'] !== md5 ( "srm_f62014_valid_1010" )))
				echo "style='display: none;'"?>>


			<div class="row">
				<div class="col-xs-1"></div>
				<div class="form-group col-xs-10">
					<label for="database_name">Load optiona from a saved template</label>
					<div class="left-inner-addon">
						<i class="glyphicon glyphicon-floppy-disk"></i> <select
							id="template_name" name="template_name" placeholder="Template"
							class="form-control font-size-lg" id="Template_name">
							<option value="no">No existing templates for this database</option>
						</select>
					</div>
					<br />
					<div style="text-align: right;">
						<a id="load_template" class="cr-hand btn btn-primary btn-xs"> Load
							Template</a>
					</div>
					<br />
				</div>
				<div class="help-container col-xs-1">
					<a href="" id="TemplateHelp" onClick="return false;"> <img
						src="includes/images/help.png" width="15" height="15"
						border="0">
					</a>
				</div>
			</div>
			<!-- .row (select database) -->



			<div class="row">
				<div class="col-xs-1"></div>
				<div class="form-group col-xs-10">
					<label for="cmb_data_source">Data Source</label>
					<div class="left-inner-addon">
						<i class="glyphicon glyphicon-dashboard"></i> <select
							name="data_source" class="form-control font-size-lg"
							id="cmb_data_source">
							<option value="table"
								<?php if($data_source === 'table') echo 'selected'?>>Table</option>
							<option value="sql"
								<?php if($data_source === 'sql') echo 'selected'?>>SQL Query</option>
						</select>
					</div>
				</div>
				<div class="help-container col-xs-1">
					<a href="" id="dsHelp" onClick="return false;"> <img
						src="includes/images/help.png" width="15" height="15"
						border="0">
					</a>
				</div>
			</div>
			<!-- .row (select data source) -->



			<div class="row" style="">
				<div class="col-xs-4">
					<!-- back button -->
				</div>
				<div class="col-xs-4"></div>
				<div class="col-xs-4">
					<button name="btn_cont" id="btn_cont"
						class="btn btn-sunny btn-block">
						<span class="icon glyphicon glyphicon-forward"></span><span
							class="separator"></span> Next
					</button>
				</div>
			</div>
			<!-- .row (navigation buttons) -->
		</div>

	</form>
</div>
<!-- .container (end of this page) -->

<!-- to complete index tags -->
</div>

</div>
</div>
</div>
<!-- end index tags -->
<script>
			/* step_2.php
			 * myForm
			 * host_name
			 * user_name
			 * password
			 * database_name
			 * cmb_data_source
			 */
			$(document).ready(function(){
				
				<?php
						
						if (is_connected ())
							echo "var connection_status = 'connected';";
						else
							echo "var connection_status = 'disconnected';";
						
						?>

				
				$("#page-header").empty();
				$("#page-header").append('<div id="img-container"><img src="includes/images/mysql-icon.png" width="70" height="70"/></div>');
				$("#page-header").append('<div id="text-container"><h4>Connect to MySQL</h4>Please enter MySQL database parameters</div>');
                //load templates
                <?php
																
																if (! empty ( $all_templates )) {
																	$all_templates = json_encode ( $all_templates );
																	
																	?>
                
                var alltemplates = new Array();
                var saved_template = <?php echo "'" . $template_value . "'"; ?>;               
               alltemplates =  eval(<?php echo $all_templates; ?>);                     
				  if(alltemplates){
						if(alltemplates.constructor === Array && alltemplates.length > 0 ){
						  var counter = 0;
							$.each(alltemplates, function(key, value) { 
								 if(typeof value.name != "undefined" && typeof value.title != "undefined"){
                                     if(counter == 0){
                                    		$("#template_name").empty().append('<option selected="selected" value="no">Select a template </option>');
                							$("#load_template").text("Load Selected Template");
                                     }
							  
								$("#template_name")
							         .append($("<option></option>")
							                    .attr("value",value.name.replace(/[&\/\\#,+();$~%.'":*?<>{}]/g, ''))
							                    .text(value.title.replace(/[&\/\\#,+();$~%.'":*?<>{}]/g, ''))); 
								 }
								 counter++
 
							});
							//case where there is a saved template selection
							if( saved_template != "no"){
							$("#template_name").val(saved_template);
							$("#load_template").text("Unload Selected Template");
							}

						}
                                
                   }

              <?php
																}
																
																?>
				 
				$("#btn_connect").mousedown(function(e){
					e.preventDefault();

					//case disconnect
				
					if(connection_status == "connected"){
                                            console.log(connection_status);
						  $.ajax({
                              type: "POST",
                              data:"stay=1",
                              url: "services//disconnect.php"
                          }).done(function(){
                              connection_status = "disconnected";
                              location.replace("../wizard/?id=0");
                              
                          });

                          return true;

					}
					
					var host = $("#host_name").val();
					var user = $("#user_name").val();
					var token = $("#csrfToken").val();					
					var pass = $("#password").val();
					var db = $("#database_name").val();
					$.ajax({
						url: "services/step_2.php",
						type: "post",
						data: "host="+encodeURIComponent(host)+"&user="+encodeURIComponent(user)+"&pass="+encodeURIComponent(pass)+"&db="+encodeURIComponent(db)+"&token="+token,
						
						success: function(data){
							
							
							data = jQuery.parseJSON(data);
							
							$("#error-container").empty();
							if(data.result === "success")
							{
								connection_status = "connected";
								alertify.success("Connected success");
								$("#form-two").show("slow");
								//lood templates
								//***********************************
                                 if(data.templates){
                                    
     								if(data.templates.constructor === Array && data.templates.length > 0 ){
                                       var counter = 0;
     									$.each(data.templates, function(key, value) { 
                                           
                                            if(typeof value.name != "undefined" && typeof value.title != "undefined"){
                                                if(counter == 0){
             									$("#template_name").empty().append('<option selected="selected" value="no">Select a template </option>');
             									$("#load_template").text("Load Selected Template");             									
                                                }
             									
                                                
     										$("#template_name")
     									         .append($("<option></option>")
     									                    .attr("value",value.name.replace(/[&\/\\#,+();$~%.'":*?<>{}]/g, ''))
     									                    .text(value.title.replace(/[&\/\\#,+();$~%.'":*?<>{}]/g, ''))); 
     										counter++;
							                 
                                            }
     									});
     								}
     	                                     
                                 }
								//**********************************
                                 $("#btn_connect").text("Disconnect");
								
							}
							else {
								
								if(data.result != "error" || !data.errorMessage ){
									 $("#error-container").append("<div class='alert alert-danger'>No response from server</div>");
								}
								else
								{   
									
									var error_message = data.errorMessage;
									 $("#error-container").append("<div class='alert alert-danger'>"+ error_message +"</div>");
								$("#form-two").hide("slow");
								}
							}
						},
						error: function(){
							$("#error-container").append("<div class='alert alert-danger'>Error contacting the server!</div>");
						}
					});				
				});


				function refresh(){
				    return function(){
				    	location.replace("../wizard/?id=0");
				    }
				}

				$("#load_template").mousedown(function(e){
					
					
					var size = $("#template_name option").size();
					var template = $("#template_name").val();
					var caption =  $(this).text().split(" ");
					var token = $("#csrfToken").val();
					var action = caption[0].toLowerCase();
					if(size < 2){
						alertify.error("There are no saved templates to load/unload");
					return;
					}

				
					if(template == "no"){
						alertify.error("Please select a template to load/unload");
						return;
					}
					
					$.ajax({
						url: "services/load_template.php",
						type: "post",
						data: "template="+template+"&action="+action+"&token="+token,
						
						success: function(data){
							//confirmation
							data = jQuery.parseJSON(data);

							//if success
						  
							if(typeof data.result  != "undefined" && data.result == "success"){
								if (action == "load") {
									$("a#load_template").text("Unload Selected Template");
									
									alertify.success("'" +template+"' is loaded successfully!, please click 'Next' to continue.");
									setTimeout(refresh(), 500);
									
									
								}
								else {
									$("a#load_template").text("Load Selected Template");
									alertify.success("'" +template+"' is unloaded successfully!.");
									setTimeout(refresh(), 500);
								}
								  //refresh
									
							}
							else if(typeof data.errorMessage  != "undefined" && data.result == "error" ){
								alertify.error("Error:" + data.errorMessage);

						   }
					else{

					}

						},error: function(){
							alertify.error("Error contacting the server");
							return;
						}
					});

				});

				
				$("#btn_cont").mousedown(function(e){
					e.preventDefault();
					var token = $("#csrfToken").val();
					
					
					var dataSource = $("#cmb_data_source").val();
					$.ajax({
						url: "services/step_2.php",
						type: "post",
						data: "token="+token+"&dataSource="+dataSource,
						success: function(data){
							
							data = jQuery.parseJSON(data);
							$("#error-container").empty();
							if(data.result === "success"){
								 nextToPage("1");
								SwitchStatusDone();
							}else if(typeof data.errorMessage  != "undefined" && data.result == "error" ){
									alertify.error("Error:" + data.errorMessage);
								//SwitchStatusError();
							}
						},
						error: function(){
							alertify.error("Error contacting the server");
							return;
							
						}
					});
				});
			});
		</script>
<!--
	</body>
</html>
-->
