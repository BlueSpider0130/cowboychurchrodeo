<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");
$required_parameters_array = $param->get_reports_params();
$error_message = ($validation_result == 1 || $validation_result =="") ? array() : $validation_result;
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Report Parameters</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Favicon Image-->
        <link rel="shortcut icon" href="#" type="image/x-icon">
        <!--- Css File --->
        <link rel="stylesheet" href="../shared/styles/Filter-css/main.css">
        <link rel="stylesheet" href="../shared/styles/Filter-css/jquery-ui.min.css">
        <!-- Roboto Font -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
        <!--- jQuery File --->
        <script src="../shared/Js/jquery-1.7.2.js"></script>
        <script src="../shared/Js/jquery.ui.core.min.js"></script>
        <script src="../shared/Js/jquery.ui.widget.min.js"></script>
        <script src="../shared/Js/jquery.ui.datepicker.min.js"></script>
        <!--[if lt IE 8]><link rel="stylesheet" href="assets/blueprint-../shared/styles/Filter-css/ie.css" type="text/css" media="screen, projection"><![endif]-->
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

    <!-- <script src="../shared/Js/script.js" defer></script> -->
        <script>
            
            $(function () {
				
                $(".datepicker").datepicker(
                        {
  dateFormat: "yy-mm-dd"
});

                $('.i-date-1').click(function () {
                    $('.input-date-1').focus();
                });
                $('.i-date-2').click(function () {
                    $('.input-date-2').focus();
                });
            });

        </script>
    </head>
    <body>

        <div class="container-flex">
            <!-- BEGIN MAIN -->
            <div class="main">
                <!-- BEGIN CONTACT -->
                <div class="headline">                    
                    <h3 class="headline-heading">Prameters ...</h3>
                </div>
                <p class="input-label">
                <?php
                foreach ($error_message as $value) {
                    echo '<span class="sp-clr" style="margin-left: 10px;">**'. $value .'</span><br/>';
                }
                ?>
                    </p>
                <div class="filter">
                    <form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"  >

                        <?php
                        foreach ($required_parameters_array as $k => $v) {
                            ?>
                            <div class="form-inputs">
                                <p class="input-label"><?php echo $v["view_name"]; ?> </p>
                                <?php
                                switch ($v["data_type"]) {
                                    case 'd':
                                        if (is_array($v["param"]) && count($v["param"]) > 1) {
                                            ?>

                                            <div class="input-group input-cal">
                                                <label for="from">from</label>
                                                <div class="input-icon">
                                                    <div class="date-icon i-date-1"></div>
                                                    <input value='<?php echo isset($_POST['posted_params_' . $k . '_0']) ? $_POST['posted_params_' . $k . '_0'] : "" ?>' type="text" class="input-field input-date-1 datepicker" name='<?php echo 'posted_params_' . $k . '_0' ?>' id='<?php echo 'posted_params_' . $k . '_0' ?>' placeholder="yy/dd/mm">
                                                </div>
                                            </div>

                                            <div class="input-group input-cal last">
                                                <label for="to">to</label>
                                                <div class="input-icon">
                                                    <div class="date-icon i-date-2"></div>
                                                    <input value='<?php echo isset($_POST['posted_params_' . $k . '_1']) ? $_POST['posted_params_' . $k . '_1'] : "" ?>' type="text" class="input-field input-date-2 datepicker" name='<?php echo 'posted_params_' . $k . '_1' ?>' id='<?php echo 'posted_params_' . $k . '_1' ?>' placeholder="yy/dd/mm">
                                                </div>
                                            </div>
            <?php } else {
                ?>
                                            <div class="input-group input-cal">
                                                <label for="editable_field_to_add_extra_infos"><?php echo $v["operator"]; ?></label>
                                                <div class="input-icon">
                                                    <div class="date-icon i-date-1"></div>
                                                    <input value='<?php echo isset($_POST['posted_params_' . $k . '_0']) ? $_POST['posted_params_' . $k . '_0'] : "" ?>' type="text" class="input-field input-date-1 datepicker" name='<?php echo 'posted_params_' . $k . '_0' ?>' id='<?php echo 'posted_params_' . $k . '_0' ?>' placeholder="yy/dd/mm">
                                                </div>
                                            </div>
                <?php
            }
            break;
      default :
            if (is_array($v["param"]) && count($v["param"]) > 1) {
                ?>

                                            <div class="input-group input-cal last">
                                                <label for="From">From</label>
                                                <div class="input-icon">
                                                    <input value='<?php echo isset($_POST['posted_params_' . $k . '_0']) ? $_POST['posted_params_' . $k . '_0'] : "" ?>' type="text" class="input-field" name="<?php echo 'posted_params_' . $k . '_0' ?>"  id='<?php echo 'posted_params_' . $k . '_0' ?>' placeholder='select <?php echo $v["view_name"]; ?>'>
                                                </div>
                                            </div>


                                            <div class="input-group input-cal last">
                                                <label for="From">To</label>
                                                <div class="input-icon">
                                                    <input value='<?php echo isset($_POST['posted_params_' . $k . '_1']) ? $_POST['posted_params_' . $k . '_1'] : "" ?>' type="text" class="input-field" name="<?php echo 'posted_params_' . $k . '_1' ?>"  id='<?php echo 'posted_params_' . $k . '_1' ?>' placeholder='select <?php echo strtolower($v["view_name"]); ?>'>
                                                </div>
                                            </div>
											

            <?php } else { ?>

                                            <div class="input-group input-cal last">
                                                <label for="editable_field_to_add_extra_infos"><?php echo $v["operator"]; ?></label>
                                                <div class="input-icon">
                                                    <input value='<?php echo isset($_POST['posted_params_' . $k . '_0']) ? $_POST['posted_params_' . $k . '_0'] : "" ?>' type="text" class="input-field" name="<?php echo 'posted_params_' . $k . '_0' ?>"  id='<?php echo 'posted_params_' . $k . '_0' ?>' placeholder='select <?php echo strtolower($v["view_name"]);  ?>'>
                                                </div>
                                            </div>
            <?php
            }
            break;
       
          
    }
    ?>  </div>
                            <hr>
                                <?php
                            }
                            ?>
                        <div class="btns">
                            <input type="submit" class="clr-1" value="run" name="param_submit" id="param_submit">
                            <input type="reset"  class="clr-2" value="clear" name="param_clear" id="param_clear">         
                        </div>
                    </form>
                </div>
                <!-- END CONTACT -->
            </div>
            <!-- END div class="MAIN" -->
        </div>
    </body>
</html>

