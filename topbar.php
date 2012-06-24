<?php
	/* note que nao eh usado include("functions.php") pois topbar.php sempre sera incluso de outra pagina
	   e essa outra pagina que deve dar o include("functions.php")*/
	include_once("functions.php");
	// obtem os dados do usuario logado
	$loguser = userinfo();
?>

<?php

/*if ($user_browser->javascript)
{ Resolver erro em /functions.php antes*/
/* CODIGO DO FACEBOOK!! */
echo "
<div id='fb-root'></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
";
//}
?>

<div class="topbar" id="topbar">
		<a href="<?php echo $base_url; if ($loguser) echo "/deals"; ?>" title="Home">
			<div id="topbar_logo" class='linkdiv'><img src='<?php echo $base_url . '/'; ?>images/topbar_logo.png' style="width: 91px"></img></div>
		</a>
			<?php
				if ($loguser)
				{
					echo "
					<form action='logout.php' method='post'>
						<div id='topbar_userinfo'>
							<a href='$base_url/profile.php?id=" . $loguser['id'] . "'>
								<div class='linkdiv' id='topbar_name'>" . $loguser['firstname'] . "<div id='topbar_userphoto'>" . get_user_photo(null, 24) . "</div></div></a><a href='$base_url/logout.php'><div class='linkdiv' id='topbar_logout'>Log Out</div>
							</a>
						</div>
					</form>";
				}
				else
				{
					echo "
					<form action='$base_url/login.php' method='post'>
						<input type='hidden' name='redir_url' value='$base_url$_SERVER[PHP_SELF]'/>
						<div id='topbar_userinfo'>
						<table id='topbar_logintable' style='right: 0px' cellpadding='0' cellspacing='0' border='0'>
							<tr>
								<td>
									<input class='text_input' id='topbar_email' type='text' name='login' placeholder='E-mail' />
								</td>
								<td>
									<input class='text_input' id='topbar_password' type='password' name='password' placeholder='Senha' />
								</td>
								<td>
									<input id='topbar_login' class='linkdiv' type='submit' value='Login'/>
								</td>
							</tr>
						</table>
						</div>
					</form>
					";
				}
			?>
</div>
<div class="topbar"></div>

<?php

if (!$hide_sidebar) {

	echo "
	<div class='sidebar-cont'>
		<div class='topbar'></div>
		<div class='sidebar' style=\"border-bottom: solid 1px #666666\">
			<form action='search.php' method='get'>
				<input id='topbar_search' type='text' class='text_input' name='search' placeholder='Procurar' />
			</form>
		</div>
		<div class='sidebar'>";
			 include_once("sidebar.php");
	echo "
		</div>
	</div>";
}
?>
