<?php
if (! defined ( "DIRECTACESS" ))
	die ( "Error 400 : No direct script access allowed" );
$error = "";
$version = "8.0.0";
$upgrade_notes = " Copy the directory '/SRM/reports' from that old version to the  location '/SRM8/SRM/reports' inside this version .  <br/> <strong> Please note carfully that :</strong>After finish coping your legacy reports  you should have two directories for the generated reports inside this version <br><font color='red'> 1-/SRM8/SRM/reports (for legacy reports created by the old version).<br/> 2-/SRM8/SRM/Reports8 (For the new reports that will be created by this version) </font> .";
$directories_to_read_write = array (
		"Reports" => "/SRM8/SRM/Reports8/",
		"configs" => "/SRM8/SRM/Reports8/shared/config/" 
);
$_SESSION [$install_session_key . "_last_view"] = 0;
if (isset ( $_CLEANED ['go'] )) {
	// check sessions
	if ( ! isset ( $_SESSION )) {
		$error = "Session is disabled in your PHP sessions, please enable the sessions then try again";
	}
	// check permissions
	define ( 'BASEPATH', 1 ); // defining the constant of codegniter
	require_once ("../SRM/Reports8/shared/helpers/Model/codegniter/Common.php");
	$wrong_directories = array();
	foreach ( $directories_to_read_write as $key => $val ) {
		$dir = str_replace("/SRM8","..",$val);
		//test write . 
		if (is_dir($dir) && is_really_writable ( $dir ) && is_writable ( $dir ) ) {
			$result = false;
			$fp = fopen($dir."tmp.php","w+");			
			if($fp){
			$result = fwrite($fp,"test");
			fclose($fp);
			}
			if(!$fp || !$result){
				$wrong_directories[] = $val;
			}
			if(file_exists($dir."tmp.php"))
			unlink($dir."tmp.php");
		}else{
			$wrong_directories[] = $val;
		}
	}

	
	if(!empty($wrong_directories)){
		$wrong_directories_str = implode(" , ",$wrong_directories);
		$error = "The following directories don't have read/write permissions : <br/> $wrong_directories_str  ";
	}
	
	
		 $_SESSION[$install_session_key."_last_view"] = 1;
		// proceed
		 $page = $_SERVER['PHP_SELF'];
                 echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
                 exit();
	
}

?>






<div class="panel-body text-center">
	<div id="intro1" class="instructions"
		style="border: 1px solid silver; border-radius: 4px;">
		<p
			style="background-color: white; padding: 5px; margin-bottom: 5px; border-radius: 4px;">
			<img src="../HomePage/DboardImages/01.jpg">
		</p>

		<p>
			Welcome to the installation wizard of Smart Report Maker <br /> version: <?php echo $version; ?></p>
		<br />
		 <?php if($error != ""){?>
		<div class="alert alert-danger"
			style="margin: 10px; text-align: left;">
			<strong>Error: </strong><span><?php echo $error;?></span>
		</div>
		<?php }?>
		
		<h5 style="text-align: left; color: navy; padding: 5px;">- Please make
			sure you give 755 permissions to the following diretories :</h5>
		<ul>
			
			<?php
			
foreach ( $directories_to_read_write as $key => $dir )
				echo "<li style='text-align:left;color:red'>$dir</li>";
			?>
				</ul>
		<br />

		<div class="alert alert-info" style="text-align: left; margin: 15px;">
			<strong>** If you want to include legacy reports (reports  created by any older
				versions of smart report maker) in this version, please do the following steps: </strong>
			<h5><?php echo $upgrade_notes?></h5>
		</div>



		<p class="text-center">

			<button class="btn btn-default" id="v" value="2" type="button"
				onclick="window.location = 'index.php?go=<?php echo $request_token_value;?>' ">
				Continue <i class="glyphicon glyphicon-chevron-right"></i>
			</button>

		</p>
	</div>


</div>