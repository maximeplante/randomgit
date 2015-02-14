var Randomgit = {
    // Array of repositories currently loaded
    cache: [],
    
    // Current language filter
    lang: "all",
    
    // Amount of pending requests to ajax/random.php
    currentRequests: 0,
    
    load: function() {
        this.fetchRepos();
        
        $("#rand-btn").click(this.showNextRepo.bind(this));
        $("#lang-select").change(this.changeLanguage.bind(this));
    },
    
    /**
     * Checks the cache's size. If it contains less than 10 repos, fetches 10 more.
     * 
     * @todo Request a variable number of repos
     */
    fetchRepos: function() {
        var lang = this.lang,
            data = {limit: 10};  // TODO: make the amount variable
        
        if(lang !== "all") {
            data.lang = lang;
        }
        
        /* Sends a request to fetch data when the cache's size goes below 10.
         * Sends another one when the cache's size goes below 5. Thus, the two
         * requests won't happen at the same time which can avoid them bugging
         * because of them same network mini-lag.
         */
        if(this.cache.length < 10 && this.currentRequests < 1 || this.cache.length < 5 && this.currentRequests < 2) {
            this.currentRequests++;
            
            $.ajax({
                url: "ajax/random.php",
                type: "GET",
                data: data,
                dataType: "json",
                timeout: 30000,
                context: this,
                
                success: function(newRepos) {
                    // Makes sure the language hasn't changed since the request was sent
                    if(lang === this.lang) {
                        this.cache = this.cache.concat(newRepos);
                        this.enableRandBtn();
                    }
                },
                
                error: function(xhr, error, exception) {
                    console.log("Failed to fetch repos from the server");
                    console.log(error);
                    console.log(exception);
                    console.log("Retrying...");
                    
                    var self = this;
                    setTimeout(function() {
                        self.fetchRepos();
                    }, 1000);
                },
                
                complete: function() {
                    this.currentRequests--;
                }
            });
        }
    },
    
    /**
     * Pops the next repo from the cache and shows it.
     */
    showNextRepo: function() {
        var repo = this.cache.shift();
        
        if($("#next-repo").length === 0) {
            var template = tmpl("repo_tmpl", repo);
            $(template).appendTo("#readme-container");
            repo = this.cache.shift();
        }
        
        $("#intro").slideUp();
        
        $("#next-repo")
            .css("display", "block")
            .animate({"opacity": 1}, 200, function() {
                $("#current-repo").remove();
                $("#next-repo").attr("id", "current-repo");
                
                // Loads the next readme in a hidden div
                var template = tmpl("repo_tmpl", repo);
                $(template).appendTo("#readme-container");
            });
        
        this.fetchRepos();
        
        if(this.cache.length === 0) {
            this.disableRandBtn();
        }
        
        // Send a google analytics event to track the click on Randomize!
        if(typeof _gaq == "object") {
            _gaq.push(["_trackEvent", "Repositories", "Randomize", this.lang]);
        }
    },
    
    /**
     * Disables the "Randomize!" button and changes its text to "Loading..."
     * The text change is done with CSS
     */
    disableRandBtn: function() {
        $("#rand-btn").prop("disabled", true);
    },
    
    /**
     * Re-enables the Randomize! button
     */
    enableRandBtn: function() {
        $("#rand-btn").prop("disabled", false);
    },
    
    /**
     * Updates the current language according to the value of #lang-select
     */
    changeLanguage: function() {
        this.disableRandBtn();
        this.lang = $("#lang-select").val();
        this.cache = [];
        $("#next-repo").remove();
        this.fetchRepos();
    }
}