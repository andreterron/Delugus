<?php
	/* codigos especificos da pagina/app */
	
	$page_title = '00:00:00 - Cronômetro Delugus'; /* Default: "Delugus" */
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
	
	
?>
<script type="text/javascript">
	var total_time = 0;
	var old_time = new Date();
	var laps = new Array();
	var loop = null;

	function startCounting() {
		old_time = new Date();
		var slb = document.getElementById("startLapButton");
		slb.onclick = lap;
		slb.title = "Lap";
		slb.innerHTML = "Lap";
		var prb = document.getElementById("pauseResetButton");
		prb.onclick = pause;
		prb.title = "Parar";
		prb.innerHTML = "Parar";
		if (loop == null) {
			loop = window.setInterval("update()", 33);
		}
	}
	
	function lap() {
		laps.push(total_time);
		printLaps();
	}
	
	function pause() {
		var slb = document.getElementById("startLapButton");
		slb.onclick = startCounting;
		slb.title = "Iniciar";
		slb.innerHTML = "Iniciar";
		var prb = document.getElementById("pauseResetButton");
		prb.onclick = reset;
		prb.title = "Zerar";
		prb.innerHTML = "Zerar";
		if (loop != null) {
			clearInterval(loop);
			loop = null
		}
	}
	
	function reset() {
		if (loop != null) {
			clearInterval(loop);
			loop = null
		}
		total_time = 0;
		laps = new Array()
		printLaps();
		printTime(format_seconds(total_time, true));
	}

	function update() {
		now = new Date();
		total_time += now - old_time;
		old_time = now;
		printTime(format_seconds(total_time, true));
	}
	
	function printLaps() {
		var i;
		var txt = "";
		for (i = 0; i < laps.length; i++) {
			txt = txt + "<div class='lap'>" + (i + 1) + " - " + format_seconds(laps[i], false) + "</div>";
		}
		document.getElementById('laplist').innerHTML = txt;
	}

	function printTime(time){
		document.getElementById('timer').innerHTML = time;
	}

	function format_seconds(t, update_title) {
		if(isNaN(t))
			t = 0;

		var txt = "";
		//var diff = new Date(milli_seconds);
		var milliseconds = t % 1000;
		t = Math.floor(t / 1000);
		var seconds = t % 60;
		t = Math.floor(t / 60);
		var minutes = t % 60;
		t = Math.floor(t / 60);
		var hours = t;
		
		if (hours < 10)
			hours = "0" + hours;
		if (hours != 0) {
			txt += hours + ":";
		}
		if (minutes < 10)
			minutes = "0" + minutes;
		if (minutes != 0) {
			txt += minutes + ":";
		}
		if (seconds < 10)
			seconds = "0" + seconds;
		if (seconds != 0) {
			txt += seconds + " - ";
		}
		txt += "Cronômetro Delugus";

		if (milliseconds < 10)
			milliseconds = "00" + milliseconds;
		else if (milliseconds < 100)
			milliseconds = "0" + milliseconds;
		
		if (update_title) {
			document.title = txt;
		}
		return hours + ":" + minutes + ":" + seconds + ":" + milliseconds;
	}
</script>

<style>
	.timerButton {
		width: 128px;
		height: 32px;
		font-size: 24px;
		margin-bottom: 16px;
		margin-right: 16px;
	}
	
	.lap {
		font-family: Century Gothic, sans-serif;
		font-size: 20px;
		padding-bottom: 4px;
	}
	
	#timer {
		font-family: Century Gothic, sans-serif;
		font-size: 48px;
		padding-bottom: 16px;
	}
</style>


<?php include_once("bodyTop.php"); ?>

	<div style="padding: 32px;">
		<div id="msg_container"></div>
		<div id="timer">00:00:00:000</div>
		
		<button id="startLapButton" class="timerButton" onclick="startCounting();" title="Iniciar">Iniciar</button>
		<button id="pauseResetButton" class="timerButton" onclick="reset();" title="Zerar">Zerar</button>
		<div id="laplist"></div>
		<script>reset();</script>
	</div>

<?php include_once("bodyBottom.php"); ?>