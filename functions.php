<?php

// ARQUIVO QUE CONTEM FUNCOES BASICAS QUE PODEM SER USADAS EM TODO O SITE

$page_title = null; /* Default: null / "Delugus" */
$hide_sidebar = false; /* Default: null / false */

$str_array_maxchar = 255;
$con_number = 0;
$con = null;
$lang = "pt-br";
$xml_lang = null; //simplexml_load_file("language/$lang.xml");
$base_url = "http://" . $_SERVER['HTTP_HOST'];
$debug = false;
/*$user_browser = get_browser();

Linha acima gera um erro:

"Warning: get_browser() [function.get-browser]:
browscap ini directive not set
in /home/content/60/8718060/html/functions.php on line 15"

devemos informar o endereco do browscap.ini no php.ini
no entanto eu nao sei o que deve ter nesse arquivo
Procurar sobre browscap.ini para resolver*/

$month_names = array(null, 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');

class MetaFile {
	private static $fileList = array('0' => null);
	public static getFile($id = '0') {
		$id = (string) $id;
		if(!isset($fileList[$id])) {
			$fileList[$id] = new OODBFile($id);
		}
		return $fileList[$id];
	}
}

class OODBFile {
	/**
	 * OODBFile
	 *
	 * This class saves the information related to a specific file
	 * in our database
	 *
	 * @var int $_id stores the database id of the file
	 * @var array(OODBFile) $_kids stores the kid files
	 * @var int $_loaded indicates which level is loaded:
	 * 		0 - only ID
	 * 		1 - file properties
	 *		2 - all properties
	 * @var boolean $_saved false if needs to be saved to DB
	 *
	 */
	private $_id;
	private $_name;
	private $_identifier;
	private $_kids;
	private $_parents;
	private $_type;
	private $_attr;
	private $_privacy;
	private $_owner;
	private $_creation;
	private $_loaded;
	private $_saved;
	
	
    public function __construct($id = 0) {
		$this->_id = $id; // string
		$this->_name = null; // string
		$this->_identifier = null; // string
		$this->_kids = null; // array<OODBFile>
		$this->_parents = null; // array<OODBFile>
		$this->_type = null; // array<string>
		$this->_attr = null; // obj array
		$this->_privacy = null; // string
		$this->_owner = null; // string
		$this->_creation = null; // date string
		$this->_loaded = 0; // int
		$this->_saved = true; // boolean;
	}
	
	public function getId() {
		return $this->_id;
	}
	
	public function getKids() {
		if ($this->_loaded == 0) {
			$this->load();
		}
		return $this->_kids;
	}
	
	public function load() {
		// TODO LOAD FROM DB
		$con = dbconnect();
		$query = "SELECT * FROM `tzdelugusdata`.`file` WHERE `file`.`id` = $fid;";
		$result = mysql_query($query, $con);
		if ($result && $finfo = mysql_fetch_array($result)) {
			$this->_name = $finfo['name'];
			$this->_identifier = $finfo['identifier'];
			$this->_kids = read_str_array($finfo['kids']); // TODO GET OODBFile Array
			$this->_parents = read_str_array($finfo['parents']); // TODO GET OODBFile Array
			$this->_type = read_str_array($finfo['type']);; // array<string>
			$this->_privacy = $finfo['privacy']; // string
			$this->_owner = $finfo['owner']; // string
			$this->_creation = $finfo['creation']; // date string
			// TODO get types parameters
			$tps = read_str_array($finfo['type']);
			if ($finfo['creation'] == "0000-00-00 00:00:00") {
				$this->_attr = array(); // obj array
				foreach ($tps as $type => $tid) {
					$query = "SELECT * FROM `tzdelugusdata`.`$type` WHERE `$type`.`id` = $tid;";
					$result = mysql_query($query, $con);
					$this->_attr[$type] = array();
					if ($result && $tp = mysql_fetch_array($result)) {
						foreach ($tp as $k -> $v) {
							$this->_attr[$type][$k] = $v;
						}
					} else {
						// ERRO DB TYPE ID Não Encontrado!
					}
				}
			} else {
				$this->_attr = json_decode($finfo['attr']);
			}
		} else {
			// DB ERROR OR FILE ID NOT FOUND
			$finfo = null;
		}
		dbclose();
		// END OF LOAD
		$this->_loaded = 1;
		$this->_saved = true;
	}
	
	public function save() {
		// TODO SAVE TO DB
		$this->_saved = true;
	}
}

function dbconnect()
{
	global $con;
	global $con_number;
	if ($con != null)
	{
		$con_number += 1;
		return $con;
	}
	$hostname='tzdelugusdata.db.8718060.hostedresource.com';
	$username='tzdelugusdata';
	$password='D3W1NN3RSq1m8';
	$dbname='tzdelugusdata';

	$con = mysql_connect($hostname, $username, $password) OR DIE ('Unable to connect to database! Please try again later.');
	$con_number += 1;
	mysql_select_db($dbname);
	return $con;
}

function dbclose()
{
	global $con;
	global $con_number;
	if ($con_number == 1)
	{
		mysql_close($con);
		$con = null;
		$con_number--;
	}
	else if ($con_number > 1)
	{
		$con_number--;
	}
	else
	{
		die("Foram fechadas mais conexoes que abertas!");
	}
}

function write_str_array($ary = array(), $txt = null)
{
	$size = count($ary);
	$str = "[$size";
	
	if ($txt)
	{
		$i = stripos($txt, ']');
		if ($i)
		{
			$i++;
			/* substr(string,start,length)*/
			$attr = explode(',', substr($txt, 1, $i - 2));
			$atrlen = count($attr);
			for ($j = 1; $j < $atrlen; $j++) {
				$str .= "," . $attr[$j];
			}
		}
	}
	$str .= ']';
	if ($size > 0) {
		$str .= $ary[0];
	}
	for ($i = 1; $i < $size; $i++) {
		$str .= "," . $ary[$i];
	}
	return $str;
}

function push_str_array($txt = null, $value = null)
{
	global $str_array_maxchar;
	// Coloca um novo elemento no final de uma string-array
	
	// retorna se o texto for vazio ou o valor for nulo
	// (o valor deve ser validado antes de ser enviado para essa funcao)
	if (!$txt && $value === null) {
		return '[0]';
	} else if (!$txt) {
		return '[1]' . $value;
	}
	if ($value === null)
		return $txt;
	$i = stripos($txt, ']');
	if ($i) {
		$i++;
		$attr = explode(',', substr($txt, 1, $i - 2));
		if ($attr[0] == 0)
		{
			return '[1]' . $value;
		}
		$attr[0]++;
		if (count($attr) == 1) {
			$temp = '[' . $attr[0] . ']' . rtrim(substr($txt, $i)) . ',' . $value;
			if (strlen($temp) <= $str_array_maxchar)
			{
				return $temp;
			} else {
				// TO DO criar uma lista ligada
				return 'LISTA GRANDE! str = ' . $temp . '; size = ' . strlen($temp) . '; max=' . $str_array_maxchar;
			}
		} else {
			// TO DO ler lista ligada e altera-la
			return 'erro! mais de um atributo! str = ' . substr($txt, 1, i - 2) . '; attr = ' . count($attr);
		}
	}
	else
	{
		return '[1]' . $value;
	}
}

function read_str_array($txt = null)
{
	/* string array:
		"[tamanho,(endereco inicial lista ligada),(end. final)]arg1,arg2,arg3,arg4,ar"
		na lista ligada: "g5,arg6,..."
		
		Note que um argumento pode comecar em uma string e terminar em outra,
		isso eh possivel pois todas as strings sao unidas, e transformadas em array em seguida
		
		(attr) significa que o atributo eh opcional, nao se deve usar os parenteses
		
		esse algoritmo ainda pode ser discutido, assim como a estrutura em questao
	*/
	/* Funcao que le uma string que funciona como array, e retorna a array resultante */
	if (!$txt) {
		// retorna uma array vazia caso o texto seja nulo ou vazio
		return array();
	}
	
	
	$i = stripos($txt, ']');
	if ($i)
	{
		$i++;
		/* substr(string,start,length)*/
		$attr = explode(',', substr($txt, 1, $i - 2));
		// pega os argumentos
		// TO DO possibilitar extender para uma lista ligada
		$args = explode(',', substr($txt, $i));
		$len = count($args);
		if (stripos($txt, ':')) {
			$nargs = array();
			for ($i = 0; $i < $len; $i++) {
				$item = explode(':', $args[$i]);
				$nargs[$item[0]] = $item[1];
			}
			$args = $nargs;
		}
		return $args;
	}
	else
	{
		// retorna uma array vazia caso a string nao possua os caracteres de atributo
		return array();
	}
}

function check_app_array($app = array(), $target = array())
{
	$i = 0;
	$str = null;
	while (isset($app[$i]))
	{
		if ($str) {
			$str .= '/' . $app[$i];
		} else {
			$str = $app[$i];
		}
		
		if (in_array($str, $target)) {
			return true;
		}
		$i++;
	}
	return false;
}

function is_secret($app = array())
{
	/* funcao para definir se o app eh secreto ou nao */
	$secret_apps = array('debug');
	
	return check_app_array($app, $secret_apps);
}

function check_app_permissions($app = array())
{
	global $loguser;
	/* communities profiles:
	
		  1 - pessoas podem pedir para entrar
		  2 - precisa de uma confirmacao por email
		  4 - precisa da autorizacao de um admin do grupo
		  8 - o grupo possui admins
		 16 - grupo secreto
		 32 - dependente to grupo superior
		
		0..63
		
	   Apps:
		
		  1 - o grupo esta bloqueado para o deShare
		  2 - o grupo esta bloqueado para o deTasks
		 
		0..3
	*/
	
	// Checa se o usuario pode usar o app
	$msg = null;
	
	/* lista de apps que requerem login
	 (pode ser trocada para uma lista externa posteriormente) */
	$require_login = array('deals', 'debug');
	
	/* checa se o app em questao requer login do usuario */
	if (check_app_array($app,$require_login)) {
		/* checa se o usuario esta logado, retorna o erro caso nao estiver */
		if (!$loguser) {
			return "Você deve estar logado para usar esse app";
		}
	}
	
	return $msg;
}

function choose_user_attr($type = 0)
{
	/* type: (cada bit liga ou desliga informacoes a serem coletadas */
	/*	0 - 0000 - id, username, email, first & last name, birthday, photo, gender (padrao)*/
	/*	1 - 0001 - [0] + friends, groups*/
	/*	2 - 0010 - [0] + deals*/
	/*	4 - 0100 - [0] + tasks*/
	
	$row_names = array('id', 'username', 'email', 'firstname', 'lastname', 'birthday', 'photo', 'gender', 'home');
	if ($type & 1)
		array_push($row_names, 'friends' , 'groups');
	if ($type & 2)
		array_push($row_names, 'deals');
	if ($type & 4)
		array_push($row_names, 'tasks');
	
	return $row_names;
}

function app_userinfo($app)
{
	$app_attr = array();
	$app_attr['deals'] = 3;
	$app_attr['tasks'] = 5;
}

function userinfo($type = 0, $id = null)
{
	
	global $con;
	global $base_url;
	// checa se o usuario esta logado e retorna uma array com as informacoes do usuario
	if (isset($_COOKIE["user"]) || $id != null)
	{
		$user = null;
		// define que atributos serão salvos na variavel, e encontra o tamanho da array
		$row_names = choose_user_attr($type);
		$size = count($row_names, 0);
		
		// conecta com a database
		$con = dbconnect();
		
		// pega o userid
		$userid = $_COOKIE['user'];
		
		if ($id == null) {
			$id = $userid;
		}
		
		// seleciona os dados definidos acima do usuario
		$query = "SELECT ";
		for ($i = 0; $i < $size; $i++)
		{
			if ($i > 0) {
				$query .= ", ";
			}
			$query .= "`" . $row_names[$i] . "`";
		}
		$query .= " from `tzdelugusdata`.`users` WHERE `id` = " . $id . ";";
		$result = mysql_query($query, $con);
		if($result && $row = mysql_fetch_array($result)) {
			//$user_command = "new setuser(";
			$user = $row;
			$user['fullname'] = "" . $user['firstname'];
			if ($user['lastname']) {
				$user['fullname'] .= ' ' . $user['lastname'];
			}
			if ($user['home'] == 0 || $user['home'] == '0') {
				$query = "INSERT INTO  `tzdelugusdata`.`file` (
					`id`, `name`, `kids`, `parents`, `type`, `privacy`, `owner`
					)
					VALUES (NULL, 'Home', '', '', '', '', '" . $user['id'] . "');";
					
				$result = mysql_query($query, $con);
				if ($result) {
					$user['home'] = mysql_insert_id($con);
					$query = "UPDATE  `tzdelugusdata`.`users` SET `home` = '" . $user['home'] . "' WHERE `users`.`id` = " . $user['id'] . " LIMIT 1 ;";
					$result = mysql_query($query, $con);
					if (!$result) {
						$user['home'] = '';
						// ERRO NO DB
					}
				} else {
					// ERRO NO DB
				}
			}
			if ($user['photo']) {
				$query = "SELECT `url`, `width`, `height` from `tzdelugusdata`.`photo` WHERE `id` = '" . $user['photo'] . "';";
				$result = mysql_query($query, $con);
				if ($result && $f_info = mysql_fetch_array($result)) {
					$user['photo_url'] = $f_info['url'];
					$user['photo_w'] = $f_info['width'];
					$user['photo_h'] = $f_info['height'];
				}
			} else {
				$user['photo_url'] = NULL;
				$user['photo_w'] = 1;
				$user['photo_h'] = 1;
			}
			/*for ($i = 0; $i < $size; $i++)
			{
				if ($i > 0) {
					$user_command .= ", ";
				}
				$user_command .= $row[$row_names[$i]];
			}*/
		}
		else
		{
			// caso a query nao tenha encontrado o userid, define que nao foi logado
			$user = null;
		}
		// os atributos da variavel $user devem ser acessados como uma array (ex: $user['email'])
		
		// fecha a conexao
		dbclose();
	}
	else
	{
		// caso o cookie nao exista e o user seja o logado, define o user como null e logged como false
		$user = null;
	}
	return $user;
}

