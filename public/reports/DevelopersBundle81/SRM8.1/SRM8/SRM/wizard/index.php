<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
ob_start ();
define ( "DIRECTACESS", true );
require_once("request.php");
require_once ("services/functions.php");

// pages that's represent navigation
$pagesName = array (
		"step_2", // connect
		"data_source", // data source
		"step_4", // columns
		"step_formatting",
		"step_5",
		"Sutotals", // group by
		"step_6" 
); // settings
   
// this to handler current page
if (isset($_GET ['id']) &&check_numeric_parameter($_GET ['id'],6,false,-1)) {
	$id = preg_replace ( "/[^0-9]/", "", $_GET ['id'] ); // validate id > numeric only
	if (isset ( $pagesName [$id] ) && is_connected ())
		$_SESSION ['srm_f62014_page_key'] = $pagesName [$id]; // set current page in session for navigation reason
	else
		$_SESSION ['srm_f62014_page_key'] = "step_2"; // if wrong id return to **step2 or not found 404
}
//handling super global array
$_GET= array();
$_POST=clean_input_array($_POST);
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();

$profile = new Profile($admin_file);
$dash_url = $profile->get_home_url();
unset($profile);

if (isset ( $_POST ['lastActivePage'] ) && $_POST ['lastActivePage'] === "clear" && isset ( $_SESSION ['srm_f62014_active_pages'] ) && is_array ( $_SESSION ['srm_f62014_active_pages'] ))
	unset ( $_SESSION ['srm_f62014_active_pages'] );
	
	// this to get url for this site without referring to page name just directory like www.sitename.com/dir/
$http = isset ( $_SERVER ['HTTPS'] ) ? 'https://' : 'http://';
$url = "$http"."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url = (strpos ( $url, '?' )) ? substr ( $url, 0, strpos ( $url, '?' ) ) : $url;
$url = ($url [strlen ( $url ) - 1] !== '/') ? $url . '/' : $url;

// this to set current page
if (isset ( $_SESSION ['srm_f62014_page_key'] ) && in_array ( $_SESSION ['srm_f62014_page_key'], $pagesName ))
	$currentPage = $_SESSION ['srm_f62014_page_key'];
else
	$currentPage = "step_2";
	
	// this to detect last active page
if (isset ( $_SESSION ['srm_f62014_active_pages'] ) && is_array ( $_SESSION ['srm_f62014_active_pages'] ))
	$lastActivePage = $_SESSION ['srm_f62014_active_pages'] [COUNT ( $_SESSION ['srm_f62014_active_pages'] ) - 1];
else
	$lastActivePage = "step_2";

	$_SESSION ["request_token_wizard"] = $request_token_value;
?>
<html lang="en">
<head>
<title>Connect</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="includes/bootstrap/css/bootstrap.min.css"
	rel="stylesheet" media="screen">
<link rel="stylesheet"
	href="includes/alertify/themes/alertify.default.css" />
<link rel="stylesheet"
	href="includes/alertify/themes/alertify.core.css" />
		<?php if(isset($_SESSION['srm_f62014_datasource']) && $_SESSION['srm_f62014_datasource'] === "sql" && $currentPage === "data_source") { ?>
		<link rel="stylesheet"
	href="includes/jquery-ui-ligthness/jquery-ui.css" />
		<?php } else { ?>
		<link href="includes/jquery-ui/css/jquery-ui-1.10.4.custom.min.css"
	rel="stylesheet">
		<?php } ?>
		<link rel="stylesheet" href="includes/css/main.css" />
                <?php
																if ($currentPage == "step_formatting") {
																	// echo "<script src='includes/angular/angular.min.js'></script>";
																	// echo "<script src='includes/angular/cellsApp.js' ></script>";
																	
																	echo "<link rel='stylesheet' href='includes/spectrum/spectrum.css' />";
																}
																?>
		
	
		<script>
			// set currenrtURL, currentPage, lastActivePage in client side
			var currentURL = "<?php $url ?>";
			var currentPage = "<?php echo $currentPage; ?>";
			var lastActivePage = "<?php echo $lastActivePage; ?>";
		</script>
