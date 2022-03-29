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
<!DOCTYPE HTML>

<html  <?php
	
	if ($language == "he" || $language == "ar") {
		echo "dir = 'rtl'";
	}
	
	?>>

<head>

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" href="../shared/Js/lightbox/css/lightbox.min.css" />
<script type="text/javascript" src="../shared/Js/jquery-1.7.2.min.js"></script>

<title><?php echo escape($title) ?></title>

<link href="<?php echo "../shared/styles/" . $style_name . ".css"; ?>" rel="stylesheet" type="text/css" />
<?php if ($_print_option != 0) echo "<link href='../shared/styles/print.css'  rel='stylesheet' type='text/css' />";?>

</head>



<body class="MainPage">
<?php

require_once 'menu.php';
require_once "../shared/views/layout_views/$layout.php";
?>

<!-- start Pagination block -->
 <?php if ($_print_option !== 1 || !isset($_CLEANED["print"])) :   ?>
	<tr >
        
		<td class="TableFooter">
		
			<div style="text-align: center;">
         
            
             	<span><?php echo escape((int)$fromRecordNumber); ?></span>
						<?php echo escape($pager_to); ?><span><?php echo escape((int)$toRecordNumber); ?></span>
						<?php echo escape($pager_of); ?> <span><?php echo escape((int)$nRecords); 
						echo ' '. escape($pager_records); ?></span>

				<div class="pages-num">
					<a	class="firstPage" href="<?php echo escape($firstPage) ?>"></a>
					<a class="prevPage" href="<?php echo escape($prevPage) ?>"></a>

					<?php  echo  escape($pager_page) ; ?> <?php echo escape((int) $currentPage) ?> <?php echo escape($pager_of); ?> <?php echo escape((int) $numberOfPages); ?>

					<a class="nextPage" href="<?php echo escape($nextPage) ?>"></a>
					<a class="lastPage" href="<?php echo escape($lastPage) ?>"></a>

					<form method="get" action="<?php echo basename($_SERVER['PHP_SELF']); ?>" style="display: inline;">
						<label><?php echo escape($Go_To_Page_lang);?><input type="hidden" name="RequestToken"  value=<?php echo $request_token_value; ?> /> <input name="cp" style="width: 50px" value="<?php echo escape($currentPage) ?>" /></label>
					<?php if($url_param === 1701){?>
					<input type="hidden" name="DebugMode7"  value="1701" />
					<?php }?>
					
					</form>
				</div>
                        <?php endif; ?>
                    


                    </div>
		</td>

	</tr>
	<!-- end pagination block -->
	</table>

	<!-- ************************* Show print Dialog **************************** !-->

        <?php
								// show print dialog in case of print mode $$$
								
								if ($_print_option == 3) {
									?>

            <script>

                window.print();

            </script>

            <?php
								}
								?>

        <!-- ************************* End Of Show print Dialog ********************* !-->
	<script type="text/javascript" src="../shared/Js/lightbox/js/lightbox.min.js"></script>
	<script>
            $(document).ready(function() {
                
                
                
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


                var datasource = <?php echo "'" .escape($datasource) . "';"; ?>
            //    if (datasource == 'sql') {
            //        $("#txtordnarySearch").css('visibility', 'hidden');
            //        $(".srch-btn").css('visibility', 'hidden');
            //        $("#search_advanced").css('visibility', 'hidden');

            //    }
                $('span.stars').stars();
                
                 

            } 
            );
        
	</script>


	</script>

</body>

</html>

