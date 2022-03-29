<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (! defined ( "DIRECTACESS" ))
	exit ( "No direct script access allowed" );
if ($_print_option == 0) // not in priting mode
{
	
	?>
<a class="toggleMenu" href="#"> <?php echo escape($title) ?></a>
<ul class="nav">
<?php 
if(isset($allow_admin_home_icon)&& isset($admin_home_url) && $admin_home_url !== "" && $allow_admin_home_icon === "yes" && get_profile() ==="admin")
{
?>
   <li class="test"><a href="<?php echo $admin_home_url; ?>"><?php echo escape($lang_home);?></a>
	</li>
	<?php }?>
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
                <?php if($show_mobile_layout && !$mobile_screen){  ?>
                 <li class="test"><a href=<?php echo "ChangeLayout.php?setlLayout=AlignLeft&&RequestToken=$request_token_value" ; ?>><?php echo escape($lang_desktop);?></a>
	</li>
                <?php }  ?>
        
      <?php
         }
         //logout
if ($security == "enabled" || $members == "enabled" || $allow_only_admin == "yes" || ! empty ( $security ) || ! empty ( $members ) ||   $sec_pass != ""  || get_profile() !== "public" || isset ( $_SESSION [$admin_login_key] ) || isset ( $_SESSION [$user_login_key])) 
         {
      	
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
