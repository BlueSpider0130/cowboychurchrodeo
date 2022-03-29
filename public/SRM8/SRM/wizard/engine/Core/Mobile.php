<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
define("DIRECTACESS", "true");
ob_start();
require_once 'request.php';

// valid only for mobile views
if (strtolower($layout) != "mobile") {
    ob_end_clean();
    header('Location: ' . $file_name . '.php');
    exit();
}
//case mobile layout displayed in a mobile or tablet screen
if (isset($detect)) {
    if ($detect->isMobile() || $detect->isTablet()) {
        ob_end_clean();
        header('Location: ' . $file_name . '.php');
        exit();
    }
}

$mobile_report_url = $file_name . '.php';
if ($_SERVER ['QUERY_STRING'] !== "") {
    $mobile_report_url = $mobile_report_url . "?" . $_SERVER ['QUERY_STRING'];
}
ob_end_flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $title ?> - Mobile View </title>
        <style type="text/css">
            .emulator {
                background-image: url(../shared/images/icons/emulator.jpg);
                background-repeat: no-repeat;
                height: 912px;
                width: 850px;
                margin-right: auto;
                margin-left: auto;
            }
        </style>
    </head>

    <body>
        <div
            style="text-align: center; position: absolute; width: 200px; margin: 0px auto;">
            <a title="Tablet View" href="Tablet.php"><img border="0"
                                                          src="../shared/images/icons/view_tablet.png" /></a>
        </div>
        <center>

            <div class="emulator">
                <iframe src="<?php echo $mobile_report_url; ?>" frameborder="0"
                        width="313" height="543"
                        style="background-color: #FFF; margin-right: 130px; margin-top: 163px;"></iframe>
            </div>

        </center>
    </body>
</html>