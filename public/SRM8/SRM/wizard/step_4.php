<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");
require_once("request.php");
require_once("lib.php");
require_once 'checkSession.php';


if (sessionBe4Step4() === false) {
    header("location: $url?id=1");
    exit();
}

$_SESSION['srm_f62014_page_key'] = "step_4";
require_once 'activePages.php';

if (isset($_SESSION["srm_f62014_datasource"]))
    $datasource = $_SESSION["srm_f62014_datasource"];
else
    header("location: $url?id=0");

$allFields = array();
// set data from session to fields
if ($datasource === "table") {
    if (isset($_SESSION["srm_f62014_table"]))
        $table = $_SESSION["srm_f62014_table"];
    else
        header("location: $url?id=1");


    foreach ($table as $key => $val) {
        $val = clean_input($val);
        $result = $dbHandler->query("SHOW COLUMNS FROM `$val`");
        foreach ($result as $key => $row) {
            if (count($table) === 1)
                $allFields[] = $row[0];
            else
                $allFields[] = $val . "." . $row[0];
        }
    }
}else {

    if (isset($_SESSION["srm_f62014_sql"]))
        $sql = $_SESSION["srm_f62014_sql"];
    else
        header("location: $url?id=1");

    $sql = make_valid($sql);
    $sql = $sql . ' LIMIT 1';

    $result = $dbHandler->query($sql, 'ASSOC');
    $rows = $dbHandler->get_num_rows();

    if ($rows !== 0)
        foreach ($result[0] as $key => $val)
            $allFields[] = $key;
    else
        $error .= "<br>*Records = 0, please click back and enter another query";


    $_SESSION["srm_f62014_fields"] = $allFields;
    $_SESSION["srm_f62014_fields2"] = $allFields;
}
// ------------------------------------------------------------------------------------
// ------------------------------------ Statistical -----------------------------------
$functions = array("sum", "avg", "min", "max", "count");
$selectedFields = isset($_SESSION["srm_f62014_fields"]) ? $_SESSION["srm_f62014_fields"] : array();

// ------------------------------------------------------------------------------------
// ------------------------------------ Label -----------------------------------------

$labels = isset($_SESSION["srm_f62014_labels"]) ? $_SESSION["srm_f62014_labels"] : array();
$tables = isset($_SESSION["srm_f62014_table"]) ? $_SESSION["srm_f62014_table"] : array();

if (empty($labels) || count($labels) < 1) {
    $labels = array();
    foreach ($selectedFields as $field) {
        $chunks = explode('.', $field);
        $labels[$field] = (count($tables) === 1) ? $field : (isset($chunks[1]) ? $chunks[1] : $chunks[0]);
    }
    $_SESSION["srm_f62014_labels"] = $labels;
} else {
    $temp = array();
    foreach ($selectedFields as $field) {
        if (array_key_exists($field, $labels))
            $temp[$field] = $labels[$field];
        else {
            $chunks = explode('.', $field);
            $temp[$field] = (count($tables) === 1) ? $field : (isset($chunks[1]) ? $chunks[1] : $chunks[0]);
        }
    }
    $labels = $temp;
    $_SESSION["srm_f62014_labels"] = $labels;
}
?>
<div id="tabs" class="container col-xs-12"><!-- -->
    <!-- Nav tabs nav nav-tabs -->
    <ul class="" style="font-size: 12px;">
        <?php if ($datasource === "table") { ?>
            <li class="active"><a id="columns-nav" href="#columns" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Columns</a></li>
        <?php } ?>
        <li><a id="labels-nav" href="#labels" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span> Labels</a></li>
<?php if ($datasource === "table") { ?>
            <li><a id="statistical-nav" href="#statistical" data-toggle="tab"><span class="glyphicon glyphicon-stats"></span> Aggregation Functions</a></li>
<?php } ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">

