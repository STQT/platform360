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
});