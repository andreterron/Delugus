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
	
	
	$loguser = userinfo(2);
	if (isset($_GET['id']))
		$user = userinfo(2, $_GET['id']);
	else
		$user = userinfo(2);
?>

<!DOCTYPE html>

<html>
<head>

<?php 

echo "<title>Delugus";

if ($user)
{
	if (isset($_COOKIE['user']) && $user['id'] == $_COOKIE['user'])
	{
		echo " - Meus Itens";
	} else {
		echo " - Itens de " . $user['fullname'];
	}
}
echo "</title>";

?>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens */
if (!$user) {
	echo "<meta http-equiv='Refresh' content='0;url=" . $redir_url . "' /></head><body></body></html>";
	die("");
} /**/
?>
</head>

<body>

<?php
	include("topbar.php");
	
	if ($error != null)
	{
		echo "<div class='error_msg'>$error</div>";
		die("");
	}
?>

<div class='contents-container'>
	<div class='contents'>
		<div class='main-contents'>
			<div class="maincontainer">
				<div class='main-col'>
					<div class="main-header">
						<?php
							if ($loguser['id'] != $user['id']) {
								$user = userinfo(2, $_GET['id']);
								echo "Itens de <a href='profile.php?id=" . $user['id'] . "'>" . $user['fullname'] . "</a>";
							} else {
								echo "Meus Itens:";
							}
						?>
						<!--a href="deals/newdeal.php"><div class="button">Nova Negociação</div></a-->
					</div>
					<div class="main-info">
	<?php
		$con = dbconnect();
		/* METODO 1: percorre a array dos itens do usuario dando varias querys pelos ids dos itens */
		if (isset($_GET['id']))
			$user = userinfo(2, $_GET['id']);
		else
			$user = userinfo(2);
		
		$start = time();
		$deals = read_str_array($user['deals']);
		$length = count($deals);
		for ($i = 0; $i < $length; $i++)
		{
			$query = "SELECT * FROM `tzdelugusdata`.`deals` WHERE `id` = '" . $deals[$i] . "' ORDER BY `id` DESC LIMIT 0 , 20;";
			$result = mysql_query($query, $con);
			if ($result && $row = mysql_fetch_array($result))
			{
				if ($row['owner'] == $user['id'])
				{
					echo draw_deal($row, 'list');
					//echo "<a href='http://www.delugus.com/deals/deal.php?id=" . $row['id'] . "'>" . $row['name'] . "</a><br/>";
				}
			}
			// METODO NAO TERMINADO! optei pela segunda opcao enquanto criava esse
			
		}
		/* echo "Metodo 1 demorou " . (time() - $start) . "ms"; /* FIM METODO 1 */
		/* METODO 2: da uma query por itens que o owner seja o usuario 
		$start = time();
		$query = "SELECT * FROM `tzdelugusdata`.`deals` WHERE `owner` = '" . $user['id'] . "';";
		$result = mysql_query($query, $con);
		if ($result)
		{
			while ($row = mysql_fetch_array($result))
			{
				echo $row['name'] . "<br/>";
			}
		}
		echo "Metodo 2 demorou " . (time() - $start) . "ms"; /* FIM METODO 2 */
		dbclose();
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

</body>
</html>