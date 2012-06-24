<?php
	/* codigos especificos da pagina/app */
	$app = 'debug';
	
	/* codigo geral para todas as paginas */
	include_once("functions.php");
	
	$loguser = userinfo(1);
	
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
	Saída do código:<br/>
	<div style="margin: 8px; padding: 8px; border: solid 1px #000000; font-family: monospace;">
		<?php
			print_r(find_event("372363072819496"));
		?>
	</div>

</body>
</html>