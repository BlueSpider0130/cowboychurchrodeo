<?php
use Sre\SmartReportingEngine\src\Engine\Constants;
define(Constants::DIRECTACESS,true);
require_once("request.php");
if($access_mode === "PUBLIC_REPORT"){
     header("HTTP/1.1 401 Unauthorized");
    echo 'Unauthorized';
    dump();
}
session_end();

//redirect
if (!empty(trim($redirect_log_out))) {
    header('Location: ' . $redirect_log_out);
    dump();
} elseif ((trim($redirect_log_out)) && !empty(trim($redirect_login_page))) {
    header('Location: ' . $redirect_login_page);
    dump();
} else {

    header("HTTP/1.1 401 Unauthorized");
    echo 'Unauthorized';
    dump();
}     
            
           
      

