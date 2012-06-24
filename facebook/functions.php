<?php 

$ws = array();
$ws['movies'] = 2;
$ws['music'] = 2;
$ws['television'] = 2;
$ws['sports'] = 2;
$ws['teams'] = 2;
$ws['games'] = 2;
$ws['entertainment'] = 2;
$ws['books'] = 2;
	
function calculateNum($t1, $t2, $l1, $l2, $sim, $p = 2) {
	return $p * $sim; // round(10 * $p * ($sim * $sim * $sim / max($t1 * $t2, 1)));
}


function likeCompare($v1, $v2) {
	echo 'a';
	if (idx($v1, 'id') == idx($v2, 'id')) {
		echo idx($v1, 'name');
		return 0;
	}
	return 0;
}

function likesNumber($p1, $p2) {

	global $ws;
	global $facebook;

	$id1 = idx($p1, 'id');
	$id2 = idx($p2, 'id');
		
	$p1_name = idx($p1, 'name');
	$p2_name = idx($p2, 'name');
	$total_points = 0;
	
	/* Comparando LIKES */
	$p1_likes = idx($facebook->api("/$id1/likes"), 'data', array());
	$p2_likes = idx($facebook->api("/$id2/likes"), 'data', array());
	$similar_likes = array();
	foreach($p1_likes as $v1)
	{
		foreach($p2_likes as $v2)
		{
			if (idx($v1, 'id') == idx($v2, 'id')) {
				array_push($similar_likes, $v1);
				break;
			}
		}
	}
	
	return count($similar_likes);
}


