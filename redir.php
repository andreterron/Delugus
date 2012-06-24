<?php
if (isset($_GET['redirurl']))
{
	$redir_url = $_GET['redirurl'];
}
?>

<!DOCTYPE html>

<html>
<!-- SIGN IN PAGE -->
<head>
<title>Delugus - redirect</title>
<base href="http://www.delugus.com/"/>
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<?php
/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens */

	echo "<meta http-equiv='Refresh' content='0;url=" . $redir_url . "' />";
/**/
?>

</head>

<body>

</body>
<html>