<?php if ($datasource === "table") { ?>
            <div class="tab-pane active" id="columns">
                <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return false;">

                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div id="error-container" class="col-xs-11">
                        </div>
                    </div><!-- .row (error) -->

                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="form-group col-xs-5" style="margin: 0px; padding: 0px 5px 0px 0px;">
                            <label for="allFields">Available Fields</label>
                            <div class="left-inner-addon">
                                <i class="glyphicon glyphicon-pushpin"></i>
                                <select size="5" name="allFields" id="allFields" class="form-control" style="height: 150px;" multiple>							          
                                    <?php
                                    foreach ($allFields as $val) {
                                        if (isset($_SESSION["srm_f62014_fields"])) {
                                            if (!in_array($val, $_SESSION["srm_f62014_fields"]))
                                                echo "<option value='$val'>$val</option>";
                                        } else
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
                            <label for="selectedFields">Selected Fields</label>
                            <div class="left-inner-addon">
                                <i class="glyphicon glyphicon-pushpin"></i>
                                <select size="2" name="selectedFields" id="selectedFields" class="form-control" style="height: 150px;" multiple>
                                    <?php
                                    if (isset($_SESSION["srm_f62014_fields"]))
                                        foreach ($_SESSION["srm_f62014_fields"] as $val)
                                            if (in_array($val, $allFields))
                                                echo "<option value='$val'>$val</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>	<!-- .row (selectColumns) -->

                    <div class="row" style="position: relative;top: -175px; left: 523px;width: 20px;">
                        <a href="" id="sfHelp" onClick="return false;">
                            <img src="includes/images/help.png" width="15" height="15" border="0">
                        </a>
                    </div><!-- help -->

                    <div class="row" style="position: relative;top: -15px;left: 15px;">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-1" style="position: relative; left: 40px;" id="pointer"></div>
                        <div class="col-xs-3">
                            <button name="select-columns" class="btn btn-primary btn-block btn-xs" style="font-size: 12px;" id="select-columns">Select</button/> 

                        </div>
                    </div>	<!-- .row (select btn) -->

                </form>	
            </div>
<?php } ?>

        <div class="tab-pane" id="labels">
            <form name="labelForm" id="labelForm" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return false;">
                <div class="row">
                    <div id="labels-error-container" class="col-xs-12"></div>
                </div><!-- .row (error) -->

                <div class="row row-as-ftr">
                    <div class="col-xs-1"></div>
                    <div class="row col-xs-10 div-as-th">
                        <div class="col-xs-6">Field</div>
                        <div class="col-xs-6">Label</div>
                    </div>
                    <div class="col-xs-1"></div>
                </div>
                <div class="row" style="min-height: 190px;">
                    <div class="col-xs-1"></div>
                    <div class="col-xs-10 table-container">
                        <table id="labels-table" class="table table-hover">
<?php foreach ($labels as $key => $val) { ?>
                                <tr>
                                    <td style="width: 50%;"><label for="lbl_<?php echo str_replace(array('.', ' '), array('_', '_'), $key); ?>"><?php echo $key; ?></label></td>
                                    <td style="width: 50%;">
                                        <div class="left-inner-addon">
                                            <i class="glyphicon glyphicon-edit"></i>
                                            <input class="input-as-tf" type="text" value="<?php echo $val; ?>" id="lbl_<?php echo str_replace(array('.', ' '), array('_', '_'), $key); ?>" name="lbl_<?php echo str_replace(array('.', ' '), array('_', '_'), $key); ?>" />
                                        </div>
                                    </td>
                                </tr>
<?php } ?>
                        </table>
                    </div>
                    <div class="col-xs-1"></div>
                </div>
            </form>
        </div>
<?php if ($datasource === "table") { ?>
            <div class="tab-pane" id="statistical">

                <form method="post" action="Statistical.php" role="form" onsubmit="return false;">
                    <!-- Statistical options -->
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div id="statistical-error-container" class="col-xs-10">
                        </div>
                        <div class="col-xs-1"></div>
                    </div><!-- .row (error) -->
                    <div class="row">
                        <div class="col-xs-1"></div> 
                        <div class="form-group col-xs-10">
                            <label for="functions">Statistical Function</label>
                            <div class="left-inner-addon">
                                <i class="glyphicon glyphicon-pushpin"></i>
                                <select name="functions" class="form-control font-size-lg" id="functions">
                                    <?php
                                    foreach ($functions as $func) {
                                        if (isset($_SESSION["srm_f62014_function"]) && $_SESSION["srm_f62014_function"] === $func)
                                            echo "<option value='$func' selected>$func</option>";
                                        else
                                            echo "<option value='$func'>$func</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="help-container col-xs-1">
                            <a href="" id="statisticalFuncHelp" onClick="return false;">
                                <img src="includes/images/help.png" width="15" height="15" border="0">
                            </a>
                        </div>
                    </div><!-- .row (select function) -->

                    <div class="row">
                        <div class="col-xs-1"></div> 
                        <div class="form-group col-xs-10">
                            <label for="affected_column">Affected column</label>
                            <div class="left-inner-addon">
                                <i class="glyphicon glyphicon-pushpin"></i>
                                <select name="affected_column" class="form-control font-size-lg" id="affected_column">

                                    <?php
                                    foreach ($selectedFields as $field) {
                                        if (isset($_SESSION["srm_f62014_affected_column"]) && $_SESSION["srm_f62014_affected_column"] === $field)
                                            echo "<option value='$field' selected>$field</option>";
                                        else
                                            echo "<option value='$field'>$field</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="help-container col-xs-1">
                            <a href="" id="statisticalColHelp" onClick="return false;">
                                <img src="includes/images/help.png" width="15" height="15" border="0">
                            </a>
                        </div>
                    </div><!-- .row (select column) -->

                    <div class="row">
                        <div class="col-xs-1"></div> 
                        <div class="form-group col-xs-10">
                            <label for="groupby_column">Group by</label>
                            <div class="left-inner-addon">
                                <i class="glyphicon glyphicon-pushpin"></i>
                                <select name="groupby_column" class="form-control font-size-lg" id="groupby_column">

                                    <?php
                                    if (isset($_SESSION["srm_f62014_fields"]) && count($_SESSION["srm_f62014_fields"]) === 1) {
                                        if (!isset($_SESSION["srm_f62014_groupby_column"]))
                                            echo "<option value='None' selected>None</option>";
                                        else
                                            echo "<option value='None'>None</option>";
                                    }

                                    foreach ($selectedFields as $field) {
                                        if (isset($_SESSION["srm_f62014_groupby_column"]) && $field === $_SESSION["srm_f62014_groupby_column"])
                                            echo "<option value='$field' selected>$field</option>";
                                        else
                                            echo "<option value='$field'>$field</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="help-container col-xs-1">
                            <a href="" id="statisticalGroHelp" onClick="return false;">
                                <img src="includes/images/help.png" width="15" height="15" border="0">
                            </a>
                        </div>
                    </div><!-- .row (select group by) -->

                    <div class="row" style="position: relative;top: -10px;">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-3">
                            <button name="set-statistical" class="btn btn-primary btn-block btn-xs" style="font-size: 12px;" id="set-statistical">
                                <?php
                                if (isset($_SESSION["srm_f62014_statestical"]) && $_SESSION["srm_f62014_statestical"] === 1)
                                    echo 'Unset Function';
                                else
                                    echo 'Set Function';
                                ?>
                            </button/> 
                        </div>
                        <div class="col-xs-1"></div>
                    </div>

                </form>
            </div>
<?php } ?>

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
<script language ="javascript">
    var fieldsInfo = <?php if (count($allFields) > 0)
    echo 'new Array("' . implode('", "', $allFields) . '")';
else
    echo 'new Array()';
?>;

    var s_selectedFields = <?php if (isset($_SESSION["srm_f62014_fields"]))
    echo 'new Array("' . implode('", "', $_SESSION["srm_f62014_fields"]) . '")';
else
    echo "''";
?>;

    var c_selectedFields = new Array();

    $(function() {
        $("#tabs").tabs();
       


        $("#page-header").empty();
        $("#page-header").append('<div id="img-container"><img src="includes/images/columns.jpg" width="70" height="70"/></div>');
        $("#page-header").append('<div id="text-container"><h4>Select Columns</h4>You must select at least One column</div>');

        $("#columns-nav").click(function() {

            $("#page-header").empty();
            $("#page-header").append('<div id="img-container"><img src="includes/images/columns.jpg" width="70" height="70"/></div>');
            $("#page-header").append('<div id="text-container"><h4>Select Columns</h4>You must select at least One Column</div>');

        });


        $("#statistical-nav").click(function(e) {
            if (c_selectedFields.length > 0 || s_selectedFields !== "")
            {
                $("#page-header").empty();
                $("#page-header").append('<div id="img-container"><img src="includes/images/Statistics.jpg" width="70" height="70"/></div>');
                $("#page-header").append('<div id="text-container"><h4>Aggregation Functions</h4>It\'s not obligatory using it</div>');
            } else {
                e.preventDefault();
                $("#columns-nav").click();
                alertify.error("Please select columns before this step");
                return;
            }
        });

<?php if ($datasource === "table") { ?>
            $("#labels-nav").click(function(e) {
                if (c_selectedFields.length > 0 || s_selectedFields !== "")
                {
                    $("#page-header").empty();
                    $("#page-header").append('<div id="img-container"><img src="includes/images/labels.jpg" width="70" height="70"/></div>');
                    $("#page-header").append('<div id="text-container"><h4>Change Labels</h4>change columns names displayed in report</div>');
                } else {
                    e.preventDefault();
                    $("#columns-nav").click();
                    alertify.error("Please select columns before this step");
                    return;
                }
            });
<?php } ?>


        $("#allFields, #selectedFields, #add, #remove, #addAll, #removeAll").mousedown(function() {
            // alertify.error("Don't forget to click Select after choosing tables");
            if ($("#pointer-x").length <= 0)
            {
                $("#pointer").append("<span id='pointer-x' class='invert-direction glyphicon glyphicon-arrow-left'> </span>");
                animatePointerX();
            }
        });


        if (s_selectedFields === "" || ($.isArray(s_selectedFields) && s_selectedFields.length < 1))
        {
            $("#btn_cont").prop("disabled", true);
        }

        $("#add").mousedown(function() {
            add("allFields", "selectedFields");
        });

        $("#remove").mousedown(function() {
            remove("selectedFields", "allFields", fieldsInfo);
        });

        $("#addAll").mousedown(function() {
            addAll("allFields", "selectedFields");
        });

        $("#removeAll").mousedown(function() {
            removeAll("selectedFields", "allFields", fieldsInfo);
        });

        $("#select-columns").mousedown(function() {
            $("#selectedFields option").prop("selected", true);
            var selectedFields = $("#selectedFields").val();
            var selectedFieldsAjax = (selectedFields !== null) ? selectedFields.join() : null;

            $.ajax({
                url: "services/step_4.php",
                type: "post",
                data: "selFields=" + selectedFieldsAjax,
                success: function(data) {
                    data = data.toString().trim();
                    $("#error-container").empty();
                    $("#affected_column").empty();
                    $("#groupby_column").empty();
                    $("#labels-table").empty();
                    $("#pointer-x").stop();
                    $("#pointer-x").remove();
                    if (data.includes("success"))
                    {
                        if ($.isArray(selectedFields))
                        {
                            if (selectedFields.length === 1)
                                $("#groupby_column").append("<option value='None'>None</option>");

                            for (var i = 0; i < selectedFields.length; i++)
                            {
                                $("#affected_column").append("<option value='" + selectedFields[i] + "'>" + selectedFields[i] + "</option>");
                                $("#groupby_column").append("<option value='" + selectedFields[i] + "'>" + selectedFields[i] + "</option>");

                                var key = selectedFields[i].replace('.', '_');
                                var chunks = selectedFields[i].split('.');
                                var val = (selectedFields[i].indexOf('.') === -1) ? selectedFields[i] : chunks[1];
                                $("#labels-table").append("<tr><td style='width: 50%;'><label for='lbl_" + key.replace(' ', '_') + "'>" + selectedFields[i] + "</label></td>" +
                                        "<td style='width: 50%;'><div class='left-inner-addon'><i class='glyphicon glyphicon-edit'></i>" +
                                        "<input class='input-as-tf' type='text' value='" + val + "' id='lbl_" + key.replace(' ', '_') + "' name='lbl_" + key.replace(' ', '_') + "' /></div></td></tr>"
                                        );
                            }
                        }
                        c_selectedFields = selectedFields;
                        $("#btn_cont").prop("disabled", false);
                        $("#labels-nav").trigger("click");
                    }
                    else {
                        $("#error-container").append("<div class='alert alert-danger'>* At least One field should be selected " + data + "</div>");
                        $("#btn_cont").prop("disabled", true);
                    }
                }
            });
        });

        $("#set-statistical").mousedown(function() {
            var functions = $("#functions").val();
            var affectedColumn = $("#affected_column").val();
            var groupbyColumn = $("#groupby_column").val();
            $("#statistical-error-container").empty();
            if (functions === "" || functions === null || typeof functions === "undefined") {
                $("#statistical-error-container").append("<div class='alert alert-danger'>* Please select a function</div>");
                return;
            } else if (affectedColumn === "" || affectedColumn === null || typeof affectedColumn === "undefined") {
                $("#statistical-error-container").append("<div class='alert alert-danger'>* Please select an affected column</div>");
                return;
            } else if (groupbyColumn === "" || groupbyColumn === null || typeof groupbyColumn === "undefined") {
                $("#statistical-error-container").append("<div class='alert alert-danger'>* Please select a group by column</div>");
                return;
            } else if (groupbyColumn === affectedColumn) {
                $("#statistical-error-container").append("<div class='alert alert-danger'>* Affected column and Group by can't be the same, Please pick another Affected column or Group by</div>");
                return;
            }
            $.ajax({
                url: "services/step_4.php",
                type: "post",
                data: "func=" + functions + "&affectedColumn=" + affectedColumn + "&groupbyColumn=" + groupbyColumn,
                success: function(data) {
                    data = data.trim();
                    if (data === "success")
                    {
                        alertify.success("Aggregation function set successfully");
                        $("#set-statistical").text('Unset Function');
                    } else if (data === "unset_success")
                    {
                        alertify.success("Unset aggregation function success");
                        $("#set-statistical").text('Set Function');
                    } else {
                        if (data === "error1")
                            $("#statistical-error-container").append("<div class='alert alert-danger'>* Please select a function</div>");
                        else if (data === "error2")
                            $("#statistical-error-container").append("<div class='alert alert-danger'>* Please select an affected column</div>");
                        else if (data === "error3")
                            $("#statistical-error-container").append("<div class='alert alert-danger'>* Please select a group by column</div>");
                        else if (data === "error4")
                            $("#statistical-error-container").append("<div class='alert alert-danger'>* Affected column and Group by can't be the same, Please pick another Affected column or Group by</div>");
                        else
                            alertify.error("error");
                    }
                }
            });
        });



        $("#btn_cont").mousedown(function() {
            var labelsValues = $("#labelForm").serialize();
            if (s_selectedFields !== "" || $.isArray(c_selectedFields))
            {
                $.ajax({
                    url: "services/step_4.php",
                    type: "post",
                    data: "labels=true&" + labelsValues,
                    success: function(data) {
                        data = data.trim();
                        $("#error-container").empty();
                        $("#labels-error-container").empty();
                        if (data === "success") {

                            nextToPage("3");
                            SwitchStatusDone();
                        } else {
                            if (data !== "error") {
                                $("#labels-nav").trigger("click");
                                $("#labels-error-container").append("<div class='alert alert-danger'>The Label of column '" + data + "' is required.</div>");
                            } else {
                                $("#columns-nav").trigger("click");
                                $("#error-container").append("<div class='alert alert-danger'>* At least One field should be selected</div>");
                                $("#btn_cont").prop("disabled", true);
                            }
                            SwitchStatusError();
                        }
                    },
                    error: function() {
                        alertify.error("error");
                    }
                });
            } else {
                $("#btn_cont").prop("disabled", true);
                return;
            }
        });

        $("#btn_back").mousedown(function() {
            backToPage("1");
        });



    });

    function animatePointerX()
    {
        $("#pointer-x").animate({left: "+=10"}, 1000, function() {
            $("#pointer-x").animate({left: "-=10"}, 1000, function() {
                animatePointerX();
            });
        });
    }
</script>
