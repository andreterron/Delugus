<?php
	/* codigos especificos da pagina/app */
	$app = 'debug';
	
	/* codigo geral para todas as paginas */
	include_once("functions.php");
	$debug = true;
	
?>

<!DOCTYPE html>

<html>
<head>

	<title>Delugus - PHP Code Debug</title>
	<base href="http://www.delugus.com/"/>
	<link rel="stylesheet" href="style.css" type = "text/css" />
	<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
	<script type="text/javascript" src="javascript.js"></script>
</head>

<body>
	<div id="fb-root"></div>
		<script>
		window.fbAsyncInit = function() {
		  FB.init({
			appId      : '446976568660000', // App ID
			channelUrl : '//photos.delugus.com/channel.php', // Channel File
			status     : true, // check login status
			cookie     : true, // enable cookies to allow the server to access the session
			xfbml      : true  // parse XFBML
		  });
		};
		// Load the SDK Asynchronously
		(function(d){
		   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		   if (d.getElementById(id)) {return;}
		   js = d.createElement('script'); js.id = id; js.async = true;
		   js.src = "//connect.facebook.net/en_US/all.js";
		   ref.parentNode.insertBefore(js, ref);
		 }(document));
		</script>

		<div class="fb-login-button">Login with Facebook</div>
	Saída do código:<br/>
	<div style="margin: 8px; padding: 8px; border: solid 1px #000000; font-family: monospace;">
		<?php
			$debug = true;
			try {
				print_r(find_event("372363072819496"));
				//print_r(get_user_events("1733755250"));
			} catch (Exception $e) {
				echo "<br/><br/><br/><br/><br/>";
				print_r($e);
			}
		?>
	</div>

</body>
</html>