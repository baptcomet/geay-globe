$(document).ready(function() {
    $("div.image").hover(function() {
        var caption = $(this).find("span.caption-hoverable").first();
        var captionTitle = $(this).find("span.caption-title-hoverable").first();
        caption.slideDown("fast");
        captionTitle.slideDown("fast");
    }, function() {
        var caption = $(this).find("span.caption-hoverable").first();
        var captionTitle = $(this).find("span.caption-title-hoverable").first();
        caption.slideUp("fast");
        captionTitle.slideUp("fast");
    });

    $("a.fancybox").fancybox({
        'overlayShow' : false,
        helpers : {
            title : {
                type : 'over'
            }
        }
    });
});
