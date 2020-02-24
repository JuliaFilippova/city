$(document).ready(function () {
    $('.slider-reviews').slick({
        dots:           false,
        infinite:       true,
        speed:          700,
        slidesToShow:   3,
        slidesToScroll: 1,
        arrows:         true,
        responsive:     [{
            breakpoint: 1300,
            settings:   {
                slidesToShow:   2,
                slidesToScroll: 1
            }
        },
            {
                breakpoint: 920,
                settings:   {
                    slidesToShow:   1,
                    slidesToScroll: 1
                }
            }]
    });
});