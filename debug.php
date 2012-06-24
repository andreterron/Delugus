<?php
	/* codigos especificos da pagina/app */
	$app = 'debug';
	
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
	
?>

<!DOCTYPE html>

<html>
<head>

<title>Delugus - PHP Code Debug</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens
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
						Saída do código:<br/>
						<div style="margin: 8px; padding: 8px; border: solid 1px #000000; font-family: monospace;">
							<?php
								/*echo $_SERVER['PHP_SELF'] . "<br/>";
								echo str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']) . "<br/>";
								$ary = explode('/', str_replace('.php', '', "/deals/deal.php"));
								if (!$ary[0]) {
									array_shift($ary);
								}
								print_r($ary);*/
								/*i = 100000000000;
								echo $i . "<br/>";
								$n = "";
								$l = 'a';
								echo "L = " . chr(ord($l)) . "<br/>";
								echo "L[0] = " . chr(ord($l[0])) . "<br/>";
								echo "L[0] + 1 = " . chr(ord($l) + 1) . "<br/>";
								while ($i > 0) {
									$j = $i % 52;
									$l = ($j < 26 ? 'a' : 'A');
									$n = chr(ord($l[0]) + ($j % 26)) . $n;
									$i = floor($i / 52);
								}
								echo $n;*/
								/*$fn = "photo/b.jpg";
								echo "File = " . $fn . "<br/>";
								echo "Exists = " . (file_exists($fn) ? "true" : "false") . "<br/>";*/
								//0ofzPzNJNOP2gV35-1RPbZLP8zdNYPSDmLVcg8Q
								//echo htmlentities("André", ENT_COMPAT, "UTF-8");
								//print_r(read_folder_files(1,'task',true));
								/*$f = read_file_info(47);
								print_r($f);
								echo "<br/><br/><br/>";
								print_r(read_str_array($f['type']));*/
								$attr = array();
								$attr['task'] = array();
								$bla = create_file('last test', '1', '21', $attr);
								echo "<br/><br/>$bla<br/><br/>";
								/*$str = "";
								echo $str . "<br/>";
								$str = push_str_array($str, 1);
								echo $str . "<br/>";
								$str = push_str_array($str, 2);
								echo $str . "<br/>";
								$str = push_str_array($str, 3);
								echo $str . "<br/>";
								$str = push_str_array($str, 4);
								echo $str . "<br/>";
								$str = push_str_array($str, 5);
								echo $str . "<br/>";*/
							?>
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