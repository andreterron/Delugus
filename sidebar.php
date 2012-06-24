<?php
if (!$loguser)
	$loguser = userinfo();

if (!$loguser) {
	//echo "Visitante<br/><br/><a href='deals/index.php'>Ultimos Itens</a><br />";
	echo "<p style='font-size: 24px;text-align: center;'>Cadastre-se:</p>
				<div id='sidebar-signup'>
					<form name='cadastro' method='post' action='register.php' >
						Nome:<br/>
						<input type='text' name='firstname' /><br/>
						Sobrenome:<br/>
						<input type='text' name='lastname' /><br/>
						E-mail:<br/>
						<input type='text' name='email' /><br/>
						Senha:<br/>
						<input type='password' name='password' /><br/>
						Confirme a senha:<br/>
						<input type='password' name='confirm' /><br/>
						Nascimento:<br/>
							<!-- Inicio Nascimento -->
							<select id='day_birth' name='day_birth'>";
							echo "<option value=-1 selected='selected'>" . get_text(array('date', 'day')) . "</option>";
							for ($i = 1; $i <= 31; $i++)
							{
								echo "<option value=$i>$i</option>";
							}
							echo "</select><select id='month_birth' name='month_birth'>";
							echo "<option value=-1 selected='selected'>" . get_text(array('date', 'month')) . "</option>";
							for ($i = 1; $i <= 12; $i++)
							{
								echo "<option value=$i>" . get_text(array('date', 'mon', 'long', $month_names[$i])) . "</option>";
							}
							echo "</select><select id='year_birth' name='year_birth'>"; 
							echo "<option value=-1 selected='selected'>" . get_text(array('date', 'year')) . "</option>";
							date_default_timezone_set('UTC');
							$t = getdate();
							for ($i = $t['year']; $i >= ($t['year'] - 150); $i--)
							{
								echo "<option value=$i>$i</option>";
							}
							echo "</select>
							<!-- Fim Nascimento --><br/>
						Genero:<br/>
						<select name='gender'>
							<option value=''></option>
							<option value='male'>Homem</option>
							<option value='female'>Mulher</option>
						</select>
						<br/>
						<input id='signup-button' type='submit' value='Cadastre-se'/>
					</form>
				</div>";
}
else {
	$user_array = array('profile', 'deals/userdeals', 'message', 'den/profile_picture');
	if (check_app_array($app, $user_array) && $user)
	{
		echo "
		<a href='profile.php?id=" . $user['id'] . "'>" . get_user_photo($user, 128) . "</a><br/>
		<a href='profile.php?id=" . $user['id'] . "' style='text-align: center; font-weight: bold;'>" . $user['fullname'] . "</a><br/><br/>";
		if ($user['id'] != $loguser['id']) {
			echo "	<a href='profile.php?id=" . $user['id'] . "'>Atualizações</a><br/>
					<a href='message.php?to=" . $user['id'] . "'>Debates</a><br/>
					<a href='deals/userdeals.php?id=" . $user['id'] . "'>Itens</a><br/>";
		} else {
			echo "	<a href='profile.php?id=" . $user['id'] . "'>Atualizações</a><br/>
					<a href='deals/userdeals.php?id=" . $user['id'] . "'>Meus Itens</a><br/>
					<a href='den/profile_picture.php'>Alterar Foto</a><br/>";
		}
		
	} else {
		echo "  <a href='profile.php?id=" . $loguser['id'] . "'>" . get_user_photo($loguser, 64) . "</a><br/>
		<a href='profile.php?id=" . $loguser['id'] . "' style='text-align: center; font-weight: bold;'>" . $loguser['fullname'] . "</a><br/><br/>
				<a href='deals/index.php'>Ultimos Itens</a><br />
				<a href='deals/userdeals.php'>Meus Itens</a><br />
				<a href='deals/newdeal.php'>Novo Item</a><br />";
	}
}
?>
