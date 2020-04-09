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
          media.play();
      }
    });

    var audioPlayer = $('#audio')[0];
    // $('#playaudio').off('click');
    $('#playaudio').on('click', function() {
        //включение и отключение звука у аудио
        if (audioPlayer.src.search('.mp3') !== -1) {
            $(".mejs__controls").find(".mejs__button")[0].click();
            if (audioPlayer.getPaused() === true) {
                $('#playaudio').find('img').attr('src', '/assets/icons/sound-off.svg');
            } else {
                $('#playaudio').find('img').attr('src', '/assets/icons/sound-on.svg');
            }
        }
    });

    // $('audio').bind('playing', function() {
    //     $('body').off('click', '#playaudio');
    //     $('body').on('click', '#playaudio', function() {
    //        audioPlayer.setMuted(false);
    //        audioPlayer.setVolume(1);
    //        audioPlayer.pause();
    //     });
    //  });
    //
    // $('audio').bind('pause', function() {
    //     $('body').off('click', '#playaudio');
    //     $('body').on('click', '#playaudio', function() {
    //         audioPlayer.setMuted(false);
    //         audioPlayer.setVolume(1);
    //         audioPlayer.play();
    //     });
    //  });

    $(window).on('resize', function () {
        if ($(this).width() > $(this).height() && isMobile) {
            $('.dubai360-header').hide();
            setTimeout(function () {
                $('.toolbar-button').hide();
                $('footer.dubai360-footer').hide();
            }, 100);
        } else if (($(this).width() < $(this).height() && isMobile)) {
            $('.dubai360-header').show();
            setTimeout(function () {
                $('.toolbar-button').show();
                $('footer.dubai360-footer').show();
            }, 100);
        }
    });

    $('.clear-field').on('click', function (e) {
        e.preventDefault();
        $('#search_adress input').val('');
    });

    $('.modal .close').on('click', function() {
       $(this).parent().fadeOut();
    });
});