function number_code($i, $fill = 0) {
	$n = '';
	while ($i > 0 || $fill > 0) {
		$j = $i % 52;
		$l = ($j < 26 ? 'a' : 'A');
		$n = chr(ord($l[0]) + ($j % 26)) . $n;
		$i = floor($i / 52);
		$fill--;
	}
	return $n;
}

function number_decode($n) {
	// TODO this function
}

function read_payperiod($pp = 0, $type = '')
{
	if ($type == 'sell')
		return '';
	if ($pp == 0) {
		if ($type == 'lend')
			return 'por vez';
	}
	/* 3600" selected>por hora</option>
	  <option value="86400">por dia</option>
	  <option value="604800">por semana</option>
	  <option value="2592000">por mes</option>
	  <option value="15768000*/
	
	if ($pp >= 31536000)  // segundos em um ano
		return ("por " . round($pp / 31536000, 2) . (round($pp / 31536000, 2) == 1 ? " ano" : " anos"));
	
	if ($pp >= 15768000) // segundos em um semestre
		return ("por " . round($pp / 15768000, 2) . (round($pp / 15768000, 2) == 1 ? " semestre" : " semestres"));
	
	if ($pp >= 2592000) // segundos em um mes
		return ("por " . round($pp / 2592000, 2) . (round($pp / 2592000, 2) == 1 ? " mês" : " meses"));
	
	if ($pp >= 604800) // segundos em uma semana
		return ("por " . round($pp / 604800, 2) . (round($pp / 604800, 2) == 1 ? " semana" : " semanas"));
	
	if ($pp >= 86400) // segundos em uma dia
		return ("por " . round($pp / 86400, 2) . (round($pp / 86400, 2) == 1 ? " dia" : " dias"));
		
	if ($pp >= 3600) // segundos em uma hora
		return ("por " . round($pp / 86400, 2) . (round($pp / 86400, 2) == 1 ? " hora" : " horas"));
	
	if ($pp == 0)
		return ("por vez");
	
}

