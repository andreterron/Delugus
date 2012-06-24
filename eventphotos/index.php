<?php

require '../facebook/src/facebook.php';

$facebook = new Facebook(array(  'appId'  => '446976568660000',  'secret' => '690d3324140569acf476af951a625f03',  'cookie' => true));     
$session = $facebook->getSession();     
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
?>

<!DOCTYPE html>
<html>
    <head>
		<title>My Facebook Login Page</title>
    </head>
    <body>

		<div id="fb-root"></div>
		<script>
		window.fbAsyncInit = function() {
		  FB.init({
			appId      : '446976568660000', // App ID
			channelUrl : '//photos.delugus.com/channel.php', // Channel File
			status     : true, // check login status
			cookie     : true, // enable cookies to allow the server to access the session
			xfbml      : true  // parse XFBML
		  });
		};
		// Load the SDK Asynchronously
		(function(d){
		   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		   if (d.getElementById(id)) {return;}
		   js = d.createElement('script'); js.id = id; js.async = true;
		   js.src = "//connect.facebook.net/en_US/all.js";
		   ref.parentNode.insertBefore(js, ref);
		 }(document));
		</script>

		<div class="fb-login-button">Login with Facebook</div>
		
		<!-- <div 
        class="fb-registration" 
        data-fields="[
		{'name':'name'},
		{'name':'email'},
		{'name':'gender'},
		{'name':'birthday'},
		{'name':'password'}]" 
        data-redirect-uri="http://www.delugus.com/debug.php">
		</div> -->

    </body>
 </html>