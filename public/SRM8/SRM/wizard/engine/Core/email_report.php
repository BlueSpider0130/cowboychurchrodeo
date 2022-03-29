<?php
/**
 * Smart Report Maker
 * Author : StarSoft 
 *All copyrights are preserved to StarSoft
 *http://mysqlreports.com/
 *
 */
define ( "DIRECTACESS", "true" );
// maximum subject and emails string length
$max_header_length = 70;
$max_message_length = 300;
$err = "";
require_once ("request.php");

if(isset($allow_captcha) && strtolower($allow_captcha) != "yes"){
	$obj_captcha = false;
}

/*
 * #################################################################################################
 * hANDLING SUPER GLOBALS
 * ################################################################################################
 */
$_CLEANED = remove_unexpected_superglobals ( $_POST, array (
			"from",
			"to",
			"subject",
			"message",
			"submit",
			"captcha" 
	) );

$_CLEANED = array_merge ( $_CLEANED, remove_unexpected_superglobals ( $_GET, array ()));
$_GET = array ();
$_POST = array ();
$_REQUEST = array ();
$_ENV = array ();
$_FILES = array ();
$_COOKIE = array ();
$URL_Back  = basename ( __DIR__ );
$URL_Back  .= ".php";
if(!file_exists($URL_Back)){
	$URL_Back  = $file_name . ".php"	;
}




//full report URL

$URL = $report_exact_url;



if(isset($allow_captcha) && strtolower($allow_captcha) != "yes"){
	$obj_captcha = false;
}

$from = isset ( $_CLEANED ["from"] ) ? get ( "from", "email", $_CLEANED ) : "";

$to = isset ( $_CLEANED ["to"] ) ? get ( "to", "email", $_CLEANED ) : "";

$subject = isset ( $_CLEANED ["subject"] ) ? get ( "subject", "string", $_CLEANED ) : "";

$message = isset ( $_CLEANED ["message"] ) ? get ( "message", "string", $_CLEANED ) : "$hi_lang \n $default_message_lang \n $URL";
$captcha_code = isset ( $_CLEANED ["captcha"] ) ? get ( "captcha", "string", $_CLEANED ) : "";

if (isset ( $_CLEANED["submit"] )) {
	
	
	// Validation
	if (empty ( $from ) || ! check_is_email ( $from ) || strlen ( $from ) > $max_header_length) {
		$err .= " *$invalid_from_lang<br/>";
	}
	
	if (empty ( $to ) || ! check_is_email ( $to ) || strlen ( $to ) > $max_header_length) {
		$err .= "*$invalid_to_lang <br/>";
	}
	
	if (	$obj_captcha != false && ! $obj_captcha->check_code ( $captcha_code )) {
		$err .= "*$invalid_code_lang <br/>";
	}
	
	if (empty ( $subject ) || ! check_no_specials ( $subject, array (
			"!",
			"?",
			".",
			" " 
	) ) || strlen ( $subject ) > $max_header_length) {
		$err .= "*$invalid_subject_lang <br/>";
	}
	
	if (empty ( $message ) || ! check_no_specials ( $message, array (
			"!",
			"?",
			".",
			":",
			",",
			"/",
			" " 
	) ) || strlen ( $message ) > $max_message_length) {
		
		$err .= "*$invalid_message_lang<br/>";
	}
	
	if (empty ( $err )) {
		
		$header = "From: Smart Report Maker<" . escape ( $from ) . ">\r\n";
		$header .= "Reply-To:Smart Report Maker<" . escape ( $from ) . ">\r\n";
		$header .= "MIME-Version: 1.0" . "\r\n";
		$header .= "Content-type: text/html; charset=UTF-8" . "\r\n";
		$message = escape ( $message );
		$message = str_ireplace ( "\n", "<br/>", $message );
		$full_message = '<html><head>   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$full_message .= "<title>" . escape ( $subject ) . "</title></head>";
		$full_message .= "<body>$message </body></html>";
		
		if (@mail ( escape ( $to ), escape ( $subject ), $full_message, $header ))
			$err .= "$success_lang '$to'. <br/> 	$back_email_lang";
		else
			$err .= "$SMTP_lang";
	}
}