function format_date($date, $type = null)
{
	$i = strtotime($date);
	
	if ($type == "post")
	{
		$t = time() - $i;
		if ($t >= 0) {
			if ($t < 120) {
				return "à menos de um minuto";
			} else if ($t < 3600) {
				return "à " . floor($t / 60) . " minutos atrás";
			} else if ($t < 24 * 3600) {
				return "à " . floor($t / 3600) . " horas atrás";
			} else if ($t < 7 * 24 * 3600) {
				return "à " . floor($t / (24 * 3600)) . " dias atrás às " . date("H:i", $i);
			}
		}
		return "em " . date("d/m/Y", $i) . " às " . date("H:i", $i);
	}
	
	return date("d/M/Y H:i:s", $i);
}

function get_user_photo_url($user = null)
{
	global $loguser;
	global $base_url;
	if (!$user) {
		$user = $loguser;
	}
	
	if (!$user['photo_url'] || !file_exists($user['photo_url'])) {
		if ($user['gender'] == 'f') {
			return "$base_url/photo/no_f.jpg";
		} else {
			return "$base_url/photo/no_m.jpg";
		}
	} else {
		return "$base_url/" . $user['photo_url'];
	}
}

function get_user_photo($user = null, $s = 48)
{
	global $loguser;
	if (!$user) {
		$user = $loguser;
	}
	
	if ($user['photo_w'] <= $user['photo_h']) {
		$w = $s - 2;
		$h = $w / $user['photo_w'] * $user['photo_h'];
		$l = 0;
	} else {
		$h = $s - 2;
		$w = $h / $user['photo_h'] * $user['photo_w'];
		$l = ($w - $h) / 2;
	}
	
	$style_wh = "width: " . $s . "px; height: " . $s . "px; border-radius: " . ($s / 8) . "px;";
	$style_ph = "margin-left: -" . $l . "px;";
	return "<div style='position: relative'><div class='photo-container photo-bg' style=\"$style_wh\">
	<img src='" . get_user_photo_url($user) . "' alt='$user[fullname]' width='$w' height='$h' style=\"$style_ph\"></img>
	<div class='photo-container photo-shadow' style=\"$style_wh\"></div></div></div>";
}

