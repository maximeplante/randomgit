<?php
include(dirname(__FILE__) . '/../Classes/Repo.php');
include(dirname(__FILE__) . '/../Classes/RepoCache.php');
$config = include(dirname(__FILE__) . '/../config.php');

if ($config['debug']) {
    ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}
error_reporting(E_ALL | E_STRICT);

$repoCache = new RepoCache($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);

/* For the language filter.
 * Removes every programming language with less than 25 repositories using it as their main language
 * to prevent random.php from always returning the same repositories.
 */
$langList = $repoCache->langList(25);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>RandomGit.com - Discover GitHub repositories</title>
        
        <meta name="description" content="Explore random GitHub repositories">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/libs/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        
        <!-- "Fork me on GitHub" ribbon : https://github.com/simonwhitaker/github-fork-ribbon-css -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/github-fork-ribbon-css/0.1.1/gh-fork-ribbon.min.css">
        <!--[if lt IE 9]>
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/github-fork-ribbon-css/0.1.1/gh-fork-ribbon.ie.min.css">
        <![endif]-->
        
        <!-- Google Analytics (for events) -->
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
        <!-- "Fork me on GitHub" ribbon -->
        <div class="github-fork-ribbon-wrapper right">
            <div class="github-fork-ribbon">
                <a href="https://github.com/Max840/randomgit">Fork me on GitHub</a>
            </div>
        </div>
        
        <div class="container">
            <h1>Random<span class="blueText">Git</span>.com</h1>
            <h3>Explore random GitHub repositories by clicking on the blue button!</h3>
            <select class="form-control">
                <option value="0">Language filter...</option>
                <?php foreach ($langList as $lang) { ?>
                    <option value="<?php echo $lang; ?>"><?php echo $lang; ?></option>
                <?php } ?>
            </select>
			<!-- Remove the "return false;" because the link opens in a new tab and does not prevent Google Amalytics from sending the data -->
            <h3><a href="random.php" id="randBtn" class="btn btn-primary btn-lg" target="_blank" onclick="trackOutboundLink('random.php');">Randomize!</a></h3>
        </div>
        
        <script src="js/libs/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
        
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