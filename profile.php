<?php
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
	
	$loguser = userinfo(-1);
	if (isset($_GET['id']) && $_GET['id'] != $loguser['id'])
		$user = userinfo(-1, $_GET['id']);
	else
		$user = $loguser;
?>
<!DOCTYPE html>

<html>
<head>

<title><?php if ($user) echo $user['fullname'] . " - "; ?>Delugus</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
	/* checa se o usuario esta logado, caso nao esteja, redireciona para a home,
	imprime o resto das tags e termina o script
	$user = userinfo(2);
	if (!$user)
	{
		echo "<meta http-equiv='Refresh' content='0;url=$redir_url' />";
		echo "
			</head>
			<body id='body'>
			</body>
			</html>";
		die("");
		$error = "Você deve estar logado para poder usar o DeShare";
	}
	else if ($user['deals'] == null)
	{
		$error = "Desculpe, DeShare está em fase de testes, somente alguns usuários podem usá-lo no momento. Volte em breve!";
	}*/
?>
</head>

<body>

<?php
	include("topbar.php");
	
	
	if (!$error && !$user)
	{
		$error = "Usuário não encontrado";
	}
	
	if ($error != null)
	{
		echo "<div class='error_msg'>$error</div>";
		die("</body></html>");
	}
	
	
?>

<div class='contents-container'>
<div class='contents'>
	<div class='main-contents'>
		<!--<div class='sidebar'>
			<?php
				/*echo $user['fullname'] . "<br/><br/>";
				echo "<a href='deals/userdeals.php?id=" . $user['id'] . "'>Itens</a><br/>";
				echo "<a href='message.php?to=" . $user['id'] . "'>Mensagens</a><br/>";*/
			?>
		</div-->
		<div class="maincontainer">
			<div class='main-col'>
				<div class="main-header">
					Atualizações:
				</div>
				<div class="main-info">
<?php
	$con = dbconnect();
	/* METODO 1: percorre a array dos itens do usuario dando varias querys pelos ids dos itens */
	
	$start = time();
	$deals = read_str_array($user['deals']);
	$length = count($deals);
	for ($i = $length - 1; $i >= 0; $i--)
	{
		$query = "SELECT * FROM `tzdelugusdata`.`deals` WHERE `id` = '" . $deals[$i] . "' LIMIT 1;";
		$result = mysql_query($query, $con);
		if ($result && $row = mysql_fetch_array($result))
		{
			if ($row['owner'] == $user['id'])
			{
				echo draw_deal($row, 'post');
				//echo "<a href='http://www.delugus.com/deals/deal.php?id=" . $row['id'] . "'>" . $row['name'] . "</a><br/>";
			}
		}
		// METODO NAO TERMINADO! optei pela segunda opcao enquanto criava esse
		
	}
	/* echo "Metodo 1 demorou " . (time() - $start) . "ms"; /* FIM METODO 1 */
	/* METODO 2: da uma query por itens que o owner seja o usuario 
	$start = time();
	$query = "SELECT * FROM `tzdelugusdata`.`deals` WHERE `owner` = '" . $user['id'] . "' ORDER BY `id` DESC LIMIT 0 , 20;";
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