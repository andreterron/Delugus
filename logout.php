<?php

include_once("functions.php");

$error = "";

setcookie('user', '', time()-3600);

/* por enquanto sempre redireciona para home ao fazer o logout */
$redir_url = "http://www.delugus.com/";

?>

<!DOCTYPE html>

<html>
<!-- LOGOUT PAGE -->
<head>
<title>Delugus - logout</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>
<?php
	// codigo que vai fazer com que o usuario seja redirecionado
	echo "<meta http-equiv='Refresh' content='0;url=" . $redir_url . "' />";
?>

</head>

<body>
<?php

	echo "Você será redirecionado em breve, caso não aconteça, <a href='" . $redir_url . "'>CLIQUE AQUI</a>.<br />";

?>
</body>
<html>