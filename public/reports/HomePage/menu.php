<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (! defined("DIRECTACESS")) exit("No direct script access allowed");
 ?>
 <a class="hidden-xs" href="<?php echo "index.php?v=1&&request_token=".$request_token_value; ?>"><i class="glyphicon glyphicon-home"></i> Home	 |</a> 
				<a class="hidden-xs" href="<?php echo "index.php?v=2&&request_token=".$request_token_value; ?>" ><i class="glyphicon glyphicon-user"></i> Admin Profile |</a> 
				<?php if($is_demo){?>
				<a class="hidden-xs" href="http://mysqlreports.com/purchase/" target="_blank"><i class="glyphicon glyphicon-shopping-cart"></i> Buy now |</a>
				<?php } ?>
				<a class="hidden-xs" target= "_blank" href="<?php if(isset($help_file)) echo $help_file;?>" ><i class="glyphicon glyphicon-question-sign"></i> Help |</a>
				<a href="logout.php" ><i class="glyphicon glyphicon-log-out"></i> Log out </a>
				
				</div>
				<br/>
				<p><div><img border="0" src="DboardImages/01.jpg" width="369" height="71" class="img-responsive"></div></p>