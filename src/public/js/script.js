var langSelect = document.getElementById("langSelect");
var randBtn = document.getElementById("randBtn");

// Updates the 'lang' url parameter
langSelect.addEventListener("change", function() {
    var language = langSelect.value;
    var href = "random.php";
    if (language !== "0") {
        href += "?lang=" + encodeURIComponent(language);
    }
    randBtn.href = href;
});

var Randomgit = {
    // Array of repositories currently loaded
    repos: [],
    
    // Amount of pending requests to ajax/random.php
    currentRequests: 0,
    
    load: function() {
        this.fetchRepos();
        
        $("#rand-btn").click(this.showNextRepo.bind(this));
    },
    
    fetchRepos: function() {
        $.ajax({
            url: "ajax/random.php",
            type: "GET",
            dataType: "json",
            context: this,
            
            success: function(newRepos) {
                this.repos = this.repos.concat(newRepos);
            },
            
            error: function(xhr, error, exception) {
                console.log("Failed to fetch repos from the server");
                console.log(error);
                console.log(exception);
            }
        });
    },
    
    showNextRepo: function() {
        var repo = this.repos.shift();
        
        $("#repo-name").text(repo.name);
        $("#repo-lang").text(repo.lang);
        $("#repo-readme").html(repo.readme_html);
        $("#repo-link").attr("href", repo.link);
        
        $("#readme-container").slideDown();
    }
}

Randomgit.load();