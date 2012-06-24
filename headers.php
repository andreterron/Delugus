<!DOCTYPE html>

<html>
<head>

<title><?php echo ($page_title ? $page_title : "Delugus"); ?></title>
<base href="<?php echo $base_url; ?>"/>
<meta name="title" content="Home" />
<meta name="description" content="Delugus - Anuncie! Compartilhe! Ganhe!" />
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="<?php echo $base_url;?>/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens
if (isset($_COOKIE['user'])) {
	echo "<meta http-equiv='Refresh' content='0;url=http://www.delugus.com/deals' /></head><body></body></html>";
	die("");
} /**/
?>