function draw_deal($deal = null, $type = 'post')
{
	global $con;
	global $loguser;
	dbconnect();
	
	$txt = "";
	
	switch ($type)
	{
		case 'post':
			$user = userinfo(3, $deal['owner']);
			/* PHOTO = <div class='userpost-photo'><div style='width:48px; height:48px'></div></div> */
			if ($loguser) {
				$txt .= "<div class='photopost'>
				<div class='userpost-photo'><a href='profile.php?id=" . $user['id'] . "'>" . get_user_photo($user) . "</a></div>";
				$txt .= "<div class='userpost-contents'><a href='profile.php?id=" . $user['id'] . "'>" . $user['fullname'] . "</a> anunciou um item:<br/>";
			} else {
				$txt .= "<div class='userpost'>";
				$txt .= "<div class='userpost-contents'>Um item foi anunciado:<br/>";
			}
			/*switch ($deal['type'])
			{
				case 'sell': $txt .= " está vendendo "; break;
				case 'lend': $txt .= " está alugando "; break;
			}
			$txt .= "um item:<br />";*/
			$txt .= "<div class='userpost-contents'><a href='deals/deal.php?id=" . $deal['id'] . "'>" . $deal['name'] . "</a><br/>";
			switch ($deal['type'])
			{
				case 'sell': $txt .= "Vendendo por "; break;
				case 'lend': $txt .= "Alugando por "; break;
			}
			$txt .= "R$" . $deal['price'] . " " . read_payperiod($deal['payperiod'], $deal['type']) . "</div><br/>";
			$txt .= "<text class='userpost-footer'>" . format_date($deal['date'], 'post') . "</text>";
			$txt .= "</div></div>";
			break;
		case 'list':
			$txt .= "<div class='userpost'><div class='userpost-photo'><div style='width:48px; height:48px'></div></div>";
			$txt .= "<a href='deals/deal.php?id=" . $deal['id'] . "'>" . $deal['name'] . "</a>";
			$txt .= "</div>";
			break;
	}
	
	dbclose();
	
	return $txt;
}

