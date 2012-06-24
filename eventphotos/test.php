<!DOCTYPE html>
<html>
    <head>
		<title>My Facebook Login Page</title>
    </head>
    <body>
		<div id="fb-root"></div>
		<script src="https://connect.facebook.net/en_US/all.js#appId=446976568660000&xfbml=1"></script>

		<fb:registration 
		  fields="name,email,gender,birthday,password" 
		  redirect-uri="http://www.delugus.com/debug.php"
		  width="530">
		</fb:registration>
    </body>
 </html>