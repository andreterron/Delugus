<?php
	include_once("functions.php");
?>

<!DOCTYPE html>

<html>
<head>

<title>Delugus - Search</title>
<base href="http://www.delugus.com/"/>
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="http://www.delugus.com/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

</head>

<body id='body'>

<?php
	/*if (!isset($_GET['search']))
		die("</body></html>");*/

	include("topbar.php");
?>
<div class='contents-container'>
	<div class='contents'>
		<div class='main-contents'>
			<div class="maincontainer">
				<div class='main-col'>
					<div class="main-header">
						Search Results:
					</div>
					<div class="main-info">

	<?php
		
		/* Procurar em: ('what'=)
			0 - Tudo
			1 - Usuarios
			2 - Comunidades*
			3 - Itens*
			
			*checar permissoes
		*/
		
		if (!isset($_GET['what']) || $_GET['what'] == null) {
			$what = 0;
		} else {
			$what = $_GET['what'];
		}
		
		if ($what == 3 && !check_app_permissions('deals'))
			$what = 0;
		
		if (!isset($_GET['search']) || strlen($_GET['search']) < 2) {
			$what = -1;
			$q = '';
		} else {
			$q = explode(' ', $_GET['search']);
			for ($i = count($q); $i >= 0; $i--)
			{
				if (strlen($q[$i]) < 2)
				{
					// TO DO Delete Argument
				}
			}
		}
		
		if (isset($_GET['page'])) {
			$p = $_GET['page'] - 1;
		} else {
			$p = 0;
		}
		
		if ($what != -1) {
			$found = 0;
			$con = dbconnect();
			
			/* USER SEARCH */
			if (($what == 0 || $what == 1) && $loguser)
			{
				$shown_sep = false;
				$query = "SELECT * from `tzdelugusdata`.`users` WHERE ";
				for ($i = count($q); $i >= 0; $i--) {
					$query .= "(`firstname` LIKE '%" . $q[$i] . "%' OR `lastname` LIKE '%" . $q[$i] . "%')";
					if ($i > 0) {
						$query .= " AND ";
					}
				}
				if ($what == 1)
					$query .= " ORDER BY `firstname` LIMIT " . ($q * 20) . " , 20;";
				else
					$query .= " ORDER BY `firstname` LIMIT 0 , 10;";
				//$query = "SELECT * from `tzdelugusdata`.`users` WHERE `middlename` = '" . $_POST["login"] . "';";
				//$query = "SELECT * from `tzdelugusdata`.`users` WHERE `lastname` = '" . $_POST["login"] . "';"
				
				$result = mysql_query($query, $con);
				if ($result)
				{
					while ($row = mysql_fetch_array($result))
					{
						if (!$shown_sep) {
							echo "<div class='userpost_sep'>Usuários</div>";
							$shown_sep = true;
						}
						//echo draw_deal($row, 'list');
						//echo "<a href='deals/deal.php?id=" . $row['id'] . "'>" . $row['name'] . "</a><br/>";
						$found++;
						echo "<div class='userpost'><a href='profile.php?id=" . $row['id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</a></div>";
					}
					if ($shown_sep)
					{
						//echo "<div class='msg_holder'><div class='warn_msg'>Não foram encontrados mais resultados</div></div>";
					}
				}
			}
			
			/* ITEMS SEARCH */
			if ($what == 0 || $what == 3)
			{
				$shown_sep = false;
				$query = "SELECT * from `tzdelugusdata`.`deals` WHERE ";
				for ($i = count($q); $i >=0; $i--) {
					$query .= "(`name` LIKE '%" . $q[$i] . "%' OR `tags` LIKE '%" . $q[$i] . "%')";
					if ($i > 0) {
						$query .= " AND ";
					}
				}
				
				if ($what == 1)
					$query .= " ORDER BY `id` DESC LIMIT " . ($q * 20) . " , 20;";
				else
					$query .= " ORDER BY `id` DESC LIMIT 0 , 10;";
				//$query .= " ORDER BY `id` DESC LIMIT 0 , 20;";
				//$query = "SELECT * from `tzdelugusdata`.`deals` WHERE `name` LIKE '%$q%' OR `tags` LIKE '%$q%' ORDER BY `id` DESC LIMIT 0 , 20;";
				$result = mysql_query($query, $con);
				if ($result)
				{
					while ($row = mysql_fetch_array($result))
					{
						if (!$shown_sep) {
							echo "<div class='userpost_sep'>Itens</div>";
							$shown_sep = true;
						}
						$found++;
						echo draw_deal($row, 'list');
						//echo "<a href='deals/deal.php?id=" . $row['id'] . "'>" . $row['name'] . "</a><br/>";
					}
					if ($shown_sep)
					{
						//echo "<div class='msg_holder'><div class='warn_msg'>Não foram encontrados mais resultados</div></div>";
					}
				}
			}
			mysql_close($con);
		}
		
		if ($what == -1 || $found == 0)
		{
			echo "<div class='msg_holder'><div class='error_msg'>Sua pesquisa não retornou resultados, por favor tente novamente</div></div>";
		}
	?>
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