function get_text($path)
{
	/* $path: array containing the levels of the text */
	
	if (!$path) return;
	
	global $xml_lang;
	if (!$xml_lang) $xml_lang = simplexml_load_file("language/$lang.xml");;
	$txt = "";
	$code = "\$txt = htmlentities(\$xml_lang"; /* a funcao htmlentities transforma 'é' em '&eacute;' */
	foreach ($path as $p)
	{
		$code .= "->" . $p;
	}
	$code .= ', ENT_COMPAT, "UTF-8");';
	eval($code);
	return $txt;
}

function get_photo_name($id)
{
	/*abcdefghijklmnopqrstuvwxyz
	  12345678901234567890123456*/
	while ($id > 0)
	{
		$id = 0;
	}
}

function dodebug($txt) {
	global $debug;
	if ($debug) {
		echo $txt . "<br/>";
	}
}

function debug_array($ary) {
	global $debug;
	if ($debug) {
		print_r($ary);
		echo "<br/>";
	}
}

function find_file($folder_id = null, $path = array(), $create = false, $return_type = 'id') {
	/* A partir de uma pasta, procura pelo caminho ($path)
		$folder_id: pasta pai que comecara a procura
		$path: array de 'identifier's contendo o caminho que deve ser percorrido a partir da $folder_id
		$create: flag que diz se a pasta deve ser criada caso nao exista
			true: cria a pasta caso nao seja encontrada
			false: nao cria
		$return_type: indica o que sera retornado
			'id': retorna o id da pasta encontrada
			'file': retorna o get_file_info() da pasta
			'tree': retorna o get_file_tree() da pasta (a pasta e os seus filhos)
	*/
	$parent = read_file_info($folder_id);
	
	$next_id = $folder_id;
	$parent = read_file_info($next_id);
	
	while ($path) {
		$search = array_shift($path);
		if ($parent == null) {
			// ERRO
		}
		$kids = $parent['kids'];
		$next_id = 0;
		foreach ($kids as $k) {
			$kid = read_file_info($k);
			if ($kid == null) {
				// ERRO
			}
			if ($kid['identifier'] == $search) {
				$next_id = $k;
				$parent = $kid
				break;
			}
		}
		if (!$next_id && $create) {
			$next_id = create_file('', 0, $parent['id'], array(), $search);
		}
		if (!$next_id) {
			if ($return_type == 'id') {
				return 0;
			} else {
				return null;
			}
		}
	}
	
	return $parent;
	
}

