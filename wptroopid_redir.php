<?php
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}
?>

<html>
<head>
<script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/jquery-bbq-1.2.1/jquery.ba-bbq.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie-1.4.1.min.js"></script>
<script type="text/javascript" src="js/jQuery-Storage-API-1.7.2/jquery.storageapi.min.js"></script>
<script type="text/javascript" src="js/redir.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Your application logic to handle the OAuth response
	
		window.opener.postMessage($.sessionStorage.get('access_token'),window.location.protocol + '//' + window.location.host);
		//window.opener.location.reload()
		window.close();
	});
</script>
</head>
<body>
</body>
</html>