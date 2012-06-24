<?php
	/* codigos especificos da pagina/app */
	
	$page_title = 'DeTasks'; /* Default: "Delugus" */
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
	
	echo "<script type='text/javascript' src='tasks/javascript.js'></script>\n";
	
	/*$user_tasks = read_str_array($loguser['tasks']);
	$l = count($user_tasks);*/
	$folder = (isset($_GET['folder']) ? $_GET['folder'] : $loguser['home']);
	
	echo "<script type='text/javascript'><!--\n";
	echo "var usertasks = new Array();\nvar globalid = new Array();";
	$files = read_folder_files($folder,'task',true);
	$home = read_file_tree($folder);
	$parent = array('null');
	$next = array($home);
	$j = 0;
	while (count($next) > 0) {
		$f = array_shift($next);
		$p = array_shift($parent);
		$t_id = $f['id'];
		$t_name = addslashes($f['name']);
		$t_comp = (double) (isset($f['task_complete']) ? $f['task_complete'] : 0.0);
		$arytxt = null;
		if ($f['kids']) {
			foreach($f['kids'] as $k) {
				if ($arytxt) $arytxt .= ',';
				else $arytxt = '[';
				$arytxt .= $k['id'];
			}
		}
		
		if (!$arytxt) $arytxt = '[';
		$arytxt .= ']';
		//$arytxt = '[' . implode(',', $f['kids']) . ']';
		echo "globalid[$j] = $t_id;\n";
		echo "usertasks[$j] = new build_task($j, $t_id, \"$t_name\", 1, $t_comp, $arytxt, $p);\n";
		foreach($f['kids'] as $k) {
			array_push($next, $k);
			array_push($parent, $j);
		}
		$j++;
	}
	/*$l = count($files);
	$j = 0;
	for ($i = 0; $i < $l; $i++) {
		$t_id = $files[$i]['task_id'];
		$t_name = $files[$i]['name'];
		if ($files[$i]['task_complete'] != 1 || (isset($_GET['complete']) || $_GET['complete'] == 'true')) {
			$t_comp = (double) $files[$i]['task_complete'];
			$arytxt = '[' . implode(',', read_str_array($files[$i]['kids'])) . ']';
			echo "globalid[$j] = $t_id;\n";
			echo "usertasks[$j] = new build_task($j, $t_id, '$t_name', 1, $t_comp, $arytxt);\n";
			$j++;
		}
	}
	$nav_txt = "";
	$fname = "";
	if ($l > 0) {
		dbconnect();
		foreach ($user_tasks as $t) {
			$query = "SELECT * FROM `tzdelugusdata`.`task` WHERE `id` = '$t';";
			$result = mysql_query($query, $con);
			if($result && $row = mysql_fetch_array($result)) {
				$t_id = $row['id'];
				$t_name = $row['name'];
				echo "usertasks.push(new build_task(usertasks.length, $t_id, '$t_name', 1));\n";
			}
		}
		dbclose();
	}*/
	echo "var folderid = " . $folder . ";";
	echo "var homefolderid = " . $loguser['home'] . ";";
	echo "\n--></script>";
?>

<style>

	.task-h-rest {
		border-top: solid 1px #5544ff;
		padding: 16px 16px 0px 32px;
	}
	
</style>


<?php include_once("bodyTop.php"); ?>

	<div style="padding: 32px;">
		<div id="folder-path" style="font-family: Century Gothic, sans-serif; font-size: 18px; padding-bottom: 2px;"><?php echo "<a onclick='open_folder($folder);' style='cursor: pointer;'>" . $loguser['firstname'] . "</a>"; ?></div>
		<div id="folder-name" style="font-family: Century Gothic, sans-serif; font-size: 36px; padding-bottom: 16px;"><?php echo $home['name']; ?></div>
		<div id="msg_container"></div>
		<!--form action="" onsubmit="create_task()"-->
			<input id="taskname" type="text" /><input type="submit" value="Nova Task" onclick="create_task()"/>
		<!--/form--><br/>
		
		<div id='tasklist' style="padding-top: 32px">
			<script type="text/javascript">
				var id = globalid.indexOf(folderid);
				if (id != -1) {
					document.write(draw_task(usertasks[id], 2));
				}
			</script>
		</div>
	</div>

<?php include_once("bodyBottom.php"); /*
<!-- SIDE BAR >
<div id='sidebar'>
	<div id='sidebar_tabspace'>
		<table id='sidebar_tabs' cellpadding="0" cellspacing="0" border="0">
			<tbody>
				<tr>
					<td>
						<div id="sidebar_list_tab" class="activetab" onclick="switch_sidetab(0);" >List</div>
					</td>
					<td>
						<div id="sidebar_view_tab" class="tab" onclick="switch_sidetab(1);" >View</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id='sidebar_contents' class='sidebar_contents'>
		<div id='sidebar_bg' class='sidebar_contents'></div>
		<div id='sidebar_list' class='sidebar_contents_active'>
			<div>
				<div style="font-size: 24px">Suas Tarefas</div>
				<table id='sidebar_list_table'>
					<script type="text/javascript">
					<!--

					//document.write(tasks.length + " tasks received<br />");
					for (i = 0; i < tasks.length; i++)
					{
						//document.write("<div class='tasklist_item' id='task" + i + "' onclick='view_task(" + i + ");'>" + tasks[i].name + "</div>");
						document.write("<tr id='sidebar_list_task" + i + "' onclick='view_task(" + i + ");'><td class='sqr'><div class='sidebar_list_color'></div></td><td>" + tasks[i].name + "</td></tr>");
					}

					</script>
					<tr id='sidebar_list_task_new' onclick='view_task(-1);'><td class='sqr'>+</td><td>Nova Task</td></tr>
				</table>
			</div>
		</div>
		<div id='sidebar_view' class='sidebar_contents'>
			<input id='sidebar_view_name' type='text' value="New Event" />
			<input id='sidebar_view_effort' type='text' />
			<div class='button'>
				<div style="text-align:center;" onclick="save_task()">Save</div>
			</div>
		</div>
	</div>
</div>
<div>
</div>
<div id='infobar'>
</div>

</body>
</html-->*/ ?>