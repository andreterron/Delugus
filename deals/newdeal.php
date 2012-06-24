<?php
	/* codigos especificos da pagina/app */
	$app = 'deals';
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

<!DOCTYPE html>

<html>
<head>

<title>Delugus - Novo item</title>
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

<body id='body'>

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
					<div class="main-info">
						<div class='main-header'>
							<form action='deals/createdeal.php' method='post' enctype="multipart/form-data">
								<!--`id`, `type`, `name`, `description`, `price`, `payperiod`, `date`, `pictures`-->
								Nome:<br/><input type='text' name='name'/><br/>
								Descrição:<br/><input type='text' name='description'/><br/>
								Tipo:<br/><select name='type'>
								  <option value="sell" selected>Venda</option>
								  <option value="lend">Aluguel</option>
								</select><br/>
								Preço:<br/><input type='text' name='price'/><select name='payperiod'>
								  <option value="0" selected>por vez</option>
								  <option value="3600">por hora</option>
								  <option value="86400">por dia</option>
								  <option value="604800">por semana</option>
								  <option value="2592000">por mes</option>
								  <option value="15768000">por semestre</option>
								  <option value="31536000">por ano</option>
								</select><br/>
								Tags:<br/><input type='text' name='tags'/><br/>
								<input type='submit' value='Publicar' />
							</form>
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