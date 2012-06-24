<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"
      xmlns:fb="https://www.facebook.com/2008/fbml"> 
<head prefix="og: http://ogp.me/ns# [YOUR_APP_NAMESPACE]: 
                  http://ogp.me/ns/apps/[YOUR_APP_NAMESPACE]#">
  <title>EventPhotos</title>
  <meta property="fb:app_id" content="446976568660000" /> 
  <meta property="og:type" content="690d3324140569acf476af951a625f03:recipe" /> 
  <meta property="og:title" content="EventPhotos" /> 
  <meta property="og:image" content="http://fbwerks.com:8000/zhen/cookie.jpg" /> 
  <meta property="og:description" content="All photos of events in one place!" /> 
  <meta property="og:url" content="http://fbwerks.com:8000/zhen/cookie.html">
</head>
<body>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '446976568660000', // App ID
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true  // parse XFBML
      });
    };

    // Load the SDK Asynchronously
    (function(d){
      var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
      js = d.createElement('script'); js.id = id; js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      d.getElementsByTagName('head')[0].appendChild(js);
    }(document));
  </script>

  <h3>Stuffed Cookies</h3>
  <p>
    <img title="Stuffed Cookies" 
         src="http://fbwerks.com:8000/zhen/cookie.jpg" 
         width="550"/>
  </p>
</body>
</html>