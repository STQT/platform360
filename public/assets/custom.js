$(document).ready(function() {
    $('.dubai360-header__logo-slider').slick({
        vertical: true,
        slidesToShow: 1,
        autoplay: true,
        arrows: false,
        autoplaySpeed: 7000
    });

    $('audio').mediaelementplayer({
      loop: true,
      success: function(player, node) {

        // More code
      }
    });

    $(window).on('resize', function () {
        if ($(this).width() > $(this).height() && isMobile) {
            $('.dubai360-header').hide();
        } else if (($(this).width() < $(this).height() && isMobile)) {
            $('.dubai360-header').show();
        }
    })
});