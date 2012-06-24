<?php
	/* codigos especificos da pagina/app */
	
	$page_title = null; /* Default: "Delugus" */
	$hide_sidebar = false; /* Default: null / false */
	
	/* codigo que encontra o app baseado no lugar do arquivo, no entanto sera trabalhoso alterar */
	$app = explode('/', str_replace('.php', '', $_SERVER['PHP_SELF']));
	if (!$app[0]) array_shift($app);
	
	// sobe para a raiz do site
	$l = count($app);
	for ($i = 1; $i < $l; $i++) chdir("..");
	
	/* codigo geral para todas as paginas */
	include_once("functions.php");
	
	/* INSIRA AQUI CODIGOS RELACIONADOS A PAGINA
	   principalmente se for setar cookies */
	
	/* a partir desse include, o usuario comeca a receber a pagina */
	include_once("headers.php");
	
	/* Coloque itens extras do header despois de include("headers.php") e antes de include("bodyTop.php"); */
?>

<?php include_once("bodyTop.php"); ?>

	<div class='warn_msg'>And here be DRAGOOONS!!!</div>

<?php include_once("bodyBottom.php"); ?>
