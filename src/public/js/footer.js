$(".footer-menu-elem").popover({
    content: function() {
        return $("#" + $(this).attr("data-popover")).html();
    },
    html: true,
    placement: 'bottom',
    template: '<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
});

$(".footer-menu-elem").click(function() {
    $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
});