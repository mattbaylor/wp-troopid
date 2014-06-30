jQuery(document).ready(function($) {
$.receiveMessage(
  function(e){
	 if(idmepopupvars.sandbox=='true'){
		var server = 'api.sandbox.id.me';
	} else {
		var server = 'api.id.me';
	}
    $.getJSON('https://' + server + '/v2/' + idmepopupvars.scope + '.json?callback=?&access_token=' + e.data,function(data){
		if(data.verified){
			if (typeof troopidformload === 'undefined') {
    			
			} else {
				$("[name='"+troopidformload.first_name+"']").val(data.first_name).addClass('prefilled');	
				$("[name='"+troopidformload.last_name+"']").val(data.last_name).addClass('prefilled');
				$("[name='"+troopidformload.email+"']").val(data.email).addClass('prefilled');
				$("[name='"+troopidformload.phone+"']").val(data.phone).addClass('prefilled');
				$("[name='"+troopidformload.zip+"']").val(data.zip).addClass('prefilled');
				$("[name='"+troopidformload.verified+"']").val(data.verified).addClass('prefilled');
				$("[name='"+troopidformload.affiliation+"']").val(data.affiliation).addClass('prefilled');
				$("[name='"+troopidformload.service_started+"']").val(data.service_started).addClass('prefilled');
				$("[name='"+troopidformload.service_ended+"']").val(data.service_ended).addClass('prefilled');
			}
			$('.troopid-verified').html('Verified');
		}	
	});
  }
);
});