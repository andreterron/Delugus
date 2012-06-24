<?php
	/* codigos especificos da pagina/app */
	$app = 'lines';
	chdir(".."); // sobe para a raiz do site 
	
	/* codigo geral para todas as paginas */
	include_once("functions.php");
	$redir_url = "http://www.delugus.com";
	$error = check_app_permissions($app);
	if ($error && is_secret($app))
	{
		/* se ocorreu um erro, e o app eh secreto, carrega a pagina do erro 404 e nao mostra mais nada */
		include("missing.html");
		die("");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Delugus_Lines</title>
	<base href="http://www.delugus.com/"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" href="style.css" type = "text/css" />
	<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />

	<script type="text/javascript" src="javascript.js"></script>
	
	
	<script src="lines/js/swfobject.js" type="text/javascript"></script>
	<script type="text/javascript">
		var flashvars = {
		};
		var params = {
			menu: "false",
			scale: "noScale",
			allowFullscreen: "true",
			allowScriptAccess: "always",
			bgcolor: "#000000"
		};
		var attributes = {
			id:"DelugusLines"
		};
		swfobject.embedSWF("lines/DelugusLines.swf", "altContent", "100%", "100%", "10.0.0", "lines/expressInstall.swf", flashvars, params, attributes);
	</script>
	<style type="text/css">
		html, body { height:100%; overflow:hidden; }
		body { margin: 0px; }
	</style>
</head>
<body>
	<?php
		include("topbar.php");
	?>
	<div id="altContent">
		<h1>DeLines</h1>
		<p>Alternative content</p>
		<p><a href="http://www.adobe.com/go/getflashplayer"><img 
			src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" 
			alt="Get Adobe Flash player" /></a></p>
	</div>
</body>
</html>