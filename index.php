<?php
	include_once("functions.php");
?>

<!DOCTYPE html>

<html>
<head>

<title>Delugus</title>
<base href="http://127.0.1.1/"/>
<meta name="title" content="Home" />
<meta name="description" content="Delugus - Anuncie! Compartilhe! Ganhe!" />
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
/* codigo que faz com que o usuario seja redirecionado para a pagina em que ele estava
   coloque tudo em comentario para continuar na pagina, facilitando a visualizacao de mensagens */
if (isset($_COOKIE['user'])) {
	echo "<meta http-equiv='Refresh' content='0;url=http://www.delugus.com/deals' /></head><body></body></html>";
	die("");
} /**/
?>

</head>

<body id='body'>

<?php
	include("topbar.php");
?>
<div class='contents-container'>
	<div class='contents'>
		<div id='home-contents' class='main-contents'>
			<div>
				<!--a href="http://www.delugus.com/deals/newdeal.php" title="DeShare">Novo Item</a><br />
				<a href="http://www.delugus.com/deals/mydeals.php" title="DeShare">Meus Itens</a><br />
				<a href="http://www.delugus.com/tasks" title="DeTasks">Tasks</a><br />
				<a href="http://www.delugus.com/deals" title="DeShare">DeShare</a><br /-->
				<div id="home-title">Delugus</div>
				<!-- Não achei um meio mais facil para tirar essa table,
				não quero usar position absolute, relative ou fixed, depois te explico o porque -->
				<div class="home-descr-container">
					<div id="home-desc1" class="home-descr">
						<div class="home-action">
							<?php echo get_text(array('home', 'deals', 'advertise'));?><text style="font-style:italic; font-weight: bold; color: #0000ff;">!</text>
						</div>
						<div>
							<?php echo get_text(array('home', 'deals', 'description', 'advertise'));?>
						</div>
					</div><div id="home-desc2" class="home-descr">
						<div class="home-action">
							<?php echo get_text(array('home', 'deals', 'share'));?><text style="font-style:italic; font-weight: bold; color: #ff0000;">!</text>
						</div>
						<div>
							<?php echo get_text(array('home', 'deals', 'description', 'share'));?>
						</div>
					</div><div id="home-desc3" class="home-descr">
						<div class="home-action">
							<?php echo get_text(array('home', 'deals', 'win'));?><text style="font-style:italic; font-weight: bold; color: #00a000;">!</text>
						</div>
						<div>
							<?php echo get_text(array('home', 'deals', 'description', 'win'));?>
						</div>
					</div>
				</div>
				<!-- style="font-size: 24px;">Anuncie<text style="color: #0000ff;">!</text> Compartilhe<text style="color: #ff0000;">!</text> Ganhe<text style="color: #00a000;">!</text></text-->
			</div>
			<div id="home-right">
				
				<!--/div-->
			</div>
			<!--div class="fb-like-box" data-href="http://www.facebook.com/delugus.group" data-width="894" data-height="320" data-show-faces="true" data-border-color="#666666" data-stream="false" data-header="true"></div-->
		</div>
		<div id='pagefooter'>
			<?php include("pagefooter.php"); ?>
		</div>
	</div>
</div>

</body>
</html>
