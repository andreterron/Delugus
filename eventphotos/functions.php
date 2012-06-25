<?php 
	require_once("../functions.php");
	require_once("../facebook/src/facebook.php");
$facebook = new Facebook(array(  'appId'  => '446976568660000',  'secret' => '690d3324140569acf476af951a625f03',  'cookie' => true,));     
//$session = $facebook->getSession();     
$me = null;     

if($session){     
 try {     
  $uid = $facebook->getUser();     
  $me = $facebook->api('/me');     
 }     
 catch(FacebookApiException $e){     
  error_log($e);     
 }     
} 
	$main_events_id = '88';		//"Pasta de eventos"
	
	

	function import_fb_event($fb_event = null, $parent = null) {
		//create_file($events['name'], 0, $parent['id'], $attr, $id_event);
	}
	
	function get_user_events($fb_user_id) {
		global $facebook;
		$user_events = $facebook->api("$fb_user_id/events");
		return $user_events;
		/*if (isset($user_events['error'])) {
			return null;
		}
		$events = $user_events['data'];*/
	}
	
	function find_event($id_event){
		dodebug("dentro da funcao");
		global $facebook;
		global $main_events_id;
		dbconnect();
		
		$main_events = read_file_info($main_events_id);
		if (!$main_events)
			dodebug("OMG! MAIN EVENTS FILE DOES NOT EXIST!");
		print_r($main_events);
		$years = $main_events["kids"];
		
		//Converte data do facebook para date convencional
		dodebug("reading facebook event");
		try {
			$events = $facebook->api("/$id_event","get");
		} catch (Exception $e) {
			return null;
		}
		if (isset($events['error'])) {
			return null;
		}
		dodebug("done reading");
		$date = getdate(strtotime($events["start_time"]));
		
		dodebug("looping through years = ");
		debug_array($years);
		//Verifica se existe "pasta" com o ano de date
		$flag = 0;
		$year = null;
		foreach($years as $y){
			$y = read_file_info($y);
			if ($y['identifier'] == $date['year']) {
				$year = $y;
				break;
			}
		}
		if(!$year) {
			$year_id = create_file($date['year'], 0, $main_events_id, array(), $date['year']);
			if ($year_id == 0) {
				// ERRO NA CRIACAO
			}
			$year = read_file_info($year_id);
		}
		
		dodebug("looping through months");
		debug_array($year['kids']);
		//Verifica se existe "pasta" com o mês de date
		$month = null;
		foreach($year['kids'] as $m){
			$m = read_file_info($m);
			if ($m['identifier'] == $date['mon']) {
				$month = $m;
				break;
			}
		}
		if(!$month) {
			$month_id = create_file($date['mon'], 0, $year['id'], array(), $date['mon']);
			if ($month_id == 0) {
				// ERRO NA CRIACAO
			}
			$month = read_file_info($month_id);
		}
		
		dodebug("looping through days");
		debug_array($month['kids']);
		//Verifica se existe "pasta" com o mês de date
		$day = null;
		foreach($month['kids'] as $d){
			$d = read_file_info($d);
			if ($d['identifier'] == $date['mday']) {
				$day = $d;
				break;
			}
		}
		if(!$day) {
			$day_id = create_file($date['mday'], 0, $month['id'], array(), $date['mday']);
			if ($day_id == 0) {
				// ERRO NA CRIACAO
			}
			$day = read_file_info($day_id);
		}
		
		dodebug("looping through events");
		debug_array($day['kids']);
		//Verifica se existe "pasta" com o mês de date
		$event = null;
		foreach($day['kids'] as $e){
			$e = read_file_info($e);
			if ($e['identifier'] == $id_event) {
				$event = $e;
				break;
			}
		}
		if(!$event) {
			$attr = array();
			$attr['event'] = array();
			// TODO GET FACEBOOK EVENT ATTRIBUTES
			//$attr['event']['description'] = $events['description'];
			$ev_id = create_file($events['name'], 0, $day['id'], $attr, $id_event);
			if ($ev_id == 0) {
				// ERRO NA CRIACAO
			}
			$event = read_file_info($ev_id);
		}
	
		dbclose();
		dodebug("END OF ALLL!");
		return $event;
	}

?>
