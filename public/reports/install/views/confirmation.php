
<?php
if (! defined ( "DIRECTACESS" ))
	die ( "Error 400 : No direct script access allowed" );
	$error = "";
	//check installation
	

	//test that there is an admin file, username and a password . 
	if(file_exists($admin_file) && $profile->get_current_password() != "" && $profile->get_username()!= ""){
		$installation_result = true;
		$confirmation_message = "Your installation is complete!";
	}else{
		$installation_result = false;
		$confirmation_message = "Installation process was failed, No profile was created!<br/> please empty your browser cash then try re-installing the system";
	}
	
	$_SESSION [$install_session_key . "_last_view"] = 2;
	if (isset ( $_CLEANED ['go'] )) {
		
		
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
			<i><?php echo $confirmation_message;?> </i></p>
		<br />
		
		<?php if($installation_result){?>
		<div class="alert alert-danger" style="text-align: left; margin: 15px;">
		<strong>To start using smart report maker please do the following two steps: </strong>
		<h5 style="text-align: left; color: navy; padding: 5px;">** Please <strong>delete </strong>the installation directory from your server at <?php echo dirname($install_exact_url);?> <br/>** Then please <strong>start </strong>using smart report maker by loggin to the home page at: <?php echo dirname($homepage_exact_url)."/login.php";?> </h5>
		
		</div>
		<?php }?>



		
	</div>


</div>