?>
<html
	<?php
	
	if ($language == "he" || $language == "ar") {
		echo "dir = 'rtl'";
	}
	?>>

<head>
<meta http-equiv="Content-Type"
	content="text/html; charset= charset=UTF-8">
<title>send email</title>

</head>

<body class="MainPage" style="text-align: center" topmargin="0"
	leftmargin="0" rightmargin="0" bottommargin="0">
	<form name="form1" method="post" action="">
		<TABLE align="center" id=table3 borderColor=#f3f1ef height=369
			cellSpacing=0 cellPadding=4 width="561" align=left border=0>
			<TR>
				<TD align=left background=../shared/images/icons/bg_table.gif colSpan=2 height=25>
					<p align="right">
						<font color="#000080"><u> <a href="<?php echo $URL_Back;?>"><font
									color="#000080"><?php echo $back_lang?></font></a></u></font>
				
				</TD>
			</TR>
			<TR bgColor=#f3f1ef>
				<TD colSpan=2 height=21>
					<p>
						<font color="#FF0000">
						<?php
						
						if (! empty ( $err )) {
							echo ($err);
						}
						
						?>


                        </font>
				
				</TD>
			</TR>
			<TR bgColor=#f3f1ef>
				<TD align=right height=21 width="150"><b><?php echo escape($from_email_lang);?></b></TD>
				<TD width="491"><FONT face="Times New Roman"> <INPUT size=32
						name=from value="<?php echo $from; ?>">
				</FONT></TD>
			</TR>
			<TR bgColor=#e4ded8>
				<TD borderColor=#f3f1ef align=right bgColor=#f3f1ef height=21
					width="150"><b><?php echo escape($to_email_lang);?></b></TD>
				<TD borderColor=#f3f1ef bgColor=#f3f1ef width="491"><FONT
					face="Times New Roman"> <INPUT id=email size=32 name=to
						value="<?php echo $to; ?>">
				</FONT></TD>
			</TR>
			<TR bgColor=#e4ded8>
				<TD borderColor=#f3f1ef align=right bgColor=#f3f1ef height=21
					width="150"><b><?php echo escape($subject_lang)?></b></TD>
				<TD borderColor=#f3f1ef bgColor=#f3f1ef width="491"><FONT
					face="Times New Roman"> <INPUT id=subject size=32 name=subject
						value="<?php echo $subject; ?>"> <font color="green"><?php echo "*".$max_length_lang." ". $max_header_length ." ". $characters_lang?> </font>
				</FONT></TD>
			</TR>
			<TR bgColor=#e4ded8>
				<TD borderColor=#f3f1ef align=right bgColor=#f3f1ef height=158
					width="150"><b><?php echo escape($message_lang);?></b></TD>
				<TD vAlign=top borderColor=#f3f1ef bgColor=#f3f1ef width="491"><FONT
					face="Times New Roman"> <TEXTAREA id=desc name=message rows=10
							cols=54><?php echo $message; ?>
</TEXTAREA>
				</FONT><font color="green"><br/><?php echo"*".$max_length_lang." ".$max_message_length." ".$characters_lang?><br /> <?php echo  "*$no_specials_lang";    ?></font></TD>
			</TR>
     <?php if($obj_captcha){?>
			<TR bgColor=#e4ded8>
				<TD borderColor=#f3f1ef align=right bgColor=#f3f1ef height=21
					width="150"><b><?php echo escape($security_code_lang);?></b></TD>
				<TD borderColor=#f3f1ef bgColor=#f3f1ef width="491"><img
					id="captcha" src="../shared/images/captcha_image.php" width="400"
					height="30" alt="CAPTCHA"> <br /> <input id="captcha"
					type="text" name="captcha"> <small><font color="green">*<?php echo $securiy_code_hint_lang;?></font></small>
				</TD>
			</TR>
			<?php } ?>   

			<TR bgColor=#e4ded8>
				<TD borderColor=#f3f1ef align=right bgColor=#f3f1ef height=56
					colspan="2">
					<p align="center">
					
						<input type="submit" value="<?php echo $send_lang ?>"
							name="submit">
				
				</TD>
			</TR>
		</TABLE>
		</from>
		
</body>
</html>
