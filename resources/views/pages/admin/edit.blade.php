@extends('layouts.admin.krpano')

@section('content')
<div id="adminModal" style="display: none;" class="modal">
    <div class="overlay"></div>
    <div class="modal-wrap">
        <span class="modal-close">x</span>

        <div class="categories">          <div id="deletehotspot" onclick="deletehotspot()" data-id="" style="border:1px solid red;color:red;text-align:center;height:25px;margin-bottom:10px;cursor:pointer">Удалить точку</div>
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
                <h1 class="mess_title">Выберите хотя бы одну категорию</h1>
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

<div id="informationModal" style="display: none;" class="modal">
    <div class="overlay"></div>
    <div class="modal-wrap">
        <span class="modal-close">x</span>
        <form id="information-form">
            <div class="row">
                <div class="form-group">
                    <a href="{{ url('/admin/locations/ru/' . $location->id) }}" ><button class="lang btn btn-success btn-sm {{ Lang::locale() == 'ru' ? 'current' : '' }}" type="button">Русский</button></a>

                    <a href="{{ url('/admin/locations/uzb/' . $location->id) }}" ><button class="lang btn btn-success btn-sm {{ Lang::locale() == 'uzb' ? 'current' : '' }}" type="button">Узбекский</button></a>
                    <a href="{{ url('/admin/locations/en/' . $location->id) }}" ><button class="lang btn btn-info btn-sm {{ Lang::locale() == 'en' ? 'current' : '' }}" type="button">Английский</button></a>

{{--                    <button data-lang="ru" class="lang btn btn-success btn-sm {{ Lang::locale() == 'ru' ? 'current' : '' }}" type="button" value="Русский">Русский</button>--}}

{{--                    <button data-lang="uzb" class="lang btn btn-success btn-sm {{ Lang::locale() == 'uzb' ? 'current' : '' }}" type="button" value="Узбекский">Узбекский</button>--}}
{{--                    <button data-lang="en" class="lang btn btn-info btn-sm {{ Lang::locale() == 'en' ? 'current' : '' }}" type="button" value="Английский">Английский</button>--}}
                </div>
            </div>

            <input type="hidden" class="hidden-create" name="create" value="">

            <input type="hidden" class="hotspotid" name="hotspotid" value="">

            <div class="row">
                <div class="form-group">
                    <h4>Информация</h4>
                    <textarea name="information" id="information" cols="30" rows="10"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label>Файл<input type="file" name="image"></label>
            </div>
            <div class="form-group">
                <img src="" alt="" style="display: none" class="preview">
            </div>

            <input type="hidden" name="hidden_lang" class="hidden-input-lang" value="">

            <div id="deleteinformation" onclick="deleteinformation()" data-id="" style="border:1px solid red;color:red;text-align:center;height:25px;margin-bottom:10px;cursor:pointer">Удалить точку</div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<div id="videoModal" style="display: none;" class="modal">
    <div class="overlay"></div>
    <div class="modal-wrap">
        <span class="modal-close">x</span>
        <div>
            <form method="post" enctype="multipart/form-data" id="upload-video">
                <div class="form-group">
                    <a href="{{ url('/admin/locations/ru/' . $location->id) }}" ><button class="lang btn btn-success btn-sm {{ Lang::locale() == 'ru' ? 'current' : '' }}" type="button">Русский</button></a>

                    <a href="{{ url('/admin/locations/uzb/' . $location->id) }}" ><button class="lang btn btn-success btn-sm {{ Lang::locale() == 'uzb' ? 'current' : '' }}" type="button">Узбекский</button></a>
                    <a href="{{ url('/admin/locations/en/' . $location->id) }}" ><button class="lang btn btn-info btn-sm {{ Lang::locale() == 'en' ? 'current' : '' }}" type="button">Английский</button></a>
                </div>

                <br>
                {{ csrf_field() }}
                <div class="form-group">Видео: <input type="file" name="video"></div>
                
                <div class="form-group">hfov: <input type="text" name="hfov"></div>
                
                <div class="form-group">yaw: <input type="text" name="yaw"></div>
                
                <div class="form-group">pitch: <input type="text" name="pitch"></div>
                
                <div class="form-group">roll: <input type="text" name="roll"></div>

                <select name="play_type">
                    @foreach (\App\Video::playTypeOptions() as $vKey => $option)
                        <option value="{{ $vKey }}">{{ $option }}</option>
                    @endforeach
                </select>

                <input type="hidden" name="location" value={{ $location->id }} >
                
                <div><button type="submit" class="btn btn-primary">Добавить</button></div>
                
            </form>
        </div>
    </div>
