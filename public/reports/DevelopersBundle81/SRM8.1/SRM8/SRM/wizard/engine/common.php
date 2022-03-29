<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
ob_start();
define('BASEPATH', 1);
define("DIRECTACESS", 1);
require_once("../request.php");
$_GET = array();
$_POST = array();
$_ENV = array();
$_FILES = array();
$_COOKIE = array();
require_once ("../../Reports8/shared/helpers/Model/codegniter/Common.php");
require_once("Model/Engine.abstract.php");
require_once("Model/StandardEngine.class.php");
$engine = new StandardEngine("../../Reports8");
$engine->run();
ob_end_flush();
exit();
?>