function pointsNumber($p1, $p2, $ws, $printing = false) {

	global $ws;
	global $facebook;

	$id1 = idx($p1, 'id');
	$id2 = idx($p2, 'id');
		
		$p1_name = idx($p1, 'name');
		$p2_name = idx($p2, 'name');
		$total_points = 0;
		
		/* Comparando LIKES */
		$p1_likes = idx($facebook->api("/$id1/likes"), 'data', array());
		$p2_likes = idx($facebook->api("/$id2/likes"), 'data', array());
		$similar_likes = array();
		foreach($p1_likes as $v1)
		{
			foreach($p2_likes as $v2)
			{
				if (idx($v1, 'id') == idx($v2, 'id')) {
					array_push($similar_likes, $v1);
					break;
				}
			}
		}
		
		
		$p1_lkn = count($p1_likes);
		$p2_lkn = count($p2_likes);
		if ($printing) {
			echo "$p1_name likes: $p1_lkn<br/>";
			echo "$p2_name likes: $p2_lkn<br/>";
			echo "Intersect: " . count($similar_likes) . "<br/>";
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum($p1_lkn, $p2_lkn, $p1_lkn, $p2_lkn, count($similar_likes), 1);
		
		/* Comparando MOVIES */
		$p1_movies = idx($facebook->api("/$id1/movies"), 'data', array());
		$p2_movies = idx($facebook->api("/$id2/movies"), 'data', array());
		$similar_movies = array();
		foreach($p1_movies as $m1)
		{
			foreach($p2_movies as $m2)
			{
				if (idx($m1, 'id') == idx($m2, 'id')) {
					array_push($similar_movies, $m1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name movies: " . count($p1_movies) . "<br/>";
			echo "$p2_name movies: " . count($p2_movies) . "<br/>";
			echo "Intersect: " . count($similar_movies) . "<br/>";
			echo "<br/><br/>";
		}
		
		
		$total_points += calculateNum(count($p1_movies), count($p2_movies), $p1_lkn, $p2_lkn, count($similar_movies), $ws['movies']);
		
		/* Comparando MUSIC */
		$p1_music = idx($facebook->api("/$id1/music"), 'data', array());
		$p2_music = idx($facebook->api("/$id2/music"), 'data', array());
		$similar_music = array();
		foreach($p1_music as $mu1)
		{
			foreach($p2_music as $mu2)
			{
				if (idx($mu1, 'id') == idx($mu2, 'id')) {
					array_push($similar_music, $mu1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name music: " . count($p1_music) . "<br/>";
			echo "$p2_name music: " . count($p2_music) . "<br/>";
			echo "Intersect: " . count($similar_music) . "<br/>";
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_music), count($p2_music), $p1_lkn, $p2_lkn, count($similar_music), $ws['music']);
		
		/* Comparando TV SHOW */
		$p1_television = idx($facebook->api("/$id1/television"), 'data', array());
		$p2_television = idx($facebook->api("/$id2/television"), 'data', array());
		$similar_television = array();
		foreach($p1_television as $tv1)
		{
			foreach($p2_television as $tv2)
			{
				if (idx($tv1, 'id') == idx($tv2, 'id') && idx($tv1, 'category') == 'Tv show') {
					array_push($similar_television, $tv1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name television: " . count($p1_television) . "<br/>";
			echo "$p2_name television: " . count($p2_television) . "<br/>";
			echo "Intersect: " . count($similar_television) . "<br/>";
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_television), count($p2_television), $p1_lkn, $p2_lkn, count($similar_television), $ws['television']);
		
		/* Comparando SPORTS */
		$p1_sports = idx($p1, 'sports', array());
		$p2_sports = idx($p2, 'sports', array());
		$similar_sports = array();
		foreach($p1_sports as $s1)
		{
			foreach($p2_sports as $s2)
			{
				if (idx($s1, 'id') == idx($s2, 'id')) {
					array_push($similar_sports, $s1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name sports: " . count($p1_sports) . "<br/>";
			echo "$p2_name sports: " . count($p2_sports) . "<br/>";
			echo "Intersect: " . count($similar_sports) . "<br/>";
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_sports), count($p2_sports), $p1_lkn, $p2_lkn, count($similar_sports), $ws['sports']);
		
		/* Comparando TEAMS */
		$p1_teams = idx($p1, 'favorite_teams', array());
		$p2_teams = idx($p2, 'favorite_teams', array());
		$similar_teams = array();
		foreach($p1_teams as $t1)
		{
			foreach($p2_teams as $t2)
			{
				if (idx($t1, 'id') == idx($t2, 'id')) {
					array_push($similar_teams, $t1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name teams: " . count($p1_teams) . "<br/>";
			echo "$p2_name teams: " . count($p2_teams) . "<br/>";
			echo "Intersect: " . count($similar_teams) . "<br/>";
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_teams), count($p2_teams), $p1_lkn, $p2_lkn, count($similar_teams), $ws['teams']);
		
		/* Comparando GAMES */
		$p1_games = idx($p1_likes, 'data', array());
		
		$p2_games = idx($p2_likes, 'data', array());
		
		for ($i = count($p1_games) - 1; $i >= 0; $i--) {
			if (!(idx($p1_games[$i], 'category') == 'Games/toys')) {
				array_splice($p1_games, $i, 1);
			}
		}
		for ($i = count($p2_games) - 1; $i >= 0; $i--) {
			if (!(idx($p2_games[$i], 'category') == 'Games/toys')) {
				array_splice($p2_games, $i, 1);
			}
		}
		
		$similar_games = array();
		foreach($p1_games as $gm1)
		{
			foreach($p2_games as $gm2)
			{
				if (idx($gm1, 'id') == idx($gm2, 'id') && idx($gm1, 'category') == 'Games/toys') {
					array_push($similar_games, $gm1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name games: " . count($p1_games) . "<br/>";
			echo "$p2_name games: " . count($p2_games) . "<br/>";
			echo "Intersect: " . count($similar_games) . "<br/>";
			echo "<br/><br/>";
		}
		
		
		$total_points += calculateNum(count($p1_games), count($p2_games), $p1_lkn, $p2_lkn, count($similar_games), $ws['games']);
		
		/* Comparando ENTERTAINMENT */
		$p1_ent = idx($p1_likes, 'data', array());
		
		$p2_ent = idx($p2_likes, 'data', array());
		
		$similar_ent = array();
		for ($i = count($p1_ent) - 1; $i >= 0; $i--) {
			if (!(idx($p1_ent[$i], 'category') == 'Entertainment')) {
				array_splice($p1_ent, $i, 1);
			}
		}
		for ($i = count($p2_ent) - 1; $i >= 0; $i--) {
			if (!(idx($p2_ent[$i], 'category') == 'Entertainment')) {
				array_splice($p2_ent, $i, 1);
			}
		}
		foreach($p1_ent as $en1)
		{
			foreach($p2_ent as $en2)
			{
				if (idx($en1, 'id') == idx($en2, 'id') && idx($en1, 'category') == 'Entertainment') {
					array_push($similar_ent, $en1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name  entertainment: " . count($p1_ent) . "<br/>";
			echo "$p2_name ent: " . count($p2_ent) . "<br/>";
			echo "Intersect: " . count($similar_ent) . "<br/>";
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_ent), count($p2_ent), $p1_lkn, $p2_lkn, count($similar_ent), $ws['entertainment']);
		
		/* Comparando LOCATION */
		$p1_location = idx($p1, 'location', array());
		$p2_location = idx($p2, 'location', array());
		if (idx($p1_location, 'id') == idx($p2_location, 'id')) {
			if ($printing) {
				echo "Same Location: 1";
				echo "<br/><br/>";
			}
		}
		
		/* Comparando HOMETOWN */
		$p1_hometown = idx($p1, 'hometown', array());
		$p2_hometown = idx($p2, 'hometown', array());
		if (idx($p1_hometown, 'id') == idx($p2_hometown, 'id')) {
			if ($printing) {
				echo "Same hometown: 1";
				echo "<br/><br/>";
			}
	    }    
	    
	    
	    /* Comparando Religion */
		$p1_religion = idx($p1, 'religion', array());
		$p2_religion = idx($p2, 'religion', array());
		if ($p1_religion == $p2_religion) {
			if ($printing) {
				echo "Same religion: 1";
				echo "<br/><br/>";
			}
	    }
	     
	     
	    /* Comparando books */
		$p1_books = idx($facebook->api("/$id1/books"), 'data', array());
		$p2_books = idx($facebook->api("/$id2/books"), 'data', array());
		$similar_books = array();
		foreach($p1_books as $bk1)
		{
			foreach($p2_books as $bk2)
			{
				if (idx($bk1, 'id') == idx($bk2, 'id')) {
					array_push($similar_books, $bk1);
					break;
				}
			}
		}
		
		if ($printing) {
			echo "$p1_name books: " . count($p1_books) . "<br/>";
			echo "$p2_name books: " . count($p2_books) . "<br/>";
			echo "Intersect: " . count($similar_books) . "<br/>";
			echo "<br/><br/>";
		}
		
		
		$total_points += calculateNum(count($p1_books), count($p2_books), $p1_lkn, $p2_lkn, count($similar_books), $ws['books']);
		
		if ($printing) {
			echo "<h1>YOUR TOTAL POINTS ARE: $total_points</h1>";
		} else {
			echo $total_points;
		}
	}