</div>

<button id="addHotspot" onclick="add_hotspot();">Добавить точку</button>
<button id="addVideo" onclick="add_video();">Добавить видео</button>
<button id="addInformation" onclick="add_information_hotspot();">Добавить информацию</button>
<a id="adminbackurl" href="{{ url()->previous() }}">Назад</a>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>
    var hcoordinate;
    var vcoordinate;
    var hotspotid;
    var hotspot_type = {{ \App\Hotspot::TYPE_MARKER }};
    var hotspotname;
    var selectedCategory = null;
    $(function () {
        $('body').on('click', '.locationItem', function () {
            var _this = $(this);

            $.post('/ru/api/locations/add', { location: "{{ $location->id }}", src: _this.data('location'), index: _this.data('index'), h: hcoordinate, v: vcoordinate }).done(function() {
                $('.modal').fadeOut();
                alert('Точка установлена');
            }).fail(function() {
                alert('Ошибка установки точки');
            });
        });

        $('body').on('submit', 'form#information-form', function (e) {
            e.preventDefault();
            var data = new FormData(this);
            var formtest = $('#information-form');
            console.log(this);
            console.log(formtest);
            data.append('location', "{{ $location->id }}");
            data.append('h', hcoordinate);
            data.append('v', vcoordinate);
            $.ajax({
                url: '/ru/api/locations/add-information/{{ $language }}',
                method: 'POST',
                data: data,
                // dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data)
                {
                    $('#informationModal').fadeOut();
                }
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

            selectedCategory = _this.data('category');
            getLocations(selectedCategory);
        });

        $('.lang').click(function () {
            var locale = $(this).attr('data-lang');
            var hiddenInput = $('.hidden-input-lang');
            console.log('locale: ', locale);
            console.log('hiddenInput: ', hiddenInput);
            hiddenInput.val(locale);
        })
    });

    function getLocations(category, query) {
        if (typeof query == 'undefined')
            query = '';
        $.get('/ru/api/locations/' + category + (query ? ('?query=' + query) : '')).done(function (data) {
            setTimeout(function () {
                $('.mess_img').fadeOut('slow');
            }, 700);

            setTimeout(function () {
                if (!data.data.length) {
                    $('.info-list').html('');

                    setTimeout(function () {
                        $('.mess_not_found').fadeIn();
                    }, 800);

                    return;
                }

                setTimeout(function () {
                    $('.mess_not_found').fadeOut();

                    $('.info-list').html('');

                    for (var i = 0; i < data.data.length; i++) {
                        var panos = JSON.parse(data.data[i].panorama);
                        var panosvideo = data.data[i].video;
                        if(panos && panos.length == 1) {

                            if (data.data[i].podlocparent_id == null) {
                                xmlDoc =  $.parseXML(data.data[i].xmllocation.replace('/>','>') + '</view>');
                                $preview = $( xmlDoc ).find('preview');

                                var parentId = data.data[i].id;
                                $('.info-list').append('<li data-id="' + data.data[i].id + '"><a class="locationItem" data-location="' + data.data[i].id + '" href="#none"><img src="'+$preview.attr("url").replace('preview', 'thumb')+'" width="150">' + data.data[i].name + '</a><a class="expand-subcategories"><img src="/images/admin/expand.png"></a><ul class="' + (parentId != {{ $location->id }} ? 'hidden' : '') + '"></ul></li>');

                                $.get('/ru/api/sublocations/' + data.data[i].id).done(function (data) {
                                 for (var i = 0; i < data.data.length; i++) {
                                     var panos = JSON.parse(data.data[i].panorama);

                                     if(panos.length == 1) {
                                         xmlDoc =  $.parseXML(data.data[i].xmllocation.replace('/>','>') + '</view>');
                                         $preview = $( xmlDoc ).find('preview');
                                                $('.info-list li[data-id='+data.data[i].podlocparent_id+'] ul').append('<li><a class="locationItem" data-location="' + data.data[i].id + '" href="#none"><img src="'+$preview.attr("url").replace('preview', 'thumb')+'" width="150">' + data.data[i].name.ru + '</a></li>');
                                            }
                                        }
                                    });
                            }
                        }
                        else {
                            if (panosvideo.length >= 1) {

                                if (data.data[i].podlocparent_id == null) {
                                    $preview = data.data[i].preview;
                                    var parentId = data.data[i].id;
                                    $('.info-list').append('<li data-id="' + data.data[i].id + '"><a class="locationItem" data-location="' + data.data[i].id + '" href="#none"><img src="'+ '/storage/panoramas/preview/' + $preview+'" width="150">' + data.data[i].name + '</a><a class="expand-subcategories"><img src="/images/admin/expand.png"></a><ul class="' + (parentId != {{ $location->id }} ? 'hidden' : '') + '"></ul></li>');
                                }
                            } else {
                                for (var floorIndex = 0; floorIndex < panos.length; floorIndex++) {
                                    $('.info-list').append('<li><a class="locationItem" data-index="' + floorIndex +'" data-location="' + data.data[i].id + '" href="#none">' + data.data[i].name + '(' + panos[i].name + ')' + '</a></li>');
                                }
                            }
                        }
                    }

                    $('.axmad4ik').fadeIn();
                }, 700);
            }, 700);
        }).fail(function () {
            alert('Ошибка загрузки информации, пожалуйста попробуйте снова.')
        });
    }

    $('.info-search').on('keyup', function() {
        getLocations(selectedCategory, $(this).val());
    });

    $(function () {
        $('.modal-close').on('click', function () {
            $('.modal').fadeOut();
        });
    });

    var krpano = null;

    function krpano_onready_callback(krpano_interface) {
        krpano = krpano_interface;

        setTimeout(function() {
            @foreach($location->hotspots as $hotspot)
            add_exist_hotspot(
                    {{ $hotspot->h }},
                    {{ $hotspot->v }},
                    {{$hotspot->id}},
                    {{$hotspot->type ? $hotspot->type : \App\Hotspot::TYPE_MARKER}},
                    "{{ str_replace("\r", "\\\r", $hotspot->information) }}",
                    "{{ $hotspot->image}}"
            );
            @endforeach
        }, 3000);
    }

    function add_exist_hotspot(h, v, id, type, information, image) {
        if (krpano) {
            var hs_name = "hs" + ((Date.now() + Math.random()) | 0);

            krpano.call("addhotspot(" + hs_name + ")");
            if (type == {{ \App\Hotspot::TYPE_INFORMATION }}) {
                krpano.set("hotspot[" + hs_name + "].url", "/skin/vtourskin_mapspot.png");
            } else {
                krpano.set("hotspot[" + hs_name + "].url", "/skin/vtourskin_hotspot.png");
            }
            krpano.set("hotspot[" + hs_name + "].ath", h);
            krpano.set("hotspot[" + hs_name + "].atv", v);
            krpano.set("hotspot[" + hs_name + "].type", type);
            krpano.set("hotspot[" + hs_name + "].information", information);
            krpano.set("hotspot[" + hs_name + "].image", image);
            krpano.set("hotspot[" + hs_name + "].distorted", true);

            if (krpano.get("device.html5")) {
                    // for HTML5 it's possible to assign JS functions directly to krpano events
                    krpano.set("hotspot[" + hs_name + "].onclick", function (hs) {
                      hotspotid =  id;
                      hotspotname =  hs_name;
                      hcoordinate = h;
                      vcoordinate = v;
                      if (type == {{ \App\Hotspot::TYPE_INFORMATION }}) {
                          $('#information-form textarea').val(information);
                          $('.hotspotid').val(hotspotid);

                          if (image.length > 0) {
                              $('.preview').attr('src', '/storage/information/' + image);
                              $('.preview').show();
                          }
                          $('#informationModal').fadeIn();
                      } else {
                          $('#adminModal').fadeIn();
                      }
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
        function deletehotspot() {
            krpano.call("removehotspot("+hotspotname+")");
            if(hotspotid  != "new") {

              $.get('/ru/api/deletehotspot/' + hotspotid).done(function (data) {
                $('.modal').fadeOut();
                alert('Удалили точку: '+ hotspotid);
            });
          } else {

              $('.modal').fadeOut();
              alert('Удалили точку');
          }
      }

        function deleteinformation() {
            krpano.call("removehotspot(" + hotspotname + ")");
            if (hotspotid != "new") {

                $.get('/ru/api/deleteinformation/' + hotspotid).done(function (data) {
                    $('.modal').fadeOut();
                    alert('Удалили точку: ' + hotspotid);
                });
            } else {

                $('.modal').fadeOut();
                alert('Удалили точку');
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
                      hotspotid =  'new';
                      hotspotname =  hs_name;
                      hcoordinate = pt.x;
                      vcoordinate = pt.y;
                      $('#adminModal').fadeIn();
                  }.bind(null, hs_name));
                }
            }
        });
    }

    function add_information_hotspot() {
        var create = $('.hidden-create');
        create.val(true);
         $('body').dblclick(function() {
            if (krpano) {
                var mx = krpano.get("mouse.x");
                var my = krpano.get("mouse.y");
                var pt = krpano.screentosphere(mx, my);

                var hs_name = "hs" + ((Date.now() + Math.random()) | 0);    // create unique/randome name
                krpano.call("addhotspot(" + hs_name + ")");
                krpano.set("hotspot[" + hs_name + "].url", "/skin/vtourskin_mapspot.png");
                krpano.set("hotspot[" + hs_name + "].ath", pt.x);
                krpano.set("hotspot[" + hs_name + "].atv", pt.y);
                krpano.set("hotspot[" + hs_name + "].distorted", true);

                if (krpano.get("device.html5")) {
                    // for HTML5 it's possible to assign JS functions directly to krpano events
                    krpano.set("hotspot[" + hs_name + "].onclick", function (hs) {
                      hotspotid =  'new';
                      hotspot_type = {{ \App\Hotspot::TYPE_INFORMATION }};
                      hotspotname =  hs_name;
                      hcoordinate = pt.x;
                      vcoordinate = pt.y;
                      $('#informationModal').fadeIn();
                  }.bind(null, hs_name));
                }
            }
        });
    }

    function add_video() {
        $('#videoModal').fadeIn();
    }

    embedpano({target: "pano", id: "pano1", xml: "/admin/krpano/{{ $location->id }}", onready: krpano_onready_callback});

    $(document).ready(function() {
        $('body').on('click', '.expand-subcategories', function(e) {
            e.preventDefault();
            $('.info-list li ul').hide();
            $(this).closest('li').find('ul').slideDown('slow');
        });


        $('form#upload-video').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/ru/api/locations/upload-video/{{ $language }}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data)
                {
                    $('#videoModal').fadeOut();
                }
            });
        });
    });
</script>

    <style>
        .cotegory-info {
            overflow: scroll;
        }
        .info-list li {
            position: relative;
        }
        .hidden {
            display: none;
        }
        .expand-subcategories {
            position: absolute;
            right: 0;
            top: -3px;
        }

        .info-list a.expand-subcategories img {
            height: 27px;
        }

        #information-form {
            color: #000;
        }

        #information-form img {
            width: 120px;
        }
    </style>
@endsection
