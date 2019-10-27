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
      success: function(media, node, player) {
          //I <3 Apple
          if(media.paused){
            $( "body" ).append( "<button type=\"button\" id='playaudio'><img src=\"/assets/play-icon.png\" style=\"width: 32px\"></button>" );
          }
      },
    });

    $('body').on('click', '#playaudio', function() {
        $('#audio')[0].play();
    });

    $(window).on('resize', function () {
        if ($(this).width() > $(this).height() && isMobile) {
            $('.dubai360-header').hide();
        } else if (($(this).width() < $(this).height() && isMobile)) {
            $('.dubai360-header').show();
        }
    })
});