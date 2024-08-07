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

    $('.wrapper-button__icon').on('click', function() {
        var _this = $(this);
        var curModal = $('.' + _this.data('pannel')).eq(0);

        if (_this.hasClass('is-active')) {
            _this.removeClass('is-active');
            curModal.removeClass('visible').addClass('hidden');
            if (_this.find('.wrappersvg').length !== 0) {
                _this.find('.wrappersvg').attr("fill", "#1a90d2");
            }
        } else {
            $('.wrapper-button__icon').removeClass('is-active');
            _this.addClass('is-active');
            $('.wrapper-panel').removeClass('visible').addClass('hidden');
            curModal.removeClass('hidden').addClass('visible');

            if (_this.find('.wrappersvg').length !== 0) {
                _this.find('.wrappersvg').attr("fill", "#c23691");
            }
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

    $('.svg_blockk').click(function() {
        var height = $('#location_description').height();
        $('.infoPanel__description__message').toggleClass('show');


        if ($('.infoPanel__description__message').hasClass('show')) {
            $('.infoPanel__description__message').css('height', height);
        }else{
            $('.infoPanel__description__message').removeAttr('style');
        }
    });

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
            // $('.searchPanel__input').trigger('click');
        });
    }

    $('.icon-ic_glass').click(function() {
        $('.searchPanel__results').css("display", "none");
    });

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

    // При клике на категории :: ПОИСК

    $('.js-icon').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $('.searchPanel__results').removeAttr('style');

        $('.js-icon').not($(this)).removeClass('icon-wrapper--selected').addClass('icon-wrapper__icon');

        if ($(this).hasClass('icon-wrapper__icon')) {
            $(this).removeClass('icon-wrapper__icon');
            $(this).addClass('icon-wrapper--selected');
        } else {
            $(this).addClass('icon-wrapper__icon');
            $(this).removeClass('icon-wrapper--selected');
            $('.searchPanel__results').hide();
        }

        var _this = $('.search-input');
        var this_lang =  $('.language-switcher select').val();
        var categoryId =
            $('.js-icon.icon-wrapper--selected').map(function() {
                return $(this).data('category');
            }).get().join(",");

        if ($('.js-icon').hasClass('icon-wrapper--selected')) {
            if (_this.val() != "") {
                _this.val('');
            } else {
                $.get(
                    "/"+this_lang+"/search/noresult/" + categoryId,
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
                if (screen.width >= 500) {
                    $('.searchPanel__results span').text('Результаты: ' + data.length + '');
                } else {
                    $('.searchPanel__button').text('Результаты: ' + data.length + '');
                }

                var searchItem = "";
                var img = "";

                for (i = 0; i < data.length; i++) {
                    if (data[i].video == null) {
                        xmlDoc =  $.parseXML(data[i].xmllocation.replace('/>','>') + '</view>');
                        $preview = $( xmlDoc ).find('preview');
                        var img = $preview.attr("url").replace('preview', 'thumb');
                    } else {
                        var img = '/storage/panoramas/preview/' + data[i].preview;
                    }

                    let videoFile = data[i].video ? ("'" + data[i].video + "'") : 'null';
                    if (screen.width >= 1024) {
                        searchItem += `
                      <div class="listItem-wrapper" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + `', null, null, null, ` + videoFile + `)">
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
                      <div class="listItem-wrapper" style="height: 208px; width: 186px;" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + `', null, null, null, ` + videoFile + `)">
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

        if ($('.search-input').val() == '') {
            $('.searchPanel__results span').text('Объект не найден');
            $('.searchPanel__button').text('Объект не найден');

            $('#searchContainer').empty();

            $('.searchPanel__resultscontainer').hide();
        }

        $('.category-name').hide();
        $('.category-information').hide();

        let link = $(this).find('a');
        let categoryWrapper = $(this).find('.icon-wrapper__text');
        if (link.length) {
            history.pushState({
                id: 'category'
            }, categoryWrapper.data('title'), link.attr('href'));
        }
        let categoryTitle = '';
        let categoryInformation = '';
        categoryTitle = categoryWrapper.data('title');
        categoryInformation = categoryWrapper.data('information');
        document.title = categoryTitle;
        $('.category-name').html('<h1>' + categoryTitle + '</h1>').show();
        if (categoryInformation) {
            $('.category-information').html('<p>' + categoryInformation + '</p>').show();
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
        var this_lang =  $('.language-switcher select').val();


        if (_this.val() != '') {
            $('.clear-field').show();
        } else {
            $('.clear-field').hide();
        }
        var categoryId =
            $('.js-icon.icon-wrapper--selected').map(function() {
                return $(this).data('category');
            }).get().join(",");

        if ($('.js-icon').hasClass('icon-wrapper--selected')) {
            $('.js-icon').addClass('icon-wrapper__icon').removeClass('icon-wrapper--selected');
        }
        if (_this.val() != "" && _this.val().length >= 3) {
            $.get(
              "/" + this_lang + "/search/" + _this.val() + '/0',
                onAjaxSuccess
            );
        }

        function onAjaxSuccess(data) {
            if (data === 'Null') {
                $('#searchContainer').empty();

                $('.searchPanel__resultscontainer').hide();
                $('.searchPanel__results span').text('Объект не найден');
                $('.searchPanel__button').html('Объект не найден');
            } else {
                // if (screen.width <= 1024) {
                //     $('.searchPanel__results span').text('Результат: ' + data.length + '');
                // } else {
                    $('.searchPanel__button').html('Результаты: ' + data.length + '');
                // }

                var searchItem = "";
                var img = "";

                for (i = 0; i < data.length; i++) {
                    if (data[i].video == null) {
                        xmlDoc = $.parseXML(data[i].xmllocation.replace('/>', '>') + '</view>');
                        $preview = $(xmlDoc).find('preview');
                        var img = $preview.attr("url").replace('preview', 'thumb');
                    } else {
                        var img = '/storage/panoramas/preview/' + data[i].preview;
                    }

                    let videoFile = data[i].video ? ("'" + data[i].video + "'") : 'null';
                    if (screen.width >= 1024) {
                        searchItem += `
                      <div class="listItem-wrapper" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + ` ', null, null, null, ` + videoFile +`)">
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
                      <div class="listItem-wrapper" style="height: 208px; width: 186px;" onclick="loadpano('uzbekistan:` + data[i].id + `', ` + i + `, '` + data[i].slug + ` ', null, null, null, ` + videoFile + `)">
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
            nextArrow: $('.icon-ic_arrow_right__active_v2_first')
        });

        $('.cotegory-slick').each(function() {
            var $this = $(this);
            if ($this.find('.category-wrapper--category').length < 2) {
                // $this.slick({
                // });
                $('.searchPanel .btn_slider_slick').hide();
            }
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

    var otherLocationsSlider = $('.slick-block');
    otherLocationsSlider.slick({
        dots: false,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        variableWidth: true,
        prevArrow: $('.icon-ic_arrow_left_active_v2_third'),
        nextArrow: $('.icon-ic_arrow_right__active_v2_third'),

    });

    otherLocationsSlider.on('wheel', (function(e) {
      e.preventDefault();

      if (e.originalEvent.deltaY < 0) {
        $(this).slick('slickNext');
      } else {
        $(this).slick('slickPrev');
      }
    }));

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

        /**
         * Редирект языков
         * @param path
         */
        function redirectLang (path)
        {
            pathname = '/' + path;
            // Если эта главная страница то убираем ru для русской версии
            pathname = pathname.replace(/^\/ru\/$/mg, '/');
            document.location.href = pathname;
        }

        if (pathname == '/') {
            pathname =  pathname.replace("/", id + '/');
            redirectLang(pathname);
        }
        if (pathname.indexOf("/ru/") >= 0) {
            pathname =  pathname.replace("/ru/", id + '/');
            redirectLang(pathname);
        }
        if (pathname.indexOf("/es/") >= 0) {
            pathname =  pathname.replace("/es/", id + '/');
            redirectLang(pathname);
        }
        if (pathname.indexOf("/tr/") >= 0) {
            pathname =  pathname.replace("/tr/", id + '/');
            redirectLang(pathname);
        }
        if (pathname.indexOf("/en/") >= 0) {
            pathname =  pathname.replace("/en/",  id + '/');
            redirectLang(pathname);
        }
    });

    // ********************* CLOSE SEARCH BLOCK ******************************************

    $('body').on('click', '.listItem-wrapper', function() {
        setTimeout(function() {
            $('.wrapper-panel-close').trigger('click')
        }, 1000)
    });

    $('.listItem-wrapper').on('click', function() {
        var _this = $(this);
        var curModal = $('.' + _this.data('pannel')).eq(0);

        $('.listItem-wrapper').removeClass('is-active');
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
});
