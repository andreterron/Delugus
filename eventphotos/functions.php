<?php 
	require_once("../functions.php");
	
	$main_events_id = 88;		//"Pasta de eventos"
	
	function find_event($id_event){
		dbconnect();
		
		$main_events = read_file_info($main_events_id);		
		$yearid = $main_events["kids"];
		
		//Converte data do facebook para date convencional
		$events = $facebook->api("/$id_event","get");
		$date = getdate(strtotime($events["start_time"]));
		
		//Verifica se existe "pasta" com o ano de date
		$flag = 0;
		foreach($yearid as $year){
			if($year == $date['year']){
				//Interação procurando mês
				$flag = 1;
				break;
			}
		}
		if(!$flag){
			// cria nova pasta de ano e guarda em $year
		}
		
		$month_begin = read_file_info($year);
		$monthid = $month_begin["kids"];
		
		//Verifica se existe "pasta" com o mês de date
		$flag = 0;
		foreach($monthid as $month){
			if($month == $date['month']){
				//Interação procurando mês
				$flag = 1;
				break;
			}
		}
		if(!$flag){
			// cria nova pasta de mês
		}
		
		$day_begin = read_file_info($month);
		$dayid = $day_begin["kids"];
		
		$flag = 0;
		foreach($dayid as $day){
			if($day == $date['day']){
				//Interação procurando dia
				$flag = 1;
				break;
			}
		}
		if(!$flag){
			// cria nova pasta de dia
		}
		
		$event_begin = read_file_info($day);
		$event_id = $event_begin["kids"];
		
		foreach($event_id as $event){
			if($event == $id_event){
				//Achou o Evento
				$flag = 1;
				break;
			}
		}
		if(!$flag){
			// cria Evento
		}
	
		dbclose();
	
	}

?>
