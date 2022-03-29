<?php
/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
use Sre\SmartReportingEngine\src\Engine\Constants;


if ($_print_option == 0) // not in priting mode
{
	
	?>
<a class="toggleMenu" href="#"> <?php echo escape($title) ?></a>
<ul class="nav">

	<li class="test"><a href="<?php echo $link_prev; ?>"><?php echo escape($prev_lang);?></a>
	</li>
	<li><a href="<?php echo $link_next; ?>"><?php echo escape($next_lang)?></a></li>
	<?php if($allow_print_view == "yes"){ ?>
	<li><a href="#"><?php echo escape($print_lang);?></a>


		<ul>
			<li><a href="<?php echo $link_pdf_all; ?>"><?php echo escape($all_pages_lang);?></a>
				</li>
			<li><a href="<?php echo $link_pdf_current; ?>"> <?php echo escape($current_page_lang) ;?></a>
				</li>
		</ul></li>
		<?php } 
		if($allow_export == "yes"){
		?>
	<li><a href="#"><?php echo $export_lang; ?></a>
		<ul>
			<li><a href="#">PDF</a>
				<ul>
					<li><a href="<?php echo $link_pdf_all; ?>"><?php echo escape($all_pages_lang);?></a></li>
					<li><a href="<?php echo $link_pdf_current; ?>"><?php echo escape($current_page_lang) ;?></a></li>
				</ul></li>
			<li><a href="#">CSV</a>
				<ul>
					<li><a href="<?php echo $link_csv_all; ?>"><?php echo escape($all_pages_lang);?></a></li>
					<li><a href="<?php echo $link_csv_current; ?>"><?php echo escape($current_page_lang) ;?></a></li>
				</ul></li>
			<li><a href="#">XML</a>
				<ul>
					<li><a href="<?php echo $link_xml_all; ?>"><?php echo escape($all_pages_lang);?></a></li>
					<li><a href="<?php echo $link_xml_current; ?>"><?php echo escape($current_page_lang) ;?></a></li>
				</ul></li>
		</ul></li>
        
      <?php
         }
         //logout
 if ($access_mode == "PRIVATE_REPORT") {
  
      	
      ?>
      <li class="test"><a href="logout.php"><?php echo escape($log_out_language);?></a>
	</li>
	<?php } ?>
</ul>
<?php if(isset($chkSearch) && strtolower($chkSearch) == "yes" &&$datasource == 'table') {?>
<div class="search-box">
	<form method="post" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
	<input type="hidden" name="RequestToken"  value=<?php echo $request_token_value; ?> />
		<input type="text" class="srch-txt" name="txtordnarySearch"
			value="<?php echo get_default_value('txtordnarySearch'); ?>"
			id="txtordnarySearch" />
		<div class="clear"></div>
		<input type="submit" class="srch-btn" name="btnordnarySearch"
			value="<?php echo escape($search_lang);?>" id="txtordnarySearch" /> <br /> <input
			type="submit" class="srch-btn"
			style="background-color: #8B8B8B; margin-top: 5px;"
			value="<?php echo escape($show_all_lang); ?>" id="btnShowAll"
			name="btnShowAll" /> <br />
	</form>
</div>
<?php

	}
}
?>
