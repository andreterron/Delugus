<?php

include_once("functions.php");

$error = "";

function valid_data()
{
	$valid = true;
	// EMAIL OU USERNAME (no momento soh aponta como invalido caso tenha 2 ou mais @,
	// se tiver 1, eh email, se nao tiver nenhum, eh username
	if (!isset($_POST["login"]) || substr_count($_POST['login'], '@') > 1) {
		$error .= "Please enter a valid e-mail adress<br />";
		$valid = false;
	}
	// PASSWORD nchar >= 6
	if (isset($_POST["password"])) {
	
	} else {
		$valid = false;
		$error .= "Please enter a password containing at least 6 characters <br />";
	}
	//BIRTHDAY 
	return $valid;
}

$leresult = false;
$user = null;
$logged = false;
$user_command = "null";
$row_names = array('id', 'username', 'email', 'firstname', 'middlename', 'lastname', 'birthday', 'photo');
$size = count($row_names, 0);

if (isset($_COOKIE["user"]))
{
	$logged = false;
	$user = userinfo();
	$error .= "USUARIO " . $user['id'] . " JA ESTAVA LOGADO";
	setcookie('user', '', time()-3600);
}
//else if
if (true)
{
	//Connect To Database
	$usertable='users';

	$con = dbconnect();
	
	// TO DO criar uma funcao para criar querys, requerindo colunas pre-definidas
	if (substr_count($_POST["login"], '@') == 0) {
		$query = "SELECT * from `tzdelugusdata`.`users` WHERE `username` = '" . $_POST["login"] . "';";
	} else {
		$query = "SELECT * from `tzdelugusdata`.`users` WHERE `email` = '" . $_POST["login"] . "';";
	}
	$result = mysql_query($query, $con);
	if($result && $row = mysql_fetch_array($result)) {
		if (crypt($_POST["password"], $row["password"]) == $row["password"]) { 
			$leresult = setcookie("user", $row["id"], time()+3600*24*7);
			$logged = true;
			$error .= "ENTROU!";
		} else {
			$error .= "SENHA INCORRETA!</br>";
		}
	}
	else
	{
		$error .= "USERNAME NAO ENCONTRADO!</br>";
	}
	mysql_close($con);
	$user = userinfo();
} else {
	$error .= "DADOS INVALIDOS!";
}

/* guarda a url que o usuario veio, ou muda para a home caso tenha vindo da de login */
if (isset($_POST['redir_url']) && $_POST['redir_url'] != "http://www.delugus.com/login.php")
{
	$redir_url = $_POST['redir_url'];
}
else
{
	$redir_url = "http://www.delugus.com/";
}
?>

<!DOCTYPE html>

<html>
<!-- SIGN IN PAGE -->
<head>
<title>Delugus - login</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>
<?php
/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens */
if ($user || $logged) {
	echo "<meta http-equiv='Refresh' content='0;url=" . $redir_url . "' />";
} /**/
?>

</head>

<body>
<?php
// note que a topbar soh sera mostrada caso o login tenha sido falho
// retire o if para sempre mostrar a barra superior
if (!$user && !$logged) {
	include("topbar.php");
}

// Se o login funcionou, aponta uma mensagem de sucesso
if ($logged || $user)
{
	echo "Bem vindo " . $user["firstname"] . "<br/>";
	echo "Você será redirecionado em breve, caso não aconteça, <a href='" . $redir_url . "'>CLIQUE AQUI</a>.<br />";
	
}
else
{
// se nao funcionou, mostra a mensagem de erro recebida
	echo "<text style='color: #ff0000'>ERRO = NAO FUNCIONOU! ";
	if ($error && $error != "")
	{
		echo $error;
	}
	echo "</text>";
// TO DO colocar a tela de login/registrar ao falhar

}
//print_r($_COOKIE);
?>
</body>
<html>