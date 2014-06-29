jQuery(document).ready(function($) {
    // Code here will be executed on document ready. Use $ as normal.
	var frag = $.deparam.fragment();
	for(var key in frag){
		if(frag.hasOwnProperty(key)){
			$.sessionStorage.set(key,frag[key]);
			$.cookieStorage.set(key,frag[key]);		
		}
	}
});