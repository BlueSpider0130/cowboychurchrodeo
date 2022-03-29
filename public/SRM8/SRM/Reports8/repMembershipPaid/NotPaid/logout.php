<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
define("DIRECTACESS", "true");
ob_start();
require_once("../shared/helpers/session.php");
session_end();
if (!empty($_SESSION)) {
    echo '
			<link href="../shared/Js/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
			<script src="../shared/Js/jquery-2.2.3.min.js"></script>
			<script src="../shared/Js/bootstrap/js/bootstrap.js"></script>
			<div class="container" style="position: relative;top: 10px;">
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<div>
						<strong>Error in logout!</strong> Browser can\'t logout, so please clear all cookies to logout
					</div>
				</div>
			</div>';
    ob_end_flush();
    exit();
} else {
    ob_end_clean();
    header('location: login.php');
    exit();
}