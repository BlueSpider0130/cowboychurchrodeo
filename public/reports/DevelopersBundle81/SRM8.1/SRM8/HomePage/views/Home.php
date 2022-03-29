<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
define('BASEPATH', 1); // defining the constant of codegniter
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");
require_once("../SRM/Reports8/shared/helpers/Model/codegniter/file_helper.php");
require_once ("models/Report.class.php");
require_once ("models/Dashboard.class.php");
$Report_location = "../SRM/Reports8/";
$Dashboard = new DashBoard($Report_location);
$categories = $Dashboard->get_categories();

if (isset($_CLEANED["del"]) && $_CLEANED["del"] != "") {


    if ($Dashboard->is_report_exists($_CLEANED["del"])) {
        $Dashboard->delete_report($_CLEANED["del"], $_CLEANED["legacy"]);
    }

    $page = "index.php?v=1&&request_token=$request_token_value";
    echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
    exit();
}


$_SESSION ["request_token"] = $request_token_value;
?>
<div class="panel-body text-center">

    <div style="text-align:left;" class="alert alert-success">Welcome Admin! You have <b>  <?php echo $Dashboard->get_categories_count(); ?></b> categories and <b><?php echo $Dashboard->get_reports_count(); ?></b> reports. </div>
    <h4  class="clearfix hidden-xs" style="margin-right:15px; 	margin-top: 5px;">

        <a  href="<?php echo $create_new_report_path; ?>" class="pull-right"><IMG src="DboardImages/b1.jpg" border=0></a>
        <a href="<?php echo $create_new_report_path; ?>" class="btn btn-primary pull-right hidden" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Creat New Report</a>
    </h4>

    <div class="col-xs-12">
        <?php
        $i = 1;
        foreach ($categories as $val) {
            $reports = $Dashboard->get_reports_per_category($val);
            ?>

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <?php
                $x = $i / 4;
                $fraction = $x - floor($x);
                if ($fraction == 0)
                    echo '<div class="panel panel-danger">';
                elseif ($fraction < 0.3)
                    echo '<div class="panel panel-info">';
                elseif ($fraction < 0.6)
                    echo '<div class="panel panel-success">';
                else
                    echo '<div class="panel panel-warning">';
                ?>

                <div class="panel-heading" role="tab" id="heading<?php echo $i; ?>">
                    <h4 class= "<?php
                    if ($i == 1)
                        echo "panel-title accordion-toggle";
                    else
                        echo "panel-title accordion-toggle collapsed";
                    ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>"><?php echo $val; ?></h4>
                </div>
                <div id="collapse<?php echo $i; ?>" class="<?php
                if ($i == 1)
                    echo "panel-collapse collapse in";
                else
                    echo "panel-collapse collapse";
                ?>" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>">
                    <div class="panel-body" width="100%">
                        <table border="0" cellpadding="2" cellspacing="0" id="table<?php echo $i; ?>" class="reports" height="31" style="border-collapse: separate; border-spacing:4px;width: 100%;" >
                            <tr >
                                <td bgcolor="#FDC643" height="18" align="center"><font size='3' color="#000080"> <b><I>Report Title</b></i></font> </td>
                                <td class="hidden-xs" bgcolor="#FDC643" height="18" align="center" width="200" ><font size='3' color="#000080"><I> <b>Date created</b></i></font></td>
                                <td class="hidden-xs"></td>
                            </tr>
                            <?php
                            foreach ($reports as $report) {
                                $legacy = ($report->is_legacy_report == true) ? "yes" : "no";
                                ?>
                                <tr>
                                    <td ><font size = '2'><a href="<?php echo $report->link; ?>"><?php echo str_replace("rep", "", $report->name) . "    	 "; ?></a><?php echo $report->Access_role; ?></font></td >
                                    <td class="hidden-xs"><font size = '3'><a href="<?php echo $report->link; ?>"><?php echo $report->date_created; ?></a></font></td >
                                    <td class="hidden-xs"><a href="<?php echo "index.php?v=1&&del=$report->name&&legacy=$legacy&&request_token=$request_token_value" ?>"  onclick='return confirm_delete();' title="delete report"><img src="DboardImages/delete.png" alt="delete report"></a></td>
                                </tr>
    <?php } ?>

                        </table>
                    </div>
                </div>
            </div>
            <?php
            $i++;
        }
        ?>
  
