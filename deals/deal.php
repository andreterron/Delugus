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
	$redir_url = "http://www.delugus.com/";
	$loguser = userinfo(3);
	$error = check_app_permissions($app);
	if ($error && is_secret($app))
	{
		/* se ocorreu um erro, e o app eh secreto, carrega a pagina do erro 404 e nao mostra mais nada */
		include("missing.html");
		die("");
	}
	else if ($error)
	{
		$redir_url = "http://www.delugus.com/";
	}
	
	/* le o item */
	$deal = null;
	if (isset($_GET['id']))
	{
		$con = dbconnect();
		
		$query = "SELECT * FROM `tzdelugusdata`.`deals` WHERE `id` = '" . $_GET['id'] . "';";
		$result = mysql_query($query, $con);
		if ($result && $row = mysql_fetch_array($result))
		{
			/*`id`, `type`, `name`, `description`, `price`, `payperiod`, `date`, `pictures`*/
			$deal = $row;
		}
		else
		{
			$error = "Item não encontrado";
			$redir_url = "http://www.delugus.com/deals/userdeals.php";
		}
		
		dbclose();
	}
?>

<!DOCTYPE html>

<html>
<head>

<title>Delugus - Item - <?php echo $deal['name']; ?></title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php

/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens */
if (!$deal) {
	echo "<meta http-equiv='Refresh' content='0;url=" . $redir_url . "' />";
	echo "</head><body></body></html>";
	die('');
} /**/

?>
</head>

<body>

<?php
	include("topbar.php");
	
	if ($error != null)
	{
		echo "<div class='error_msg'>$error</div>";
		die("</body></html>");
	}
?>

<div class='contents-container'>
	<div class='contents'>
		<div class='main-contents'>
			<div class="maincontainer">
				<div class='main-col'>
					<div class="main-info">
						<div class='main-header'>
	<?php
		
		echo "<text style='font-size: 32px; font-weight: bold;'>" . $deal['name'] . "</text><br/>";
		echo "<div style='height: 20px;'><div class='fb-like' data-href='http://www.delugus.com/deals/deal.php?id=" . $deal['id'] . "' data-send='true' data-layout='button_count' data-width='450' data-show-faces='true' data-action='recommend'></div></div>";
		echo "<text style='font-size: 24px; font-weight: bold;'>R$" . $deal['price'] . "</text> ";
		echo "<text style='font-size: 16px; font-weight: bold;'>". read_payperiod($deal['payperiod'], $deal['type']) . "</text><br/>";
		echo "Divulgado em " . $deal['date'] . "<br/>";
		if ($deal['description'])
			echo "Descrição:<br/>" . $deal['description'] . "<br/>";
		
		
	?>
						</div>
						<?php
						$user = userinfo(3, $deal['owner']);
						if ($loguser && $user) {
				echo "	<div class='userpost'>
							<form action='message.php?to=" . $user['id'] . "' method='post'>
								<textarea autocomplete='off' name='msg' placeholder='Escreva sua mensagem (será enviada também por e-mail para o dono)' style='resize: none; width: 100%;'></textarea><br />
								<input type='hidden' name='item' value='" . $deal['id'] . "' />
								<input type='submit' value='enviar'/>
							</form>
						</div>";
						}
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