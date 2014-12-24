<!DOCTYPE html>
<html>
    <head>
        <title>RandomGit.com - Discover GitHub repositories</title>
        
        <meta name="description" content="Click on the button to be redirected to a randomly selected GitHub repository">
        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script>
            if (document.location.hostname.search("randomgit.com") !== -1) {
                var trackOutboundLink = function(url) {
                    ga('send', 'event', 'outbound', 'click', url, {'hitCallback':
                        function () {
                            // Do nothing since the link has already been opened in a new window
                        }
                    });
                };
            }
        </script>
    </head>
    <body>
        <div id="container">
            <h1>Random<span class="blueText">Git</span>.com</h1>
            <h3>Click on the button to be redirected to a randomly selected GitHub repository</h3>
			<!-- Remove the "return false;" because the link opens in a new tab and does not prevent Google Amalytics from sending the data -->
            <h3><a href="random.php" class="button" target="_blank" onclick="trackOutboundLink('random.php');">Randomize!</a></h3>
            <a href="https://github.com/Max840/randomgit" target="_blank"><img src="img/github-logo.png" width="32" height="32" alt="Visit us on GitHub!"/></a>
        </div>
        <!-- Google Analytics -->
        <script>
            // Prevents Google Analytics from counting the visits when the website is on a development server
            if (document.location.hostname.search("randomgit.com") !== -1) {
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            
                ga('create', 'UA-50135382-2', 'auto');
                ga('send', 'pageview');
            }
        </script>
    </body>
</html>