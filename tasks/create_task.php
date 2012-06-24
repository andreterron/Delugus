<?php
	/* codigo que encontra o app baseado no lugar do arquivo, no entanto sera trabalhoso alterar */
	$app = explode('/', str_replace('.php', '', $_SERVER['PHP_SELF']));
	if (!$app[0]) array_shift($app);
	
	// sobe para a raiz do site
	$l = count($app);
	for ($i = 1; $i < $l; $i++) chdir("..");
	
	/* codigo geral para todas as paginas */
	include_once("functions.php");
	$loguser = userinfo(-1);

	$xml_top = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	$xml_top .= "<detask>";
	/* XML STRUCTURE:
	<detask>
		<result>
			1 = sucesso
			2 = erro
		</result>
		<taskid>
			id global da task que foi alterada
		</taskid>
		<msg>
			mensagem de erro
		</msg>
	</detask>
	*/
	$xml_bot = "</detask>";
	
	/* CODIGOS ESPECIFICOS */
	dodebug("START!");
	if (isset($_GET['action']))
	{
		dodebug("ACTION = " . $_GET['action']);
		if ($_GET['action'] == 'create' && isset($_GET['name']) && isset($_GET['folder']))
		{
			dodebug("NAME = " . $_GET['name']);
			dodebug("FOLDER = " . $_GET['folder']);
			$attr = array();
			$attr['task'] = array();
			$fid = create_file($_GET['name'], $loguser['id'], $_GET['folder'], $attr);
			echo $xml_top;
			if ($fid != 0) {
				dodebug("ID FOUND = " . $fid);
				echo "<action>create</action>";
				echo "<result>1</result>";
				echo "<fileid>$fid</fileid>";
				//echo "<taskid>$t_id</taskid>";
			} else {
				echo "<action>create</action>";
				echo "<result>2</result>";
				echo "<msg class='error_msg'>Erro na criacao do arquivo</msg>";
			}
			echo $xml_bot;
			die();
			
			/*$query = "SELECT * FROM `tzdelugusdata`.`file` WHERE `id` = '" . $_GET['folder'] . "';";
			$result = mysql_query($query, $con);
			if (!($result && $row = mysql_fetch_array($result))) {
				// ERRO no DB ou folder nao existe
				echo "<action>create</action>";
				echo "<result>2</result>";
				echo "<msg class='error_msg'>Folder " . $_GET['folder'] . " nao existe</msg>";
				echo $xml_bot;
			} 
			
			
			$query = "INSERT INTO  `tzdelugusdata`.`task` (
			`id` ,
			`name` ,
			`privacy`
			)
			VALUES (NULL";
			// NAME
			$query .= ",'" . $_GET['name'] . "'";
			// PRIVACY
			$query .= ",'0'";
			// QUERY END
			$query .= ");";
			
			$result = mysql_query($query, $con);
			if ($result) {
				$t_id = mysql_insert_id($con);
			} else {
				// ERRO NO DB
				echo $xml_top;
				echo "<action>create</action>";
				echo "<result>2</result>";
				echo "<msg class='error_msg'>Erro na conexao com a database</msg>";
				echo $xml_bot;
				die();
			}
			
			$query = "INSERT INTO  `tzdelugusdata`.`file` (
					`id`, `name`, `kids`, `parents`, `type`, `privacy`, `owner`
					)
					VALUES (NULL, '" . $_GET['name'] . "', '', '[1]" . $_GET['folder'] . "', '[1]task:$t_id', '', '" . $loguser['id'] . "');";
					
			$result = mysql_query($query, $con);
			
			echo $xml_top;
			if ($result) {
				$user = $loguser = userinfo(-1);
				$f_id = mysql_insert_id($con);
				
				$str = push_str_array($row['kids'], $f_id);
				$query = "UPDATE `tzdelugusdata`.`task` SET `fileid` = '" . $f_id . "' WHERE `task`.`id` = '" . $t_id . "' LIMIT 1;";
				$result = mysql_query($query, $con);
				if ($result) {
					$query = "UPDATE `tzdelugusdata`.`file` SET `kids` = '" . $str . "' WHERE `file`.`id` = '" . $_GET['folder'] . "' LIMIT 1 ;";
					//$str = push_str_array($user['tasks'], $t_id);
					//$query = "UPDATE  `tzdelugusdata`.`users` SET `tasks` = '" . $str . "' WHERE `users`.`id` = " . $user['id'] . " LIMIT 1 ;";
					$result = mysql_query($query, $con);
				} else {
					// ERRO
					echo "<action>create</action>";
					echo "<result>2</result>";
					echo "<taskid>$t_id</taskid>";
					echo "<msg class='error_msg'>Erro na atualizacao da task</msg>";
					echo $xml_bot;
					die();
				}
				if ($result) {
					echo "<action>create</action>";
					echo "<result>1</result>";
					echo "<fileid>$f_id</fileid>";
					echo "<taskid>$t_id</taskid>";
				} else {
					// ERRO
					echo "<action>create</action>";
					echo "<result>2</result>";
					echo "<fileid>$f_id</fileid>";
					echo "<taskid>$t_id</taskid>";
					echo "<msg class='error_msg'>Erro na atualizacao da pasta! query = $query</msg>";
				}
			} else {
				// ERRO
				echo "<action>create</action>";
				echo "<result>2</result>";
				echo "<taskid>$t_id</taskid>";
				echo "<msg class='error_msg'>Erro na criacao do arquivo</msg>";
			}
			echo $xml_bot;
			
			dbclose();
			
			//echo "<div class='warn_msg'>Queremos criar a task " . $_GET['name'] . "</div>";
			*/
		}
		else if ($_GET['action'] == 'complete' && isset($_GET['fileid']))
		{
			$c = (isset($_GET['value']) ? $_GET['value'] : '1');
			$fid = $_GET['fileid'];
			
			dbconnect();
			
			$f = read_file_info($fid);
			
			if ($f == null || !isset($f['task_id'])) {
				echo $xml_top;
				echo "<action>create</action>";
				echo "<result>2</result>";
				if ($f == null) {
					echo "<msg class='error_msg'>File $fid nao existe</msg>";
				} else {
					echo "<msg class='error_msg'>File $fid nao é uma task - ";
					echo $f['id'] . "</msg>";
				}
				echo $xml_bot;
				return;
			}
			
			$t_id = $f['task_id'];
			$query = "UPDATE `tzdelugusdata`.`task` SET  `complete` = '$c' WHERE `task`.`id` = $t_id LIMIT 1 ;";
			//"DELETE FROM `tzdelugusdata`.`task` WHERE `task`.`id` = $t_id LIMIT 1";
			
			$result = mysql_query($query, $con);
			
			echo $xml_top;
			if ($result) {
				/*$user = $loguser = userinfo(-1);
				$utasks = read_str_array($user['tasks']);
				if (in_array($t_id, $utasks)) {
					array_splice($utasks, array_search($t_id, $utasks), 1);
					$str = write_str_array($utasks, $user['tasks']);
					$query = "UPDATE  `tzdelugusdata`.`users` SET  `tasks` = '" . $str . "' WHERE `users`.`id` = " . $user['id'] . " LIMIT 1 ;";
					$result = mysql_query($query, $con);
				}
				if ($result) {
					echo "<result>1</result>";
					echo "<taskid>$t_id</taskid>";
				} else {
					// ERRO DB
					echo "<result>2</result>";
					echo "<taskid>$t_id</taskid>";
					echo "<msg class='error_msg'>Erro na atualizacao do usuario!</msg>";
				}*/
				echo "<action>complete</action>";
				echo "<value>$c</value>";
				echo "<result>1</result>";
				echo "<taskid>$t_id</taskid>";
			} else {
				// ERRO DB
				echo "<action>complete</action>";
				echo "<value>$c</value>";
				echo "<result>2</result>";
				echo "<msg class='error_msg'>Erro na conexao com a database</msg>";
			}
			echo $xml_bot;
			
			dbclose();
			/*echo $xml_top;
			echo "<result>2</result>";
			echo "<msg class='error_msg'>" . ("Ainda não é possível completar uma task") . "</msg>";
			echo $xml_bot;*/
		}
		else if ($_GET['action'] == 'delete' && isset($_GET['taskid']))
		{
			
		}
	}
	
?>