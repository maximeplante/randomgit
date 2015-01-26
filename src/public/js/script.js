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
    cache: [],
    
    // Amount of pending requests to ajax/random.php
    currentRequests: 0,
    
    load: function() {
        this.fetchRepos();
        
        $("#rand-btn").click(this.showNextRepo.bind(this));
    },
    
    /**
     * Adds 10 repos to the cache if the cache's size is smaller than 10.
     * 
     * @param cb A callback to be called when the request has been recieved
     * 
     * @todo Request a variable number of repos
     */
    fetchRepos: function() {
        if(this.cache.length < 10) { // TODO: Makethis variable
            $.ajax({
                url: "ajax/random.php",
                type: "GET",
                data: {limit: 10}, // TODO: make this variable
                dataType: "json",
                context: this,
                
                success: function(newRepos) {
                    this.cache = this.cache.concat(newRepos);
                    this.enableRandBtn();
                },
                
                error: function(xhr, error, exception) {
                    console.log("Failed to fetch repos from the server");
                    console.log(error);
                    console.log(exception);
                    console.log("Retrying...");
                    
                    this.fetchRepos();
                }
            });
        }
    },
    
    showNextRepo: function() {
        var repo = this.cache.shift();
        
        $("#intro").slideUp();
        
        $("#readme-container").slideUp(400, function() {
            $("#repo-name").text(repo.name);
            $("#repo-lang").text(repo.lang);
            $("#repo-readme").html(repo.readme_html);
            $("#repo-link").attr("href", repo.url);
            
            $(this).slideDown();
        });
        
        this.fetchRepos();
        
        if(this.cache.length === 0) {
            this.disableRandBtn();
        }
    },
    
    disableRandBtn: function() {
        $("#rand-btn").prop("disabled", true);
    },
    
    enableRandBtn: function() {
        $("#rand-btn").prop("disabled", false);
    }
}

Randomgit.load();