function create_file($name = null, $owner = null, $folder = null, $attr = array(), $identifier = "NULL") {
	global $con;
	if ((!$name && $identifier == "NULL") || (!$owner && !$folder) {
		return 0;
	}
	// TODO turn $folder from int to obj_array
	//$folder_id = $folder['id'];
	dodebug("DEBUG: CREATING ITEM: name =  $name; owner = $owner; folder = $folder;");
	dbconnect();
	if ($owner) {
		//dodebug("OWNER DEFINED");
		// Caso uma pasta não tenha sido definida, escolhe a home do usuário
		$query = "SELECT `home` FROM `tzdelugusdata`.`users` WHERE `users`.`id` = $owner;";
		$result = mysql_query($query, $con);
		if ($result && $uinfo = mysql_fetch_array($result)) {
			$homefolder = $uinfo['home'];
			//dodebug("USER HOMEFOLDER: $homefolder");
			if (!$folder) {
				//dodebug("FOLDER NOT DEFINED");
				$folder = $uinfo['home'];
			}
		} else {
			//dodebug("USER NOT FOUND");
			if (!$folder) {
				//dodebug("FOLDER NOT FOUND EXIT!");
				dbclose();
				return 0;
			} else {
				$owner = null;
			}
		}
	}
	$fid = ($folder != null ? $folder : $homefolder);
	//dodebug("FID = $fid");
	// Caso o dono não tenha sido definido, escolhe o dono da pasta
	$query = "SELECT `id`, `kids`, `owner` FROM `tzdelugusdata`.`file` WHERE `file`.`id` = $fid;";
	$result = mysql_query($query, $con);
	if ($result && $finfo = mysql_fetch_array($result)) {
		//dodebug("PARENT FOLDER FOUND");
		if (!$owner) {
			//dodebug("USER NOT DEFINED");
			$owner = $finfo['owner'];
		}
	} else {
		//dodebug("PARENT FOLDER NOT FOUND");
		if (!$owner || $fid == $homefolder) {
			//dodebug("USER NOT DEFINED");
			dbclose();
			return 0;
		} else  {
			//dodebug("SEARCHING USER HOME FOLDER");
			$folder = $homefolder;
			$query = "SELECT `id`, `kids`, `owner` FROM `tzdelugusdata`.`file` WHERE `file`.`id` = $folder;";
			if (!($result && $finfo = mysql_fetch_array($result))) {
				//dodebug("USER HOME FOLDER NOT FOUND");
				dbclose();
				return 0;
			}
		}
	}
	//dodebug("PRE-PROCESSING DONE");
	$attrstr = json_encode($attr);
	$typestr = write_str_array(array_keys($attr));
	$creation = gmdate("Y-m-d H:i:s");
	$folder = $finfo['id'];
	$query = "INSERT INTO  `tzdelugusdata`.`file` (
			`id`, `name`, `identifier`, `kids`, `parents`, `type`, `attr`, `privacy`, `owner`, `creation`
			) VALUES (
			NULL, '$name', '$identifier', '', '[1]$folder', '$typestr', '$attrstr', '', '$owner', '$creation');";
	dodebug("DEBUG: QUERY: $query");
	$result = mysql_query($query, $con);
	if ($result) {
		$file_id = mysql_insert_id($con);
		//dodebug("INSERTION SUCCEDED! FID = $file_id");
		$str = push_str_array($finfo['kids'], $file_id);
		$query = "UPDATE `tzdelugusdata`.`file` SET `file`.`kids` = '$str' WHERE `file`.`id` = $folder LIMIT 1 ;";
		$result = mysql_query($query, $con);
		if (!$result) {
			dodebug("ERROR: KIDS UPDATE ERROR!");
			//ERRO PURAMENTE DB
		}
		dbclose();
		return $file_id;
	} else {
		dodebug("ERROR AT CREATION");
		// ERRO DB
		dbclose();
		return 0;
	}
	dodebug("WEIRD ERROR");
	dbclose();
	return 0;
}

