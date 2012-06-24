<?php

$printing = (!isset($_GET['output']) || $_GET['output'] != "number");

/**
 * This sample app is provided to kickstart your experience using Facebook's
 * resources for developers.  This sample app provides examples of several
 * key concepts, including authentication, the Graph API, and FQL (Facebook
 * Query Language). Please visit the docs at 'developers.facebook.com/docs'
 * to learn more about the resources available to you
 */

// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'
require_once('AppInfo.php');

// Enforce https on production
if (substr(AppInfo::getUrl(), 0, 8) != 'https://' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit();
}

// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');


/*****************************************************************************
 *
 * The content below provides examples of how to fetch Facebook data using the
 * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
 * do so.  You should change this section so that it prepares all of the
 * information that you want to display to the user.
 *
 ****************************************************************************/

require_once('sdk/src/facebook.php');

require_once('functions.php');

$facebook = new Facebook(array(
  'appId'  => AppInfo::appID(),
  'secret' => AppInfo::appSecret(),
));

$user_id = $facebook->getUser();
if ($user_id) {
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
      header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
      exit();
    }
  }
}

// Fetch the basic info of the app that they are using
$app_info = $facebook->api('/'. AppInfo::appID());

$app_name = idx($app_info, 'name', '');

if ($printing) {

?>

<!DOCTYPE html>

<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

    <title><?php echo he($app_name); ?></title>
    <link rel="stylesheet" href="stylesheets/screen.css" media="Screen" type="text/css" />
    <link rel="stylesheet" href="stylesheets/mobile.css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" type="text/css" />

    <!--[if IEMobile]>
    <link rel="stylesheet" href="mobile.css" media="screen" type="text/css"  />
    <![endif]-->

    <!-- These are Open Graph tags.  They add meta data to your  -->
    <!-- site that facebook uses when your content is shared     -->
    <!-- over facebook.  You should fill these tags in with      -->
    <!-- your data.  To learn more about Open Graph, visit       -->
    <!-- 'https://developers.facebook.com/docs/opengraph/'       -->
    <meta property="og:title" content="<?php echo he($app_name); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
    <meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
    <meta property="og:site_name" content="<?php echo he($app_name); ?>" />
    <meta property="og:description" content="My first app" />
    <meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />
	
	<style>
	* {
	/* ATENCAO! aqui eu defino que qualquer altura ou largura, ja ira incluir o padding e a border! */
	box-sizing: border-box;
	-moz-box-sizing: border-box; /* Firefox */
	-webkit-box-sizing: border-box; /* Safari */
}

.photo-container {
	border-radius: 6px;
	overflow: hidden;
	border: 1px solid #000000;
}

.photo-bg {
	background-color: #ffffff;
}

.photo-shadow {
	position: absolute;
	top: 0px;
	left: 0px;
	box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 4px 1px inset;
	-webkit-box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 4px 1px inset;
	-moz-box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 4px 1px inset;
	-ms-box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 4px 1px inset;
	-o-box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 4px 1px inset;
}

.ourbase {
	
	box-shadow: rgba(0, 0, 0, 0.5) 0px -15px 10px -10px inset;
	-webkit-box-shadow: rgba(0, 0, 0, 0.5) 0px -15px 10px -10px inset;
	-moz-box-shadow: rgba(0, 0, 0, 0.5) 0px -15px 10px -10px inset;
	-ms-box-shadow: rgba(0, 0, 0, 0.5) 0px -15px 10px -10px inset;
	-o-box-shadow: rgba(0, 0, 0, 0.5) 0px -15px 10px -10px inset;
}
</style>
	
    <script type="text/javascript" src="/javascript/jquery-1.7.1.min.js"></script>

    <script type="text/javascript">
      function logResponse(response) {
        if (console && console.log) {
          console.log('The response was', response);
        }
      }

      $(function(){
        // Set up so we handle click on the buttons
        $('#postToWall').click(function() {
          FB.ui(
            {
              method : 'feed',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendToFriends').click(function() {
          FB.ui(
            {
              method : 'send',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendRequest').click(function() {
          FB.ui(
            {
              method  : 'apprequests',
              message : $(this).attr('data-message')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });
      });
    </script>

    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
  </head>
<body>

<br/><br/>
<?php
}

//155986251195903

if ($printing) {
	/*echo "Comparing " . $_GET['p1'] . " and " . $_GET['p2'] . "<br/>";*/
}

	$id1 = (isset($_GET['p1']) ? $_GET['p1'] : 'me');
	$p1 = $facebook->api("/$id1");
	$id2 = (isset($_GET['p2']) ? $_GET['p2'] : 'me');
	$p2 = $facebook->api("/$id2");
	$id1 = idx($p1, 'id');
	$id2 = idx($p2, 'id');
	
	$ws = array();
	$ws['movies'] = (isset($_GET['movies']) ? $_GET['movies'] : 2);
	$ws['music'] = (isset($_GET['movies']) ? $_GET['movies'] : 2);
	$ws['television'] = (isset($_GET['television']) ? $_GET['television'] : 2);
	$ws['sports'] = (isset($_GET['sports']) ? $_GET['sports'] : 2);
	$ws['teams'] = (isset($_GET['teams']) ? $_GET['teams'] : 2);
	$ws['games'] = (isset($_GET['games']) ? $_GET['games'] : 2);
	$ws['entertainment'] = (isset($_GET['entertainment']) ? $_GET['entertainment'] : 2);
	$ws['books'] = (isset($_GET['books']) ? $_GET['books'] : 2);
	
	if ($id1 == $id2) {
		if ($printing) {
			?>
			
			User to compare:
			<form action="compare.php" method="get">
				<input type="text" name="p2" /><input type="submit" value="Compare!" />
			</form>
		<?php
		}
	}
	else
	{
		$p1 = $facebook->api("/$id1");
		$p2 = $facebook->api("/$id2");
		?>
		<div class="ourbase" style="position: relative; height: 32px; margin: 32px; border-radius: 8px; background-color: #cceeff; text-align: center; font-size: 48px; font-weight: bold;">
			<a href='<?php echo "/$id1";?>'>
				<div style='position: absolute; top: -64px; left: 32px;'>
					<div class='photo-container photo-bg' style="width: 128px; height: 128px; border-radius: 8px;">
						<img src='<?php echo("https://graph.facebook.com/" . he($id1) . "/picture?type=square&size=large' alt='" . idx($p1, 'name')); ?>' width='126' height='126'></img>
						<div class='photo-container photo-shadow' style="width: 128px; height: 128px; border-radius: 8px;">
						</div>
					</div>
				</div>
			</a>
			<?php
		pointsNumber ($p1, $p2, $ws, false); ?>
			<a href='<?php echo "/$id2";?>'>
				<div style='position: absolute; top: -64px; right: 32px;'>
					<div class='photo-container photo-bg' style="width: 128px; height: 128px; border-radius: 16px;">
						<img src='<?php echo("https://graph.facebook.com/" . he($id2) . "/picture?type=square&size=large' alt='" . idx($p2, 'name')); ?>' width='126' height='126'></img>
						<div class='photo-container photo-shadow' style="width: 128px; height: 128px; border-radius: 16px;">
						</div>
					</div>
				</div>
			</a>
		</div>
		<br/><br/><br/>
		User to compare:
		<form action="compare.php" method="get">
			<input type="text" name="p2" /><input type="submit" value="Compare!" />
		</form>
		
		<?php
		
		pointsNumber ($p1, $p2, $ws, true);
		
		return;
		
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
		
        $cat_likes = array();
        foreach($p1_likes as $lk) { // $similar_likes
            $cl = idx($lk, 'category');
            if(!array_key_exists($cl, $cat_likes)) $cat_likes[$cl] = array();
            array_push($cat_likes[$cl], idx($lk,'id'));
        }
		
		$p1_lkn = count($p1_likes);
		$p2_lkn = count($p2_likes);
		if ($printing) {
			echo "$p1_name likes: $p1_lkn<br/>";
			echo "$p2_name likes: $p2_lkn<br/>";
			echo "Intersect: " . count($similar_likes) . "<br/>";
			print_r($cat_likes);
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum($p1_lkn, $p2_lkn, $p1_lkn, $p2_lkn, count($similar_likes), 1);
		
		/* Comparando MOVIES */
		/*$p1_movies = idx($facebook->api("/$id1/movies"), 'data', array());
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
		
        $cat_movies = array();
        foreach($similar_movies as $mv) {
            $cm = idx($mv, 'category');
            if(!array_key_exists($cm, $cat_movies)) $cat_movies[$cm] = array();
            array_push($cat_movies[$cm], idx($mv,'id'));
        }
		if ($printing) {
			echo "$p1_name movies: " . count($p1_movies) . "<br/>";
			echo "$p2_name movies: " . count($p2_movies) . "<br/>";
			echo "Intersect: " . count($similar_movies) . "<br/>";
			//print_r($cat_movies);
			echo "<br/><br/>";
		}*/
		
		
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
		
        $cat_music = array();
        foreach($p1_music as $ms) { //$similar_music
            $cms = idx($ms, 'category');
            if(!array_key_exists($cms, $cat_music)) $cat_music[$cms] = array();
            array_push($cat_music[$cms], idx($ms,'id'));
        }
		if ($printing) {
			echo "$p1_name music: " . count($p1_music) . "<br/>";
			echo "$p2_name music: " . count($p2_music) . "<br/>";
			echo "Intersect: " . count($similar_music) . "<br/>";
			print_r($cat_music);
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
		
        $cat_television = array();
        foreach($p1_television as $ts) { // $similar_television
            $cts = idx($ts, 'category');
            if(!array_key_exists($cts, $cat_television)) $cat_television[$cts] = array();
            array_push($cat_television[$cts], idx($ts,'id'));
        }
		if ($printing) {
			echo "$p1_name television: " . count($p1_television) . "<br/>";
			echo "$p2_name television: " . count($p2_television) . "<br/>";
			echo "Intersect: " . count($similar_television) . "<br/>";
			print_r($cat_television);
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_television), count($p2_television), $p1_lkn, $p2_lkn, count($similar_television), $ws['television']);
		
		/* Comparando SPORTS */
		$p1_sports = idx($facebook->api("/$id1"), 'sports', array());
		$p2_sports = idx($facebook->api("/$id2"), 'sports', array());
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
		
        $cat_sports = array();
        foreach($p1_sports as $sp) { //$similar_sports
            $csp = idx($sp, 'category');
            if(!array_key_exists($csp, $cat_sports)) $cat_sports[$csp] = array();
            array_push($cat_sports[$csp], idx($sp,'id'));
        }
		
		if ($printing) {
			echo "$p1_name sports: " . count($p1_sports) . "<br/>";
			echo "$p2_name sports: " . count($p2_sports) . "<br/>";
			echo "Intersect: " . count($similar_sports) . "<br/>";
			print_r($cat_sports);
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_sports), count($p2_sports), $p1_lkn, $p2_lkn, count($similar_sports), $ws['sports']);
		
		/* Comparando TEAMS */
		$p1_teams = idx($facebook->api("/$id1"), 'favorite_teams', array());
		$p2_teams = idx($facebook->api("/$id2"), 'favorite_teams', array());
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
		
        $cat_teams = array();
        foreach($p1_teams as $tm) {
            $ctm = idx($tm, 'category');
            if(!array_key_exists($ctm, $cat_teams)) $cat_teams[$ctm] = array();
            array_push($cat_teams[$ctm], idx($tm,'id'));
        }
		
		if ($printing) {
			echo "$p1_name teams: " . count($p1_teams) . "<br/>";
			echo "$p2_name teams: " . count($p2_teams) . "<br/>";
			echo "Intersect: " . count($similar_teams) . "<br/>";
			print_r($cat_teams);
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_teams), count($p2_teams), $p1_lkn, $p2_lkn, count($similar_teams), $ws['teams']);
		
		/* Comparando GAMES */
		$p1_games = idx($facebook->api("/$id1/likes"), 'data', array());
		
		$p2_games = idx($facebook->api("/$id2/likes"), 'data', array());
		
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
		
        $cat_games = array();
        foreach($p1 as $ga) {
            $cg = idx($ga, 'category');
            if(!array_key_exists($cg, $cat_games)) $cat_games[$cg] = array();
            array_push($cat_games[$cg], idx($ga,'id'));
        }
		
		if ($printing) {
			echo "$p1_name games: " . count($p1_games) . "<br/>";
			echo "$p2_name games: " . count($p2_games) . "<br/>";
			echo "Intersect: " . count($similar_games) . "<br/>";
			print_r($cat_games);
			echo "<br/><br/>";
		}
		
		
		$total_points += calculateNum(count($p1_games), count($p2_games), $p1_lkn, $p2_lkn, count($similar_games), $ws['games']);
		
		/* Comparando ENTERTAINMENT */
		$p1_ent = idx($facebook->api("/$id1/likes"), 'data', array());
		
		$p2_ent = idx($facebook->api("/$id2/likes"), 'data', array());
		
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
		
        $cat_ent = array();
        foreach($p1_ent as $et) {
            $cet = idx($et, 'category');
            if(!array_key_exists($cet, $cat_ent)) $cat_ent[$cet] = array();
            array_push($cat_ent[$cet], idx($et,'id'));
        }
		
		if ($printing) {
			echo "$p1_name  entertainment: " . count($p1_ent) . "<br/>";
			echo "$p2_name ent: " . count($p2_ent) . "<br/>";
			echo "Intersect: " . count($similar_ent) . "<br/>";
			print_r($cat_ent);
			echo "<br/><br/>";
		}
		
		$total_points += calculateNum(count($p1_ent), count($p2_ent), $p1_lkn, $p2_lkn, count($similar_ent), $ws['entertainment']);
		
		/* Comparando LOCATION */
		$p1_location = idx($facebook->api("/$id1"), 'location', array());
		$p2_location = idx($facebook->api("/$id2"), 'location', array());
		if (idx($p1_location, 'id') == idx($p2_location, 'id')) {
			if ($printing) {
				echo "Same Location: 1";
				echo "<br/><br/>";
			}
		}
		
		/* Comparando HOMETOWN */
		$p1_hometown = idx($facebook->api("/$id1"), 'hometown', array());
		$p2_hometown = idx($facebook->api("/$id2"), 'hometown', array());
		if (idx($p1_hometown, 'id') == idx($p2_hometown, 'id')) {
			if ($printing) {
				echo "Same hometown: 1";
				echo "<br/><br/>";
			}
	    }    
	    
	    
	    /* Comparando Religion */
		$p1_religion = idx($facebook->api("/$id1"), 'religion', array());
		$p2_religion = idx($facebook->api("/$id2"), 'religion', array());
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
		
        $cat_books = array();
        foreach($p1_books as $bs) {
            $cbs = idx($bs, 'category');
            if(!array_key_exists($cbs, $cat_books)) $cat_books[$cbs] = array();
            array_push($cat_books[$cbs], idx($bs,'id'));
        }
		
		if ($printing) {
			echo "$p1_name books: " . count($p1_books) . "<br/>";
			echo "$p2_name books: " . count($p2_books) . "<br/>";
			echo "Intersect: " . count($similar_books) . "<br/>";
			print_r($cat_books);
			echo "<br/><br/>";
		}
		
		
		$total_points += calculateNum(count($p1_books), count($p2_books), $p1_lkn, $p2_lkn, count($similar_books), $ws['books']);
		
		if ($printing) {
			echo "<h1>YOUR TOTAL POINTS ARE: $total_points</h1>";
		} else {
			echo $total_points;
		}
	}
    ?>
<?php
if ($printing) {
?>

</body>
</head>
<?php } ?>
