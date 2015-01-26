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
        
        if(this.cache.length < 10) { // TODO: Makethis variable
            $.ajax({
                url: "ajax/random.php",
                type: "GET",
                data: data,
                dataType: "json",
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
                    
                    this.fetchRepos();
                }
            });
        }
    },
    
    /**
     * Pops the next repo from the cache and shows it.
     */
    showNextRepo: function() {
        var repo = this.cache.shift();
        
        $("#intro").slideUp();
        
        $("#readme-container").slideUp(400, function() {
            var template = tmpl("repo_tmpl", repo);
            
            $(this)
                .html(template)
                .slideDown();
        });
        
        this.fetchRepos();
        
        if(this.cache.length === 0) {
            this.disableRandBtn();
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
        this.lang = $("#lang-select").val();
        this.cache = [];
        this.disableRandBtn();
        this.fetchRepos();
    }
}