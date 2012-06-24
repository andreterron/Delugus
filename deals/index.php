<?php
	/* codigos especificos da pagina/app */
	
	/* codigo que encontra o app baseado no lugar do arquivo, no entanto sera trabalhoso alterar */
	$app = explode('/', str_replace('.php', '', $_SERVER['PHP_SELF']));
	if (!$ary[0]) {
		array_shift($app);
	}
	
	// sobe para a raiz do site
	$l = count($app);
	for ($i = 1; $i < $l; $i++) {
		chdir(".."); 
	}
	
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
	
	$loguser = userinfo();
	
?>

<!DOCTYPE html>

<html>
<head>

<title>Delugus - Deals</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

</head>

<body id='body'>

<?php
	include("topbar.php");
?>

<div class='contents-container'>
	<div class='contents'>
		<div class='main-contents'>
			<div class="maincontainer">
				<div class='main-col'>
					<div class="main-header">
						<a href="deals/newdeal.php"><div class="button">Nova Negociação</div></a>
					</div>
					<div class="main-info">
						<?php
							
							/* Procurar em: ('what'=)
								0 - Tudo
								1 - Usuarios
								2 - Comunidades*
								3 - Itens*
								
								*checar permissoes
							*/
							
							if (!isset($_GET['what']) || $_GET['what'] == null) {
								$what = 0;
							} else {
								$what = $_GET['what'];
							}
							
							if ($what == 3 && !check_app_permissions('deals'))
								$what = 0;
							
							$con = dbconnect();
							
							/* ITEMS SEARCH */
							if ($what == 0 || $what == 3)
							{
								if ($loguser) {
									$query = "SELECT * FROM `tzdelugusdata`.`deals` WHERE `owner` <> " . $loguser['id'] . " ORDER BY `id` DESC LIMIT 0 , 20;";
								} else {
									$query = "SELECT * FROM `tzdelugusdata`.`deals` ORDER BY `id` DESC LIMIT 0 , 20;";
								}
								$result = mysql_query($query, $con);
								if ($result)
								{
									while ($row = mysql_fetch_array($result))
									{
										//echo "<a href='deals/deal.php?id=" . $row['id'] . "'>" . $row['name'] . "</a><br/>";
										//if ($row['owner'] != $loguser['id'])
											echo draw_deal($row, 'post');
									}
								}
							}
							mysql_close($con);
							//echo "</div>";
							//echo "<div class='main-footer'></div>";
							
						?>
					</div>
				</div>
			</div>
		</div>
		<div id='pagefooter'>
			<?php include("pagefooter.php"); ?>
		</div>
	</div>
</div>
	<!--div class='infobar'>LIKE US ON FACEBOOK!</div-->

<!--/div-->

</body>
</html>