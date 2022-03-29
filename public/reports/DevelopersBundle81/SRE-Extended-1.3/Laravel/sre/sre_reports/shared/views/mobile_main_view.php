<?php
use Sre\SmartReportingEngine\src\Engine\Constants;

/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet"
		href="../shared/Js/lightbox/css/lightbox.min.css" />


<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<title><?php  echo escape($title) ?></title>

		<link href="../shared/styles/mobile.css" rel="stylesheet" type="text/css" />
			
			
			

</head>

<body>
	<div class="container">

<?php

require_once "mobile-menu.php";
require_once "../shared/views/layout_views/mobile.php";
?>
</div>
	<script type="text/javascript" src="../shared/Js/jquery-2.2.3.min.js"></script>
	<script type="text/javascript"
		src="../shared/Js/lightbox/js/lightbox.min.js"></script>
	<script type="text/javascript" src="../shared/Js/footable-0.1.js"></script>
	<script type="text/javascript"> 
 
  
  
	$(document).ready(function(){
      setTimeout(function(){$('.report-table').footable()}, 1000);  
      //s;

      $("#btnShowAll").click(function(){ 
           $('#txtordnarySearch').val('');
      });
      
     
        var $first = $('.report-table').eq(1);
      $('.report-table').each(function(index,tbl){
          $(tbl).children('thead').children('tr').children('th').each(function(index_th,cell){
              
              var width = $first.find('th').eq(index_th).width();
              $(cell).css('width',width+'px');
          });
      });
        

	
	
	
 var datasource = <?php echo "'" . escape($datasource) . "';" ; ?>
 $.fn.stars = function() {
	    return $(this).each(function() {
	        // Get the value
	        var val = parseFloat($(this).html());
	        // Make sure that the value is in 0 - 5 range, multiply to get width
	        var size = Math.max(0, (Math.min(5, val))) * 16;
	        // Create stars holder
	        var $span = $('<span />').width(size);
	        // Replace the numerical value with stars
	        $(this).html($span);
	    });
 }
  
	    $('span.stars').stars();
 }
	 ); 
  </script>
	<script type="text/javascript" src="../shared/Js/script.js"></script>
</body>
</html>
