<?php

/*<<<<<<< HEAD
=======*/

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
  
  $lim = (isset($_GET['limit']) ? $_GET['limit'] : 20);
  // This fetches ALL of your friends.
  $friends = idx($facebook->api("/me/friends?limit=$lim"), 'data', array());
  if (empty($friends)) echo "esta vazio!<br>";
}

// Fetch the basic info of the app that they are using
$app_info = $facebook->api('/'. AppInfo::appID());
$app_name = idx($app_info, 'name', '');

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

<div id="people-list">
	<?php
	
	$ws = array();
	$ws['movies'] = (isset($_GET['movies']) ? $_GET['movies'] : 2);
	$ws['music'] = (isset($_GET['movies']) ? $_GET['movies'] : 2);
	$ws['television'] = (isset($_GET['television']) ? $_GET['television'] : 2);
	$ws['sports'] = (isset($_GET['sports']) ? $_GET['sports'] : 2);
	$ws['teams'] = (isset($_GET['teams']) ? $_GET['teams'] : 2);
	$ws['games'] = (isset($_GET['games']) ? $_GET['games'] : 2);
	$ws['entertainment'] = (isset($_GET['entertainment']) ? $_GET['entertainment'] : 2);
	$ws['books'] = (isset($_GET['books']) ? $_GET['books'] : 2);
	
	$user = $facebook->api("/me");
	
	foreach ($friends as $friend)
	{ 
		$id = idx($friend, 'id');
		$f = $facebook->api("/$id");
		$f_gender = idx($f, 'gender');
		$f_rstatus = idx($f, 'relationship_status', 'Single');
		$min_age = (isset($_GET['AgeMin']) ? $_GET['AgeMin'] : 0);
		$max_age = (isset($_GET['AgeMax']) ? $_GET['AgeMax'] : 1000);
		
		list($month,$day,$year) = explode("/",idx($f, 'birthday'));
		$f_age = date("Y") - $year;
		$month_dif = date("m") - $month;
		$day_dif = date("d") - $day;
		if ($day_dif < 0 || $month_dif < 0) $f_age--;
		
		if ($_GET[$f_gender] == "true" && ($f_rstatus != "In a relationship" &&
			$f_rstatus != "Engaged" && $f_rstatus != "Married" && $f_rstatus != "It's complicated" &&
			$f_rstatus != "In a civil union" && $f_rstatus != "In a domestic partnership")) {
		    
		    
		    //$f_points = $_GET[location] + $_GET[sports] +$_GET[sportsteams] + $_GET[music] +$_GET[movies] + $_GET[shows] +$_GET[games] + $_GET[books] +$_GET[religion] + $_GET[hometown]
			
		?>
		    <div style="border: 2px black solid;">
			    <div style="width: 100%; height: 12px; background-color: #ff0000;"></div>
			    Name: <?php echo idx($f, 'name'); ?><br/>
			    Gender: <?php echo $f_gender; ?><br/>
			    Relationship status: <?php echo $f_rstatus; ?><br/>
			    Compatibility points: <?php if (isset($_GET['type']) && $_GET['type'] == "simples") echo likesNumber($f, $user); else pointsNumber($f, $user, $ws, false); ?><br/>
		    </div>
	    <?php
	    } 
	}
/*>>>>>>> 6da26f2cdbabab3289f1f9dd6b6bf82b2817d7be*/
	?>
</div>

</body>
</head>
