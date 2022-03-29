<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die ("Error 301: Access denied!");
require_once ("request.php");
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
$_SESSION['srm_f62014_page_key'] = "Sutotals";
require_once 'activePages.php';


$subtotal_checked = (isset ( $_SESSION["srp_subtotals_enabled"] ) && $_SESSION["srp_subtotals_enabled"] == true ) ? "checked": '';
$groupby = (isset ( $_SESSION ["srm_f62014_group_by"] ) && count($_SESSION ["srm_f62014_group_by"]) != 0 )  ? $_SESSION ["srm_f62014_group_by"][0] : "";
$selected_function = (isset($_SESSION['srp_sub_totals']))? $_SESSION['srp_sub_totals']['function'] : null ;
$selected_Columns = (isset($_SESSION['srp_sub_totals'])) ? $_SESSION["srp_sub_totals"]['affected_columns'] :array() ;
// $user_name = (isset ( $_SESSION ['srm_f62014_user'] )) ? $_SESSION ['srm_f62014_user'] : '';
// $password = (isset ( $_SESSION ['srm_f62014_pass'] )) ? $_SESSION ['srm_f62014_pass'] : '';
// $database_name = (isset ( $_SESSION ['srm_f62014_db'] )) ? $_SESSION ['srm_f62014_db'] : '';
// $template_value = (isset ( $_SESSION ['template_value'] ) && ! empty ( $_SESSION ['template_value'] )) ? $_SESSION ['template_value'] : 'no';
// $all_templates = (isset ( $_SESSION ["all_templates"] ) && ! empty ( $_SESSION ["all_templates"] ) && is_array ( $_SESSION ["all_templates"] )) ? $_SESSION ["all_templates"] : array ();
// $data_source = (isset ( $_SESSION ['srm_f62014_datasource'] )) ? $_SESSION ['srm_f62014_datasource'] : 'table';

?>
<div class="container col-xs-12">
	<form id="myForm" name="myForm"
		action="<?php echo($_SERVER['PHP_SELF']); ?>" role="form"
		method="post" onsubmit="return false;">

		<div class="row">
			<div class="col-xs-1"></div>
			<div id="error-container" class="col-xs-10">
				
			</div>
			<div class="col-xs-1"></div>
		</div>
		

     
        
        
	
	
        
		<div class="row">
			<div class="col-xs-10"></div>
			<div class="col-xs-2"></div>
        </div>
        <br />
        <div class="row">
                <div class="col-xs-1"></div>
                <div class="form-group col-xs-10" style="margin-top: -10px;">
                    <input type="checkbox" id="subtotal" name="subtotal" <?php echo $subtotal_checked?> /> <label
                        for="subtotal">Allow Subtotals</label>


                </div>
                <div class="help-container-i col-xs-1">
                    <a href="" id="subtotalhelp" onClick="return false;"> <img
                            src="includes/images/help.png" width="15" height="15" border="0">
                    </a>
                </div>

            </div>
            
            <div class="row">
			<div class="col-xs-1"></div>
			<div class="form-group col-xs-10">
				<label for="host_name">Group by</label>
				<div class="left-inner-addon">
					<i class="glyphicon glyphicon-list-alt"></i> <input name="Group_by"
						class="form-control font-size-lg" type="text" id="Group_by"
						placeholder="" value="<?php echo $groupby; ?>" readonly />
				</div>
			</div>
			
		</div>
        <br />
        <div class="row">
                <div class="col-xs-1"></div>

                    
                    <div class="form-group col-xs-10" style="margin-top: -10px;">
                        
                        <label for="style_name">Function</label>

                        <div class="left-inner-addon">
                            <i class="glyphicon glyphicon-plus"></i> <select id="Function"
                                                                            name="Function" class="form-control" 
                              <?php if($subtotal_checked != "checked") echo "disabled" ;?>                                              >
                                
                                  <option value='sum'<?php if ($selected_function == "sum") { echo ' selected'; }?>>SUM</option>
                                  <option value='count' <?php if ($selected_function == "count") { echo ' selected'; }?>>Count</option>
                                  <option value='average'<?php if ($selected_function == "average") { echo ' selected'; }?>>Average</option>
                                  <option value='min'<?php if ($selected_function == "min") { echo ' selected'; }?>>Min</option>
                                  <option value='max'<?php if ($selected_function == "sum") { echo ' selected'; }?>>Max</option>
                            </select>

                        </div>
                    </div>

              


                </div>
            

                <br />
               
                <div class="row">
                <div class="col-xs-1"></div>

                    
                    
              
                    <div class="form-group col-xs-10" style="margin-top: -10px;">
						<label for="allFields">Affected Columns</label>
						<div class="left-inner-addon">
							<i class="glyphicon glyphicon-pushpin"></i>
                            <select size="5" name="AffectedColumns" id="AffectedColumns" class="form-control" style="height: 150px;" multiple  <?php if($subtotal_checked != "checked") echo "disabled" ;?>>	
                            				          
							<?php
								foreach($_SESSION["srm_f62014_fields"] as $val)
								{
									 
                                  
										if(in_array($val, $selected_Columns)){
                                            echo "<option value='$val' selected >$val</option>";
                                        }else
                                        echo "<option value='$val'>$val</option>";
											
									
								}
							?>
							</select>
						</div>
					</div>


                </div>
	
        <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-4">
            <button name="btn_back" id="btn_back" class="btn btn-sunny btn-block">
                <span class="icon glyphicon glyphicon-backward"></span><span
                    class="separator"></span> Back
            </button>
        </div>
        <div class="col-xs-3"></div>
        <div class="col-xs-4">
			<button name="continue" id="btn_cont" class="btn btn-sunny btn-block" >
				<span class="icon glyphicon glyphicon-forward"></span><span class="separator"></span> Next
			</button>
		</div>
    </div>

	</form>
</div>

<script>
     $("#btn_back").mousedown(function () {
            backToPage("4");
        });

        $("#subtotal").click(function () {
            
            if ($("#subtotal").is(':checked')){
                //$( "#template" ).prop( "disabled", false );
                $("#Function").prop("disabled", false);
                $("#AffectedColumns").prop("disabled", false);
            } else{
                //$( "#template" ).prop( "disabled", true );
                $("#Function").prop("disabled", true);  
                $("#AffectedColumns").prop("disabled", true);  
            }
        });
        $('input[name=option]').change(function () {

            $('#security').prop('checked', false);
            $('#security').prop('disabled', false);
            $('#members').prop('checked', false);
            $('#members').prop('disabled', false);

            $('#sec_Username').prop('disabled', false);
            $('#sec_pass').prop('disabled', false);
            $('#error-container').empty();

        });

        $("#btn_cont").mousedown(function(){
            
			
			var AffectedColumns = $("#AffectedColumns").val();
            var selected_function = $('#Function').val();
            var Group_by = $('#Group_by').val();
            var subtotal = $("#subtotal").is(':checked') ? "enabled" : "disabled";
			
			$.ajax({
				url: "services/Sutotals.php",
				type: "post",
				data: "affectedcolumns="+AffectedColumns+"&selected_function="+selected_function+"&group_by="+Group_by+"&subtotal="+subtotal,
				success: function(data){
					data = data.trim();
					if(data === "success"){
						nextToPage("6");
						SwitchStatusDone();
					}else{
                        $("#error-container").append("<div class='alert alert-danger'>*  " + data + "</div>");
                        // $("#btn_cont").prop("disabled", true);
					}
				},
			});
		});

</script>
