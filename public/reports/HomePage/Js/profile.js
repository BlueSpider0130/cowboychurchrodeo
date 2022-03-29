$(document).ready(function() {

	togglel_disable_ip();
	toggle_disable_home();
	 change_password ();
	$("#isFixedIP").on("change", togglel_disable_ip);
	$("#ishome").on("change", toggle_disable_home);
	$("#iscaptcha").on("change", confirm_disable_captcha);
	$("#isChangePassword").on("change",change_password);
});

function togglel_disable_ip() {
	if ($("#isFixedIP").val() == "no") {
		$("#ip").attr("disabled", "disabled");
	} else {
		$("#ip").removeAttr("disabled");
	}
}

function toggle_disable_home() {
	if ($("#ishome").val() == "no") {
		$("#homeURL").attr("disabled", "disabled");
	} else {
		$("#homeURL").removeAttr("disabled");
	}
}

function confirm_disable_captcha() {
	if ($("#iscaptcha").val() == "no") {
		val = confirm("It's recommended to enable captcha for security reason. Are you sure you want to disable it ?");
		if (val) {
			return true;
		} else {
			return false;
		}
	}
}

function change_password (){
	if($("#isChangePassword").val() == "no"){
		$(".pass").hide();
		
	}else{
		$(".pass").show();
		
	}
}