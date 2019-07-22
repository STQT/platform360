@extends('layouts.admin.krpano')

@section('content')
    <div id="adminModal" style="display: none;" class="modal">
        <div class="overlay"></div>
        <div class="modal-wrap">
            <span class="modal-close">x</span>
            <div class="categories">
                <ul class="category-list">
                    @foreach($categories as $category)
                        <li>
                            <a class="category-li" data-category="{{ $category->id }}"
                               href="#">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="cotegory-info">
                <div class="mess">
                    <img style="display: none;" class="mess_img" src="/skin/preloader.gif" alt="">
                    <h1 class="mess_title">Выберите хотя-бы одну категорию</h1>
                    <h1 style="display: none;" class="mess_not_found">Локации не найдены</h1>
                </div>

                <div class="axmad4ik" style="display: none;">
                    <input class="info-search" type="search" placeholder="Поиск...">

                    <ul class="info-list">

                    </ul>

                    <ul class="info-pagination">

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <button id="addHotspot" onclick="add_hotspot();">Добавить точку</button>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>
        var hcoordinate;
        var vcoordinate;

        $(function () {
            $('body').on('click', '.locationItem', function () {
                var _this = $(this);

                $.post('/api/skyhotspot/add', { location: "{{ $location->id }}", src: _this.data('location'), index: _this.data('index'), h: hcoordinate, v: vcoordinate }).done(function() {
                    $('.modal').fadeOut();
                    alert('Точка установлена');
                }).fail(function() {
                    alert('Ошибка установки точки');
                });
            });


            $('.category-li').click(function () {
                var _this = $(this);

                $('.mess_title').fadeOut('slow');

                $('.mess_not_found').fadeOut('slow');

                $('.info-list').html('');

                $('.axmad4ik').fadeOut();

                setTimeout(function () {
                    $('.mess_img').fadeIn();
                }, 700);

                $.get('/api/locations/' + _this.data('category')).done(function (data) {
                    setTimeout(function () {
                        $('.mess_img').fadeOut('slow');

                    }, 700);

                    setTimeout(function () {
                        if (!data.data.length) {
                            setTimeout(function () {
                                $('.mess_not_found').fadeIn();
                            }, 800);

                            return;
                        }

                        setTimeout(function () {
                            $('.mess_not_found').fadeOut();

                            for (var i = 0; i < data.data.length; i++) {
                                var panos = JSON.parse(data.data[i].panorama);

                                if(panos.length == 1) {
                                    $('.info-list').append('<li><a class="locationItem" data-location="' + data.data[i].id + '" href="#none">' + data.data[i].name + '</a></li>');
                                }
                                else {
                                    for (var floorIndex = 0; floorIndex < panos.length; floorIndex++) {
                                        $('.info-list').append('<li><a class="locationItem" data-index="' + floorIndex +'" data-location="' + data.data[i].id + '" href="#none">' + data.data[i].name + '(' + panos[i].name + ')' + '</a></li>');
                                    }
                                }
                            }

                            $('.axmad4ik').fadeIn();
                        }, 700);
                    }, 700);
                }).fail(function () {
                    alert('Ошибка загрузки информации, пожалуйста попробуйте снова.')
                });
            });
        })
    </script>

    <script>
        $(function () {
            $('.modal-close').on('click', function () {
                $('.modal').fadeOut();
            });
        });
    </script>

    <script>
        var krpano = null;

        function krpano_onready_callback(krpano_interface) {
            krpano = krpano_interface;

            setTimeout(function() {
                @foreach($location->hotspots as $hotspot)
                    add_exist_hotspot({{ $hotspot->h }}, {{ $hotspot->v }});
                @endforeach
            }, 3000);
        }

        function add_exist_hotspot(h, v) {
            if (krpano) {
                var hs_name = "hs" + ((Date.now() + Math.random()) | 0);

                krpano.call("addhotspot(" + hs_name + ")");
                krpano.set("hotspot[" + hs_name + "].url", "/skin/vtourskin_hotspot.png");
                krpano.set("hotspot[" + hs_name + "].ath", h);
                krpano.set("hotspot[" + hs_name + "].atv", v);
                krpano.set("hotspot[" + hs_name + "].distorted", true);

                if (krpano.get("device.html5")) {
                    // for HTML5 it's possible to assign JS functions directly to krpano events
                    krpano.set("hotspot[" + hs_name + "].onclick", function (hs) {
                        hcoordinate = h;
                        vcoordinate = v;
                        $('#adminModal').fadeIn();
                    }.bind(null, hs_name));
                }
            }
        }

        function loadpano(xmlname)
        {
            if (krpano)
            {
                krpano.call("loadpano(" + xmlname + ", null, MERGE, BLEND(0.5));");
            }
        }

        function add_hotspot() {
         $('body').dblclick(function() {
            if (krpano) {
            var mx = krpano.get("mouse.x");
    var my = krpano.get("mouse.y");
    var pt = krpano.screentosphere(mx, my);

                var hs_name = "hs" + ((Date.now() + Math.random()) | 0);    // create unique/randome name
                krpano.call("addhotspot(" + hs_name + ")");
                krpano.set("hotspot[" + hs_name + "].url", "/skin/vtourskin_hotspot.png");
                krpano.set("hotspot[" + hs_name + "].ath", pt.x);
                krpano.set("hotspot[" + hs_name + "].atv", pt.y);
                krpano.set("hotspot[" + hs_name + "].distorted", true);

                if (krpano.get("device.html5")) {
                    // for HTML5 it's possible to assign JS functions directly to krpano events
                    krpano.set("hotspot[" + hs_name + "].onclick", function (hs) {
                        hcoordinate = pt.x;
                        vcoordinate = pt.y;
                        $('#adminModal').fadeIn();
                    }.bind(null, hs_name));
                }
            }
        });}
    </script>

    <script>
        embedpano({target: "pano", id: "pano1", xml: "/admin/skykrpano/{{ $location->id }}", onready: krpano_onready_callback});
    </script>
@endsection
