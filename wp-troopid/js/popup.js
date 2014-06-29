jQuery(document).ready(function($) {
	if(idmepopupvars.sandbox=='true'){
		var server = 'www.sandbox.id.me';
	} else {
		var server = 'www.id.me';
	}
	$(".troopid-login-trigger").on("click", function() {
		var top   = ($(document).height() - 780) / 4;
		var left  = ($(document).width() - 750) / 2;
		window.open("https://" + server + "/oauth/authorize?client_id=" + idmepopupvars.client_id + "&redirect_uri=" + idmepopupvars.redirect_uri + "&response_type=" + idmepopupvars.response_type + "&scope=" + idmepopupvars.scope + "&display=popup", "", "scrollbars=yes,menubar=no,status=no,location=no,toolbar=no,width=750,height=780,top=" + top + ",left=" + left);
	});
});