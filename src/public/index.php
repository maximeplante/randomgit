<?php
include(dirname(__FILE__) . '/../autoload.php');

$repoCache = RepoCacheFactory::create($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['dbName']);

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
        
        <!-- Google Analytics -->
        <script>
            // Prevents Google Analytics from counting the visits when the website is on a development server
            if(document.location.hostname.search("randomgit.com") !== -1) {
                var _gaq = _gaq || [];
                _gaq.push(['_setAccount', 'UA-50135382-2']);
                _gaq.push(['_trackPageview']);
                
                (function() {
                    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                })();
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
                    <select id="lang-select" class="form-control input-md">
                        <option value="all">Language filter...</option>
                        <?php foreach ($langList as $lang) { ?>
                            <option value="<?php echo $lang; ?>"><?php echo $lang; ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="col-xs-5 col-sm-3">
                    <button id="rand-btn" class="btn btn-primary btn-md btn-block" disabled>
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
            
            <div id="readme-container">
                <!-- The readme will be added here following the template below -->
            </div>
        </div>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="js/libs/bootstrap.min.js"></script>
        <script src="js/libs/template.js"></script>
        <script src="js/script.js"></script>
        <script>
            Randomgit.cache = <?php
                // Preloading the cache
                $repoList = $repoCache->randomRepo(10);
                echo json_encode(API::convertRepoArray($repoList));
            ?>;
            Randomgit.load();
            Randomgit.enableRandBtn();
        </script>
        
        <script type="text/html" id="repo_tmpl">
            <div id="next-repo">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="<%=url%>" id="repo-link" target="_blank">
                            <span class="glyphicon glyphicon-link"></span>
                            <strong id="repo-name"><%=name%></strong>
                        </a>&nbsp;
                        <span id="repo-lang" class="label label-default"><%=lang%></span>
                    </div>
                    <div class="panel-heading">
                        <%=description%>
                    </div>
                    <div id="repo-readme" class="panel-body">
                        <%=readme_html%>
                    </div>
                </div>
            </div>
        </script>
    </body>
</html>