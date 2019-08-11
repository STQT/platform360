$(function() {


    // preloader
    setTimeout(function() {
        $('.loading').fadeOut('slow');
    }, 1000);

    function isMobile() {
        try {
            document.createEvent("TouchEvent");
            return true;
        } catch (e) {
            return false;
        }
    }
    if (isMobile()) {
        $('.mytab2').fadeOut();
    }
    // modals

    $('.searchPanel__filtered__clear').on('click', function() {

    });




    $('.wrapper-button__icon').on('click', function() {
        var _this = $(this);
        var curModal = $('.' + _this.data('pannel')).eq(0);



        if (_this.hasClass('is-active')) {
            _this.removeClass('is-active');
            curModal.removeClass('visible').addClass('hidden');
            if (_this.find('.wrappersvg').length !== 0) {
                _this.find('.wrappersvg').attr("fill", "#1a90d2");
            };
        } else {
            $('.wrapper-button__icon').removeClass('is-active');
            _this.addClass('is-active');
            $('.wrapper-panel').removeClass('visible').addClass('hidden');
            curModal.removeClass('hidden').addClass('visible');




            if (_this.find('.wrappersvg').length !== 0) {
                _this.find('.wrappersvg').attr("fill", "#c23691");
            };
        }

    });

    // modals close
    $('.wrapper-panel-close, .icon-ic_close, .gyro__button').on('click', function() {
        if (!$(this).hasClass('myclose') && !$(this).hasClass('myclose')) {
            $(this).parents('.wrapper-panel').removeClass('visible').addClass('hidden');
            if ($(this).parents('.wrapper-panel').hasClass('hidden')) {
                $('.wrapper-button__icon').removeClass('is-active');
            }
        }
    });





    // ******************** TImberland SCripts ************************


    $('.svg_blockk').click(function() {
    	var height = $('#location_description').height();
        $('.infoPanel__description__message').toggleClass('show');


        if ($('.infoPanel__description__message').hasClass('show')) {
        	$('.infoPanel__description__message').css('height', height);
        }else{
        	$('.infoPanel__description__message').removeAttr('style');
        }



    });

    // $("#mobilelanguage").click(function() {
    // });




    $('.wrapper-panel-close').click(function() {
       $('.infoPanel__description__message').removeAttr('style');
    });

    // ********************** EXIT FULL SCREEN ************************

    document.addEventListener('fullscreenchange', exitHandler);
    document.addEventListener('webkitfullscreenchange', exitHandler);
    document.addEventListener('mozfullscreenchange', exitHandler);
    document.addEventListener('MSFullscreenChange', exitHandler);

    function exitHandler() {
        if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {

            $('#logo2').remove();
        }
    }

    $('.wrapper-panel-close').click(function() {
        $('.searchPanel__button').css('display', 'none');

        $('.js-icon').removeClass('icon-wrapper--selected')
        $('.js-icon').addClass('icon-wrapper__icon')

    });

    $('.searchPanel__button').click(function() {
        $(this).css('display', 'none');
    });




    if ($(window).width() > 1300) {


    } else {

        $('.icon-ic_glass').click(function() {
            $('#search_adress input').focus();
            // $('.searchPanel__input').trigger('click');
        });

    }


    $('.icon-ic_glass').click(function() {
    	$('.searchPanel__results').css("display", "none");

    });



    // ****************************************************************

    // feedback modal close
    $('.close-btn').on('click', function() {
        $('.wrapper-panel.feedbackPannel').removeClass('visible').addClass('hidden');
        $('.icon-ic_comment').removeClass('is-active');
    });

    // explore filters on mobile
    $('.filterPanel__options li').on('click', function() {
        var _this = $(this);
        var curTab = $('.mytab' + _this.data('tab')).eq(0);

        $('.mytab').fadeOut();
        $('.fullScreenPanel_filter').fadeOut();
        curTab.fadeIn();

    });

    $('.mybtn').click(function() {

    })
    $('.mybtn').on('touchstart click', function() {
        $('.fullScreenPanel_filter').fadeIn();
    });
    $('.myclose').on('click', function() {
        $('.fullScreenPanel_filter').fadeOut();
    })

    $('.filterPanel__options li').on('click', function() {
        $('.filterPanel__options li').removeClass('active');
        $(this).addClass('active');
    });

    // floors
    $('.floorplan-tab').eq(0).fadeIn();

    $('.floorplan-viewer__footer li').eq(0).addClass('floorplan-viewer__footer__element--selected is-activated--categories');

    $('.floorplan-viewer__footer li').on('click', function() {
        var _this = $(this);
        var curTab = $('#floorplan-tab' + _this.data('tab')).eq(0);

        $('.floorplan-viewer__footer li').removeClass('floorplan-viewer__footer__element--selected is-activated--categories');
        _this.addClass('floorplan-viewer__footer__element--selected is-activated--categories');

        $('.floorplan-tab').fadeOut();

        setTimeout(function() {
            curTab.fadeIn();
        }, 400);
    });

    $('.planHotspot').on('click', function() {
        $('.wrapper-panel.floorplanPanel').removeClass('visible').addClass('hidden');
        $('.icon-ic_floorplan').removeClass('is-active');
    });

    // pagination on help panel
    $('.pagination__wrapper').on('click', function() {
        var _this = $(this);
        var curModal = $('#' + _this.data('tab')).eq(0);


        if (_this.hasClass('is-activated--categories')) {
            _this.removeClass('is-activated--categories');
            curModal.fadeOut();
        } else {
            $('.pagination__wrapper').removeClass('is-activated--categories');
            _this.addClass('is-activated--categories');
            $('.section-help').fadeOut();
            setTimeout(function() {
                curModal.fadeIn();
            }, 400);
        }

    });

    // krpano modes list
    $('.projection li').on('click', function() {
        $('.projection li').removeClass('selected');
        $(this).addClass('selected');
    });

    // custom checkbox
    $('.checkbox').on('click', function() {
        $(this).toggleClass('checked');
    });

    // search
    $('.searchPanel__filtered__title').on('click', function() {
        if ($('.chevron-mobile').hasClass('icon-ic_arrow_down')) {
            $('.chevron-mobile').removeClass('icon-ic_arrow_down').addClass('icon-ic_arrow_up');
            $('.searchPanel__wrapper-category').slideDown();
        } else {
            $('.searchPanel__wrapper-category').slideUp();
            $('.chevron-mobile').removeClass('icon-ic_arrow_up').addClass('icon-ic_arrow_down');
        }
    });



    // При клике на категории :: ПОИСК


    $('.js-icon').on('click', function() {
        // $(".searchPanel__button").css("display", "block")
        // $('.searchPanel__results').fadeIn();
        $('.searchPanel__results').removeAttr('style');


        if ($(this).hasClass('icon-wrapper__icon')) {
            $(this).removeClass('icon-wrapper__icon').addClass('icon-wrapper--selected');
        } else {
            $(this).removeClass('icon-wrapper--selected').addClass('icon-wrapper__icon');
        }


        var _this = $('.search-input');
        var categoryId =
            $('.js-icon.icon-wrapper--selected').map(function() {
                return $(this).data('category');
            }).get().join(",");

        if ($('.js-icon').hasClass('icon-wrapper--selected')) {
            if (_this.val() != "") {
                $.get(
                    "/ru/search/" + _this.val() + '/' + categoryId,
                    onAjaxSuccess
                );
            } else {

                $.get(
                    "/ru/search/noresult/" + categoryId,
                    onAjaxSuccess
                );

            }
        } else {
            if (_this.val() != "") {
                $.get(
                    "/ru/search/" + _this.val() + '/0',
                    onAjaxSuccess
                );
            }
        }
        //Дебильная функция
        function onAjaxSuccess(data) {
            if (data === 'Null') {


                $('#searchContainer').empty();

                $('.searchPanel__resultscontainer').hide();
                $('.searchPanel__results span').text('Объект не найден');
                $('.searchPanel__button').text('Объект не найден');
            } else {
                if (screen.width >= 500) {
                    $('.searchPanel__results span').text('Результаты: ' + data.length + '');
                } else {
                    $('.searchPanel__button').text('Результаты: ' + data.length + '');
                }

                var searchItem = "";
                var img = "";

                for (i = 0; i < data.length; i++) {
					xmlDoc =  $.parseXML(data[i].xmllocation.replace('/>','>') + '</view>');
                    $preview = $( xmlDoc ).find('preview');
                    var img = $preview.attr("url").replace('preview', 'thumb');


                    if (screen.width >= 1024) {
                        searchItem += `
                      <div class="listItem-wrapper" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + `')">
                      <div class="listItem">
                          <div class="listItem__img"><img src="` + img + `" class="listItem__img--scene"></div>
                          <div class="listItem__icon-category">
                              <div class="icon-wrapper__icon--category category-normal" style="` + data[i].color + `;"><img src="/storage/cat_icons/` + data[i].cat_icon_svg + `"></div>
                          </div>
                          <div class="listItem__text">
                          <div><span><span>` + data[i].name + `</span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                          </div>
                      </div>
                  </div>
                  `;

                    } else {
                        searchItem += `
                      <div class="listItem-wrapper" style="height: 208px; width: 186px;" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + `')">
                              <div class="listItem" style="width: 170px; height: 192px;">
                                <div class="listItem__img"><img src="` + img + `/" class="listItem__img--scene"></div>
                                <div class="listItem__icon-category">
                                  <div class="icon-wrapper__icon--category category-normal" style="background-color: ` + data[i].color + `;"><img src="/storage/cat_icons/` + data[i].cat_icon_svg + `"></div>
                                </div>
                      <div class="listItem__text">
                        <div><span><span>` + data[i].name + `</span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                      </div>
                              </div>
                            </div>
                  `;
                    }

                    if (screen.width >= 1024) {
                        $('#searchContainer').empty().append(searchItem);
                        $('.searchPanel__resultscontainer').show();
                    } else {
                        $('#searchContainerMobile').empty().append(searchItem);
                        $('.searchPanel__button').show();
                    }

                    if (screen.width <= 1024) {
                        $('#searchContainer').empty().append(searchItem);
                        $('.searchPanel__resultscontainer').show();
                    } else {
                        $('#searchContainerMobile').empty().append(searchItem);
                        $('.searchPanel__button').show();
                    }




                }




            }




        }
        //КОНЕЦ Дебильная функция




        if (_this.val() == '') {
            $('.searchPanel__results span').text('Объект не найден');
            $('.searchPanel__button').text('Объект не найден');

            $('#searchContainer').empty();

            $('.searchPanel__resultscontainer').hide();
        }
    });
    // КОНЕЦ При клике на категории :: ПОИСК


    // mobile search result panel
    $('.searchPanel__button').on('click', function() {
        $('.fullScreenPanel_search').fadeIn();
    });

    $('.search-close').on('click', function() {
        $('.fullScreenPanel_search').fadeOut();
    });

    $('.search-input').bind('input', function() {
        var _this = $(this);
        var categoryId =
            $('.js-icon.icon-wrapper--selected').map(function() {
                return $(this).data('category');
            }).get().join(",");

        if ($('.js-icon').hasClass('icon-wrapper--selected')) {
            if (_this.val() != "") {
                $.get(
                    "/ru/search/" + _this.val() + '/' + categoryId,
                    onAjaxSuccess
                );
            }
        } else {
            if (_this.val() != "") {
                $.get(
                    "/ru/search/" + _this.val() + '/0',
                    onAjaxSuccess
                );
            }
        }

        function onAjaxSuccess(data) {
            if (data === 'Null') {


                $('#searchContainer').empty();

                $('.searchPanel__resultscontainer').hide();
                $('.searchPanel__results span').text('Объект не найден');
                $('.searchPanel__button').text('Объект не найден');
            } else {
                if (screen.width <= 1024) {
                    $('.searchPanel__results span').text('Результат: ' + data.length + '');
                } else {
                    $('.searchPanel__button').text('Результаты: ' + data.length + '');
                }



                var searchItem = "";
                var img = "";

                for (i = 0; i < data.length; i++) {
					xmlDoc =  $.parseXML(data[i].xmllocation.replace('/>','>') + '</view>');
                    $preview = $( xmlDoc ).find('preview');
                    var img = $preview.attr("url").replace('preview', 'thumb');




                    if (screen.width >= 1024) {
                        searchItem += `
                      <div class="listItem-wrapper" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + ` ')">
                      <div class="listItem">
                          <div class="listItem__img"><img src="` + img + `" class="listItem__img--scene"></div>
                          <div class="listItem__icon-category">
                              <div class="icon-wrapper__icon--category category-normal" style="background-color: ` + data[i].color + `;"><img src="/storage/cat_icons/` + data[i].cat_icon_svg + `"></div>
                          </div>
                          <div class="listItem__text">
                          <div><span><span>` + data[i].name + `</span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                          </div>
                      </div>
                  </div>
                  `;


                    } else {
                        searchItem += `
                      <div class="listItem-wrapper" style="height: 208px; width: 186px;" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + `')">
                              <div class="listItem" style="width: 170px; height: 192px;">
                                <div class="listItem__img"><img src="` + img + `" class="listItem__img--scene"></div>
                                <div class="listItem__icon-category">
                                  <div class="icon-wrapper__icon--category category-normal" style="background-color: ` + data[i].color + `;"><img src="/storage/cat_icons/` + data[i].cat_icon_svg + `"></div>
                                </div>
                      <div class="listItem__text">
                        <div><span><span>` + data[i].name + `</span><span style="position: fixed; visibility: hidden; top: 0px; left: 0px;">…</span></span></div>
                      </div>
                              </div>
                            </div>
                  `;
                    }




                    if (screen.width >= 1024) {
                        $('#searchContainer').empty().append(searchItem);
                        $('.searchPanel__resultscontainer').show();
                    } else {
                        $('#searchContainerMobile').empty().append(searchItem);
                        $('.searchPanel__button').show();
                    }

                    //   if (screen.width <= 1024) {
                    //     $('#searchContainer').empty().append(searchItem);
                    //     $('.searchPanel__resultscontainer').show();
                    // } else {
                    //     $('#searchContainerMobile').empty().append(searchItem);
                    //     $('.searchPanel__button').show();
                    // }




                }




            }




        }

        if (_this.val() == '') {
            $('.searchPanel__results span').text('Объект не найден');
            $('.searchPanel__button').text('Объект не найден');

            $('#searchContainer').empty();

            $('.searchPanel__resultscontainer').hide();
        }
    });

    // sliders



    if (screen.width > 576) {
        $('.cotegory-slick').slick({
            dots: false,
            draggable: false,
            swipe: false,
            infinite: false,
            speed: 500,
            fade: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: $('.icon-ic_arrow_left_active_v2_first'),
            nextArrow: $('.icon-ic_arrow_right__active_v2_first'),
        });
    }

    $('.cotegory-slick_sec').slick({
        dots: false,
        draggable: false,
        swipe: false,
        infinite: false,
        speed: 500,
        fade: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $('.icon-ic_arrow_left_active_v2_sec'),
        nextArrow: $('.icon-ic_arrow_right__active_v2_sec'),
    });

    $('.slick-block').slick({
        dots: false,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        variableWidth: true,
        prevArrow: $('.icon-ic_arrow_left_active_v2_third'),
        nextArrow: $('.icon-ic_arrow_right__active_v2_third'),

    });

    $('.slick-block2').slick({
        dots: false,
        infinite: true,
        speed: 500,
  slidesToShow: 5,
        slidesToScroll: 1,
        variableWidth: true,
        prevArrow: $('.slick-block2_left'),
        nextArrow: $('.slick-block2_right'),
    });

    $('.slick-block3').slick({
        dots: false,
        infinite: true,
        speed: 500,
  slidesToShow: 5,
        slidesToScroll: 1,
        variableWidth: true,
        prevArrow: $('.slick-block3_left'),
        nextArrow: $('.slick-block3_right'),


    });

    $('.slick-block4').slick({
        dots: false,
        infinite: false,
        speed: 500,
        // slidesToShow: 3,
        slidesToScroll: 1,
        variableWidth: true,
        prevArrow: $('.slick-block4_left'),
        nextArrow: $('.slick-block4_right'),
    });

    $(".Pane1").resizable({
        handleSelector: ".resizer-block",
        resizeWidth: false
    });


    // ************* Timberland Style *********************
    $('.drop_down_city').click(function() {
        $('.drop_down_city .info_city').slideToggle();
    });


    // $('.drop_down_city select option').click(function(){
    //         var _this = $(this).text();
    //         var id = $(this).attr('id');
    //         var path = window.location.href;
    //         location.replace("http://dev2.uzbekistan360.uz/city/"+id+"")

    //         $('.drop_city span').text(_this);

    // });

    $('.drop_down_city select').on('change', function() {

        var id = $(this).children(":selected").attr("id");
        var lang = $(this).children(":selected").data('my-var');
        document.location.href = '/' + lang + '/city/' + id + '';
    });

    $('.language-switcher select').on('change', function() {
      var id = $(this).children(":selected").attr("id");
      var lang = $(this).children(":selected").data('my-var');
var pathname = window.location.pathname;
if (pathname.indexOf("/ru") >= 0) {
  pathname =  pathname.replace("/ru", id);
  document.location.href = '/' + pathname;
}
if (pathname.indexOf("/uzb") >= 0) {
pathname =  pathname.replace("/uzb", id);
  document.location.href = '/' + pathname;
}
if (pathname.indexOf("/en") >= 0) {
pathname =  pathname.replace("/en", id);
  document.location.href = '/' + pathname;
}



    });
    // ****************************************************




    // ********************* CLOSE SEARCH BLOCK ******************************************

    $('body').on('click', '.listItem-wrapper', function() {

        setTimeout(function() {
            $('.wrapper-panel-close').trigger('click')
        }, 1000)
    });



    $('.listItem-wrapper').on('click', function() {
        var _this = $(this);
        var curModal = $('.' + _this.data('pannel')).eq(0);



        if (_this.hasClass('is-active')) {
            _this.removeClass('is-active');
            curModal.removeClass('visible').addClass('hidden');

        } else {
            $('.wrapper-button__icon').removeClass('is-active');
            _this.addClass('is-active');
            $('.wrapper-panel').removeClass('visible').addClass('hidden');
            curModal.removeClass('hidden').addClass('visible');
        }

    });




    // **********************************************************************************

});
