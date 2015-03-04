$("#footer-btn-about").popover({
    content: function() {
        return $("#about-container").html();
    },
    html: true,
    placement: 'bottom',
    template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
});

$("#footer-btn-tech").popover({
    content: function() {
        return $("#tech-container").html();
    },
    html: true,
    placement: 'bottom',
    template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
});

$(".footer-menu-elem").click(function() {
    $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
});