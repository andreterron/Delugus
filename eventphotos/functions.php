<?php 
	require_once("../functions.php");
	require_once("../facebook/src/facebook.php");
	
	$main_events_id = 88;		//"Pasta de eventos"
	
	function get_user_events($fb_user_id) {
		$user_events = $facebook->api("$fb_user_id/events");
		if (isset($user_events['error'])) {
			return null;
		}
		$events = $user_events['data'];
	}
	
	function find_event($id_event){
		dbconnect();
		
		$main_events = read_file_info($main_events_id);		
		$yearid = $main_events["kids"];
		
		//Converte data do facebook para date convencional
		$events = $facebook->api("/$id_event","get");
		if (isset($events['error'])) {
			return null;
		}
		$date = getdate(strtotime($events["start_time"]));
		
		//Verifica se existe "pasta" com o ano de date
		$flag = 0;
		$year = null;
		foreach($yearid as $y){
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
			$ev_id = create_file($events['name'], 0, $day['id'], $attr, $id_event);
			if ($ev_id == 0) {
				// ERRO NA CRIACAO
			}
			$event = read_file_info($ev_id);
		}
	
		dbclose();
		return $event;
	}

?>
