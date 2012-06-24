<?php	
	include_once("functions.php");
	
	//Conecta com o Banco
	$con = dbconnect();
	
	//Caso falhe, retorna erro
	if (!$con)
	die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysql_error());

	//Gera query dos posts
	$nome = $_POST["firstname"];
	$sobrenome = $_POST["lastname"];
	$email = $_POST["email"];
	$password = crypt($_POST["password"]);
	$gender = $_POST["gender"];
	$day = $_POST["day_birth"];
	$month = $_POST["month_birth"];
	$year = $_POST["year_birth"];
	
	//Converte as datas para formato Y-m-d
	$birthday = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	
	//Valida os dados
	$ctrl = 0;
	$msg = "";
	if(!$nome){
		$ctrl = 1;
		$msg .="Falta preencher seu nome.<br />";
	}
	if(!$_POST["password"] || strlen($_POST["password"])<8){
		$ctrl = 1;
		$msg .="Password com menos de 8 caracteres.<br />";
	}
	
	if(!$_POST["confirm"] || $_POST["confirm"] != $_POST["password"])
	{
		$ctrl = 1;
		$msg .= "As senhas são diferentes.<br />";
	}
	
	if(!$email || substr_count($email, '@') != 1){
		$msg .= "Insira um e-mail válido.<br />";
		$ctrl = 1;
	}
		
	if(!$sobrenome){
		$msg .="Falta preencher seu sobrenome.<br />";
		$ctrl = 1;
	}
	
	if(!$day || !$month || !$year){
		$msg .="Erro na data.<br />";
		$ctrl = 1;
	}


	//Gera Query
	if (!$ctrl)
	{
		$query = "INSERT INTO  `tzdelugusdata`.`users` (
			`id` ,
			`username` ,
			`password` ,
			`email` ,
			`firstname` ,
			`lastname` ,
			`birthday` ,
			`gender` ,
			`photo` ,
			`deals` ,
			`friends` ,
			`groups` ,
			`tasks`
		)
		VALUES (
			NULL ,
			NULL ,
			'$password',
			'$email',
			'$nome',
			'$sobrenome',
			'$birthday',
			'$gender',
			NULL ,
			NULL ,
			'', 
			NULL , 
			NULL
		);";
		
		//Result tem a chamada do banco
		$result = mysql_query($query,$con);
		
		if($result) {
			$msg = "Seu cadastro foi realizado com sucesso!";
			setcookie("user", mysql_insert_id($con), time()+3600*24*7);
		}
	}
?>


<!DOCTYPE html>

<html>
<head>

<title>Delugus - Registro</title>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php

/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens */
if (!$deal) {
	echo "<meta http-equiv='Refresh' content='0;url=http://www.delugus.com/' />";
	die('</head><body></body></html>');
} /**/

?>
</head>

<body>

<?php
	include("topbar.php");
	
	if ($msg)
	{
		echo "<div class='error_msg'>$msg</div></body></html>";
		die("");
	}
?>

</body>
</html>