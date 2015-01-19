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