<script src="includes/js/jquery.js"></script>
<script src="includes/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="includes/bootstrap/js/bootstrap.min.js"></script>
<script src="includes/alertify/lib/alertify.min.js"></script>
<script src="includes/js/lib.js"></script>
<?php if($currentPage == "step_formatting"){ ?>
<script src='includes/angular/angular.min.js'></script>
<?php } ?>
</head>
<body>
	<div class="container-index">
		<div id="parent-container">
			<div class="header-bar"></div>
			<div id="header" class="row col-xs-12">
				<div style="height: 65px;">
					<p id="logo" style="float: left;">
						<a href="<?php echo $dash_url; ?>"><img border="0" src="includes/images/01.jpg" width="369"
                                                                                        height="71"></a>
					</p>
					<!--   style="text-align: right;" -->
					<div id="page-header"></div>
				</div>
				<hr
					style="width: 650px; margin-left: 75px; border: 1px solid #FFBF00" />
			</div>
			<div id="exit-container" class="">
				<a id="exit" class="cr-hand btn btn-primary btn-xs"><img
					style="position: relative; left: -3px; top: -1px;" width="16"
					height="16" src="includes/images/exit.png" class="glyphicon">Disconnect
					&amp; Exit</a>
			</div>
			<div class="row" id="child-container">
				<div class="col-xs-3">
					<div id="nav-switch">
					<ul id="nav-inner-switch" class="nav nav-pills nav-stacked">
							<li id="nav-header">Steps</li>
							<li class=""><a id="step_2"> <span
									class="glyphicon glyphicon-link"></span> Connect
							</a></li>
							<li class=""><a id="data_source"> <span
									class="glyphicon glyphicon-dashboard"></span> Data Source
							</a></li>
							<li class=""><a id="step_4"> <span
									class="glyphicon glyphicon-th-large"></span> Columns
							</a></li>
							<li class=""><a id="step_formatting"> <span
									class="glyphicon glyphicon-picture"></span> Cells
							</a></li>


							<li class=""><a id="step_5"> <span
									class="glyphicon glyphicon-pushpin"></span> Grouping
							</a></li>
							<li class=""><a id="Sutotals"> <span
									class="glyphicon glyphicon-plus"></span> Sutotals
							</a></li>
							<li class=""><a id="step_6"> <span
									class="glyphicon glyphicon-cog"></span> Settings
							</a></li>
							<li class=""><a id="finish"> <span class="glyphicon glyphicon-ok"></span>
									Finish
							</a></li>
							
						</ul>

					</div>
				</div>
				<div id="container" class="col-xs-9">
						
						<?php
					
						require_once $currentPage.'.php';
						ob_end_flush();
						?>
	
		<script>
			// this array hold title of pages when we going throw navigation
			var titleOfPages = {
				"step_2": "Connect",
				"data_source": "Data Source",
				"step_4": "Choose Columns",
                "step_formatting": "Conditional Formatting",
				"step_5": "Groups & Sorts Setting",
				"Sutotals": "Sutotals",
				"step_6": "General Setting"
			};
			
		
			$(document).ready(function(){
                            
                             if (!String.prototype.includes) {
            String.prototype.includes = function(search) {              

               
                    return this.indexOf(search, 0) !== -1;
               
            };
        }
				
				// set title for every page
				$("title").text(titleOfPages[currentPage]);
				// handle navigate between pages
				$("#"+lastActivePage).parent().nextAll().addClass( "disabled-now" );
				$("#"+lastActivePage).parent().prevAll().children().attr("href", "#");
				$("#"+lastActivePage).attr("href", "#");
				$("#"+currentPage).parent().addClass( "active-now" );
				// set icons
				$("#"+currentPage).append( "<span id='switchStatus' style='position: absolute;right: 0px;top: 10px;' class='glyphicon glyphicon-play invert-direction'></span>" );
				// set icons
				$("#"+currentPage).parent().prevAll().children().append( "<span style='position: absolute;right: 0px;top: 10px;' class='glyphicon glyphicon-ok switchStatus'></span>" );
				// execute navigation process
				$("#nav-inner-switch > li").each(function(){
					var id  = $(this).children().attr("id");
					var next = id+".php";
					setNavProcess(id);
				});
				
				// remove outline from buttons
				$("button").css("outline", "none");
				
				$("#exit").mousedown(function(){
					//location.replace("server/disconnect.php");
                                        $.ajax({
                                            type: "POST",
                                            url: "services/disconnect.php"
                                        }).done(function(){
                                            location.replace("../wizard/?id=0");
                                        });
				});
			});
			// set navigation process
			function setNavProcess(id)
			{
				id = "#"+id;
				$(id).click(function(e){
					e.preventDefault();
					if(!$(this).parent().hasClass("active-now") && $(this).attr("href") === "#"){
						if($(this).attr("id") === "step_2") location.replace(currentURL+"?id="+0);
						if($(this).attr("id") === "data_source") location.replace(currentURL+"?id="+1);
						if($(this).attr("id") === "step_4") location.replace(currentURL+"?id="+2);
                        if($(this).attr("id") === "step_formatting") location.replace(currentURL+"?id="+3);
						if($(this).attr("id") === "step_5") location.replace(currentURL+"?id="+4);
						if($(this).attr("id") === "Sutotals") location.replace(currentURL+"?id="+5);
						if($(this).attr("id") === "step_6") location.replace(currentURL+"?id="+6);
					}
				});
			}
		</script>
					<script src="help.js"></script>

</body>
</html>
