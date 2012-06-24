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
	
	function validate_data()
	{
		$err = null;
		/* `id` ,
		`type` ,
		`name` ,
		`description` ,
		`price` ,
		`payperiod` ,
		`date` ,
		`pictures` ,
		`owner` */
		// TYPE
		$valid_types = array('sell', 'lend');
		if (!isset($_POST['type']) || !in_array($_POST['type'], $valid_types))
		{
			$err = ($err == null ? '' : $err . '<br/>') .  "Tipo de negociação inválido";
		}
		// NAME
		if (!isset($_POST['name']) || $_POST['name'] == null)
		{
			$err = ($err == null ? '' : $err . '<br/>') .  "Insira um nome para seu item";
		}
		// PRICE
		if (!isset($_POST['price']) || $_POST['price'] == null)
		{
			$err = ($err == null ? '' : $err . '<br/>') .  "Insira preço para a negociação";
		}
		// PAYPERIOD
		
		return $err;
	}
	
	/* valida as informacoes vindas do usuario */
	if (!$error)
		$error = validate_data();
	
	if (!$error)
	{
		$query = "INSERT INTO  `tzdelugusdata`.`deals` (
		`id` ,
		`type` ,
		`name` ,
		`owner` ,
		`description` ,
		`price` ,
		`payperiod` ,
		`date` ,
		`pictures` ,
		`tags`
		)
		VALUES (NULL";
		// TYPE
		$query .= ",'" . $_POST['type'] . "'";
		// NAME
		$query .= ",'" . $_POST['name'] . "'";
		// OWNER
		$user = userinfo(3);
		$query .= ",'" . $user['id'] . "'";
		// DESCRIPTION
		if (isset($_POST['description']) && $_POST['description'] != null) {
			$query .= ",'" . $_POST['description'] . "'";
		} else {
			$query .= ",NULL";
		}
		// PRICE
		// TO DO: remove non-number characters
		$query .= ",'" . $_POST['price'] . "'";
		// PAYPERIOD
		if (isset($_POST['payperiod']) && $_POST['payperiod'] != null) {
			$query .= ",'" . $_POST['payperiod'] . "'";
		} else {
			$query .= ",NULL";
		}
		// DATE (note que ele guarda a data no GMT)
		$query .= ",'" . gmdate("Y-m-d G:i:s") . "'";
		// PICTURES
		$query .= ",NULL";
		// TAGS
		$query .= ",'" . $_POST['tags'] . "'";
		//NULL ,  'lend',  'Colchonete', NULL ,  '20',  '604800',  '2012-02-21 23:58:45', NULL ,  '1'
		$query .= ");";
		
		dbconnect();
		$result = mysql_query($query, $con);
		
		if ($result) {
			$str = push_str_array($user['deals'], mysql_insert_id($con));
			$query = "UPDATE  `tzdelugusdata`.`users` SET  `deals` = '" . $str . "' WHERE `users`.`id` = " . $user['id'] . " LIMIT 1 ;";
			$result = mysql_query($query, $con);
			if ($result) {
				$error .= "Parabéns! Seu item foi criado com sucesso!";
			} else {
				$error .= "Erro na atualizacao do usuario!";
			}
		} else {
			$error .= "Erro na conexao com a database";
		}
		
		dbclose();
		
	}
	
	//"SELECT `id`, `type`, `name`, `description`, `price`, `payperiod`, `date`, `pictures`, `owner` FROM `deals` WHERE 1"
	
	
?>
<!DOCTYPE html>

<html>
<head>

<title>Delugus - Criando Item...</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php

?>
</head>

<body id='body'>

<?php
	if ($error != null)
	{
		include("topbar.php");
		echo "<div class='error_msg'>$error</div>";
		die("");
	}
	else
	{
		include("topbar.php");
		echo "<div class='error_msg'>Parabéns, seu item foi criado com sucesso!</div>";
	}
?>

</body>
</html>