function read_file_info($fid) {
	global $con;
	$files = array();
	dbconnect();
	$query = "SELECT * FROM `tzdelugusdata`.`file` WHERE `file`.`id` = $fid;";
	$result = mysql_query($query, $con);
	if ($result && $finfo = mysql_fetch_array($result)) {
		// TODO get types parameters
		$finfo['kids'] = read_str_array($finfo['kids']);
		$tps = read_str_array($finfo['type']);
		if ($finfo['creation'] == "0000-00-00 00:00:00") {
			foreach ($tps as $type => $tid) {
				$query = "SELECT * FROM `tzdelugusdata`.`$type` WHERE `$type`.`id` = $tid;";
				$result = mysql_query($query, $con);
				if ($result && $tp = mysql_fetch_array($result)) {
					foreach (array_keys($tp) as $k) {
						if (!array_key_exists($k, $finfo)) {
							$finfo[$k] = $tp[$k];
						}
						$finfo[$type.'_'.$k] = $tp[$k];
					}
				} else {
					// ERRO DB TYPE ID Não Encontrado!
				}
			}
		} else {
			$finfo['attr'] = json_decode($finfo['attr']);
		}
	} else {
		// DB ERROR OR FILE ID NOT FOUND
		$finfo = null;
	}
	dbclose();
	return $finfo;
}

