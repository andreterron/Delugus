<?php
	/* codigos especificos da pagina/app */
	
	$page_title = 'EventPhotos'; /* Default: "EventPhotos" */
	$hide_sidebar = true; /* Default: null / false */
	$motherfolder = '87';
	
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
	//include_once("headers.php");
	
	/* Coloque itens extras do header despois de include("headers.php") e antes de include("bodyTop.php"); */
	
	//echo "<script type='text/javascript' src='tasks/javascript.js'></script>\n";
	
	/*$user_tasks = read_str_array($loguser['tasks']);
	$l = count($user_tasks);*/
?>
	<!DOCTYPE html>

<html>
<head>

<title><?php echo ($page_title ? $page_title : "EventPhotos"); ?></title>
<base href="<?php echo $base_url; ?>"/>
<meta name="title" content="Home" />
<meta name="description" content="Delugus - Anuncie! Compartilhe! Ganhe!" />
<link rel="stylesheet" href="style.css" type = "text/css" />
<link rel="shortcut icon" href="<?php echo $base_url;?>/favicon.ico" />
<script type="text/javascript" src="javascript.js"></script>

<?php
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
</head>
<body>
</body>
</html>