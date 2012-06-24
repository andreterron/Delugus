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
		<!--div id="fb-root"></div>
		<script src="https://connect.facebook.net/en_US/all.js#appId=446976568660000&xfbml=1"></script>

		<fb:registration 
		  fields="name,email,gender,birthday,password" 
		  redirect-uri="http://www.delugus.com/debug.php"
		  width="530">
		</fb:registration-->
    </body>
 </html>