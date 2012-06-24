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
	
	$loguser = userinfo(1);
	if (isset($_GET['to'])) {
		$user = userinfo(1, $_GET['to']);
	}
	
	/* Marca todas as mensagens recebidas como lidas */
	if ($loguser && $user)
	{
		$query = "UPDATE `tzdelugusdata`.`message` SET `viewed`='1' WHERE
		(`from`='" . $user['id'] . "' AND `to`='" . $loguser['id'] . "');";
		
		dbconnect();
		
		$result = mysql_query($query, $con);
		
		if ($result) {
			// MENSAGENS LIDAS COM SUCESSO
		} else {
			// ERRO NA LEITURA DAS MENSAGEM
		}
		
		dbclose();
		
	}
	
	/* Checa se uma mensagem foi enviada */
	if ($loguser && $user && isset($_POST['msg']))
	{
		
		$query = "INSERT INTO  `tzdelugusdata`.`message` (
		`id` ,
		`to` ,
		`from` ,
		`time` ,
		`text` ,
		`viewed`
		)
		VALUES (NULL";
		// TO
		$query .= ",'" . $user['id'] . "'";
		// FROM
		$query .= ",'" . $loguser['id'] . "'";
		// TIME (note que ele guarda a data no GMT)
		$query .= ",'" . gmdate("Y-m-d G:i:s") . "'";
		// TEXT
		$query .= ",'" . $_POST['msg'] . "'";
		// VIWED
		$query .= ",'0'";
		$query .= ");";
		
		dbconnect();
		
		$result = mysql_query($query, $con);
		
		if ($result) {
			// MENSAGEM CRIADA COM SUCESSO
			// TO DO Enviar e-mail
			if (isset($_POST['item']))
			{
				dbconnect();
		
				$query = "SELECT `id`, `name` FROM `tzdelugusdata`.`deals` WHERE `id` = '" . $_POST['item'] . "';";
				$result = mysql_query($query, $con);
				if ($result && $row = mysql_fetch_array($result))
				{
					/*`id`, `type`, `name`, `description`, `price`, `payperiod`, `date`, `pictures`*/
					$deal = $row;
				}
				else
				{
					$deal = null;
				}
				
				dbclose();
				
				if ($deal) {
					$to = $user['fullname'] . " <" . $user['email'] . ">\r\n";
					$subject = "Delugus - DeShare - Mensagem de " . $loguser['fullname'] . " sobre o item " . $deal['name'];
					$message = $loguser['fullname'] . " te enviou uma mensagem relacionada ao item \"" . $deal['name'] . "\":\n\n\"" . $_POST['msg'] .
					"\"\n\nResponda a essa mensagem no site do Delugus, ou responda diretamente esse e-mail.\n\nObrigado por escolher a Delugus!";
					$headers = "From: " . $loguser['fullname'] . " <" . $loguser['email'] . ">\r\n";
					mail($user['email'],$subject,$message,$headers);
					$redir_url = "http://www.delugus.com/deals/deal.php?id=" . $_POST['item'];
				}
			}
		} else {
			// ERRO NA CRIACAO DA MENSAGEM
		}
		
		dbclose();
	}
	
?>

<!DOCTYPE html>

<html>
<head>

<title>Delugus - Message</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens */
if (!$loguser || !$user || $user['id'] == $loguser['id'] || isset($_POST['item'])) {
	echo "<meta http-equiv='Refresh' content='0;url=" . $redir_url . "' /></head><body></body></html>";
	die("");
} /**/
?>
</head>

<body>

<?php
	include("topbar.php");
?>

<div class='contents-container'>
	<div class='contents'>
		<div class='main-contents'>
			<div class="maincontainer">
				<div class='main-col'>
					<div class="main-header">
						Conversa com <?php echo "<a class='nocolor' href='profile.php?id=" . $user['id'] . "'>" . $user['fullname'] . "</a>"; ?>:
					</div>
					<div class="main-info">
						<?php
							/*if (isset($_POST['msg']))
							{
								echo "<div class='userpost'>Sua mensagem \"" . $_POST['msg'] . "\" não foi enviada pois essa funcionalidade não foi implementada ainda!</div>";
							}*/
							
							$query = "SELECT * FROM `tzdelugusdata`.`message` WHERE
							((`to`='" . $loguser['id'] . "' AND `from`='" . $user['id'] . "') OR
							(`to`='" . $user['id'] . "' AND `from`='" . $loguser['id'] . "')) ORDER BY `id` DESC LIMIT 0, 40;";
							
							dbconnect();
							
							$txt = "";
							$oldfrom = null;
							
							$result = mysql_query($query, $con);
							
							if ($result)
							{
								while ($row = mysql_fetch_array($result))
								{
									if (!$oldfrom)
									{
										$txt .= "</div>";
									}
									if ($oldfrom && $row['from'] != $oldfrom['id'])
									{
										$txt = "</div><div class='photopost'><div class='userpost-photo'>
										<a href='profile.php?id=" . $oldfrom['id'] . "'>" .
										get_user_photo($oldfrom) . "</a></div><a href='profile.php?id=" . $oldfrom['id'] . 
										"' style='font-weight: bold;'>" . $oldfrom['fullname'] .
										"</a><br/>" . $txt;
									}
									$txt = $row['text'] . "<br/>" . $txt;
									$oldfrom = ($row['from'] == $user['id'] ? $user : $loguser);
								}
								if ($oldfrom) {
									$txt = "<div class='photopost'><div class='userpost-photo'>
									<a href='profile.php?id=" . $oldfrom['id'] . "'>" .
									get_user_photo($oldfrom) . "</a></div>
									<a href='profile.php?id=" . $oldfrom['id'] . 
									"' style='font-weight: bold;'>" . $oldfrom['fullname'] .
									"</a><br/>" . $txt;
								}
							}
							
							echo $txt;
							
							dbclose();
						?>
						<div class='userpost'>
							<?php echo "<form action='message.php?to=" . $user['id'] . "' method='post'>"; ?>
								<input type='text-area' autocomplete='off' name='msg' placeholder="Escreva sua mensagem" style="resize: none; width: 100%;" /><br />
								<input type='submit' value='enviar'/>
							<?php echo "</form>"; ?>
						</div>
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