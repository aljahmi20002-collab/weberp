$(document).ready(function(){
    $("img").lazyload({
        effect: "fadeIn"
    });
    $(window).on('load',function () {
        $('img.lazy').lazyload();
        $(window).resize();
    });

    !function () {
        var e = new Image;
        e.onload = e.onerror = function () {
            if (2 != e.height) {
                var A = document.createElement("script");
                A.type = "text/javascript", A.async = !0;
                var t = document.getElementsByTagName("script")[0];
                A.src = "js/webpjs-0.0.2.min.js", t.parentNode.insertBefore(A, t)
            }
        }, e.src = "data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA"
    }();
});