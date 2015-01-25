<?php
include(dirname(__FILE__) . '/../lib/Repo.php');
include(dirname(__FILE__) . '/../lib/RepoCache.php');
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
        
        <link href='//fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
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
        <div class="github-fork-ribbon-wrapper right visible-md-block visible-lg-block">
            <div class="github-fork-ribbon">
                <a href="//github.com/Max840/randomgit" target="_blank">Fork me on GitHub</a>
            </div>
        </div>
        
        <div class="container">
            <header id="intro">
                <h1>Random<span class="blueText">Git</span>.com</h1>
                <h3 class="description">Explore random GitHub repositories by clicking on the blue button!</h3>
            </header>
            
            <br>
            
            <div class="row">
                <div class="hidden-xs col-sm-2"></div>
                <div class="col-xs-7 col-sm-5">
                    <select id="langSelect" class="form-control input-md">
                        <option value="0">Language filter...</option>
                        <?php foreach ($langList as $lang) { ?>
                            <option value="<?php echo $lang; ?>"><?php echo $lang; ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="col-xs-5 col-sm-3">
                    <button id="rand-btn" class="btn btn-primary btn-md btn-block">
                        <!-- When the button is enabled, only the first <span> is visible and vice-versa -->
                        <span class="on-btn-enabled">
                            <span class="hidden-xs"><span class="glyphicon glyphicon-random"></span>&nbsp;</span>
                            Randomize!
                        </span>
                        <span class="on-btn-disabled">
                            Loading...
                        </span>
                    </button>
                </div>
                <div class="hidden-xs col-sm-2"></div>
            </div>
            
            <br>
            
            <div id="readme-container" class="panel panel-default" style="display: none;">
                <div class="panel-heading">
                    <a href="#" id="repo-link" target="_blank">
                        <span class="glyphicon glyphicon-link"></span>
                        <strong id="repo-name">Repo name</strong>
                    </a>&nbsp;
                    <span id="repo-lang" class="label label-default">Language</span>
                </div>
                <div id="repo-readme" class="panel-body">
                    
                </div>
            </div>
        </div>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
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