function read_file_tree($file_id, $type_cmp_str = "", $attr_cmp_str = "", $read_tattr = "", $limit = array(), $get_loop = false, $loop_ary = array()) {
	global $con;
	$files = array();
	
	$file = read_file_info($file_id);
	if ($file['kids']) {
		array_push($loop_ary, $file_id);
		foreach(array_keys($file['kids']) as $k) {
			if (!in_array($file['kids'][$k], $loop_ary)) {
				$file['kids'][$k] = read_file_tree($file['kids'][$k], $type_cmp_str, $attr_cmp_str, $read_tattr, $limit, $get_loop, $loop_ary);
			} else {
				$k_id = $file['kids'][$k];
				$file['kids'][$k] = array();
				$file['kids'][$k]['id'] = $k_id;
			}
		}
		if (array_pop($loop_ary) != $file_id) {
			die("ERROR at read_file_tree! last loop_ary item isn't the file_id");
		}
	}
	return $file;
	/*$folder = $file;
	$type = $type_cmp_str;
	$read_type = ($read_tattr != "");
	
	dbconnect();
	$query = "SELECT * FROM `tzdelugusdata`.`file` WHERE `file`.`id` = $folder;";
	$result = mysql_query($query, $con);
	if ($result && $finfo = mysql_fetch_array($result)) {
		$ary = read_str_array($finfo['kids']);
		$arylen = count($ary);
		for ($i = 0; $i < $arylen; $i++) {
			$query = "SELECT * FROM `tzdelugusdata`.`file` WHERE `file`.`id` = " . $ary[$i] . ";";
			$result = mysql_query($query, $con);
			if ($result && $row = mysql_fetch_array($result)) {
				// TODO - implement $read_type to read attributes from the filetype
				if ($type != null) {
					$tps = read_str_array($row['type']);
					if (array_key_exists($type, $tps)) {
						if ($read_type) {
							$query = "SELECT * FROM `tzdelugusdata`.`$type` WHERE `$type`.`id` = " . $tps[$type] . ";";
							$result = mysql_query($query, $con);
							if ($result && $tp = mysql_fetch_array($result)) {
								foreach (array_keys($tp) as $k) {
									if (!array_key_exists($k, $row)) {
										$row[$k] = $tp[$k];
									}
									$row[$type.'_'.$k] = $tp[$k];
								}
							}
						}
						array_push($files, $row);
					}
				} else {
					array_push($files, $row);
				}
			}
		}
	}
	dbclose();
	return $files;*/
}

function read_folder_files($folder, $type = null, $read_type = false)
{
	global $con;
	$files = array();
	dbconnect();
	$query = "SELECT * FROM `tzdelugusdata`.`file` WHERE `file`.`id` = $folder;";
	$result = mysql_query($query, $con);
	if ($result && $finfo = mysql_fetch_array($result)) {
		$ary = read_str_array($finfo['kids']);
		$arylen = count($ary);
		for ($i = 0; $i < $arylen; $i++) {
			$query = "SELECT * FROM `tzdelugusdata`.`file` WHERE `file`.`id` = " . $ary[$i] . ";";
			$result = mysql_query($query, $con);
			if ($result && $row = mysql_fetch_array($result)) {
				// TODO - implement $read_type to read attributes from the filetype
				if ($type != null) {
					$tps = read_str_array($row['type']);
					if (array_key_exists($type, $tps)) {
						if ($read_type) {
							$query = "SELECT * FROM `tzdelugusdata`.`$type` WHERE `$type`.`id` = " . $tps[$type] . ";";
							$result = mysql_query($query, $con);
							if ($result && $tp = mysql_fetch_array($result)) {
								foreach (array_keys($tp) as $k) {
									if (!array_key_exists($k, $row)) {
										$row[$k] = $tp[$k];
									}
									$row[$type.'_'.$k] = $tp[$k];
								}
							}
						}
						array_push($files, $row);
					}
				} else {
					array_push($files, $row);
				}
			}
		}
	}
	dbclose();
	return $files;
}

$loguser = userinfo(-1);
?>
