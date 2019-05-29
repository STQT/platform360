@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Edit Location #{{ $location->id }}</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/locations') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($location, [
                            'method' => 'PATCH',
                            'url' => ['/admin/locations', $location->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('admin.locations.form', ['formMode' => 'edit'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#isFloor').on('change', function() {
                var _this = $(this);

                if(_this.is(':checked')) {
                    $('.has').show();
                    $('.hasNo').hide();
                }
                else {
                    $('.hasNo').show();
                    $('.has').hide();
                }
            });


            // Этажи

            var floorIndex = $('.panorama-wrap').last().find('.x').attr('name');
            floorIndex = floorIndex.split('][')[0];
            floorIndex = floorIndex.split('[')[1];

            var floorWrapVal = `
                <div class="floor-wrap">
                    <hr>

                    <div class="form-group">
                        <label class="control-label">Название</label>
                        <input type="text" class="form-control" name="floor_name[]">
                    </div>

                    <div class="form-group">
                        <label class="control-label plan_label">План</label>
                        <input id="planId" type="file" class="form-control plan_input myinput" name="floor_plan[]">
                    </div>


                    <div class="panorama-wrapper">
                    </div>

                    <button class="deleteFloor btn btn-danger mb-3" type="button">Удалить</button>
                    <br>
                </div>
             `;

            $('#addFloor').on('click', function(e){ // добавить этаж
                e.preventDefault();
                $('#addFloor').before('<div class="floor-wrap">' + floorWrapVal + '</div>');
                floorIndex = +floorIndex + 1;
            });

            $('body').on('click', '.deleteFloor', function (e) { // удалить этаж
                e.preventDefault();
                $(this).parent().remove();
            });


            // Панорамы

            $('body').on('click', '.addPanorama', function(e){ // добавить панораму
                e.preventDefault();

                var panoramaWrapVal = `
                    <div class="form-group panorama-wrap">
                        <label class="control-label panorama_label">Панорама</label>
                        <input required type="file" class="form-control panorama_input" name="floor_panorama[` + floorIndex + `][]">
                        <input type="hidden" name="x[` + floorIndex + `][]" class="x">
                        <input type="hidden" name="y[` + floorIndex + `][]" class="y">
                    </div>
                 `;

                $(this).before('<div class="panorama-wrap">' + panoramaWrapVal + '</div>');
            });


            // Модальное окно

            $('body').on('change', '.plan_input', function () { // вставка плана на модальное окно
                readURL(this);
            });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.planClass, .planHotspot').remove();

                        $('.modal .plan').append(`
                            <img class="planClass" style="border: 1px dotted black;" src="` + e.target.result + `">
                        `);

                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('body').on('change', '.plan_input.myinput', function (e) { // добавление полей для панорамы после загрузки плана
                var addPanoramaVal = `
                    <div class="form-group panorama-wrap">
                        <label class="control-label">Панорама</label>
                        <input required type="file" class="form-control panorama_input" name="floor_panorama[` + floorIndex + `][]">
                        <input type="hidden" name="x[` + floorIndex + `][]" class="x">
                        <input type="hidden" name="y[` + floorIndex + `][]" class="y">
                    </div>

                    <button class="btn btn-secondary d-block ml-auto addPanorama" type="button">Добавить понораму</button>
                `;
                $(this).parents('.floor-wrap').find('.panorama-wrapper').html(addPanoramaVal);
            });

            $('body').on('click', '.panorama_label', function() { // открытие модального окна при клике на label
                var _this = $(this);

                var dataVar = _this.parents('.floor-wrap').find('.plan_label').data('plan');

                if(dataVar) {
                    $('.planClass, .planHotspot').remove();
                    $('.modal .plan').append(`
                        <img class="planClass" style="border: 1px dotted black;" src="/storage/` + dataVar + `">
                    `);

                    $('button[data-target="#exampleModalLong"]').click();
                }
            });


            var cur_input;

            $('body').on('change', '.panorama_input', function () { // открытие модального окна при загрузке панорамы
                var _this = $(this);
                cur_input = _this;

                if( document.getElementById("planId").files.length == 0 ){

                    var dataVar = _this.parents('.floor-wrap').find('.plan_label').data('plan');

                    if(dataVar) {
                        $('.planClass, .planHotspot').remove();
                        $('.modal .plan').append(`
                        <img class="planClass" style="border: 1px dotted black;" src="/storage/` + dataVar + `">
                    `);

                        $('button[data-target="#exampleModalLong"]').click();
                    }
                }

                if( this.files.length != 0 ){
                    $('button[data-target="#exampleModalLong"]').click();
                }
            });

            var bool = false;

            $('body').on('click', '#addPlanHotspot', function (e) { // кнопка добавить точку
                e.preventDefault();
                alert('Нажмите на нужное место в картинке, чтобы добавить точку');
                bool = true;
            });

            $('body').on('click', '#deletePlanHotspots', function (e) { // кнопка удалить точку
                e.preventDefault();

                $('.planHotspot').remove();
            });

            $('body').on('click', '.planClass', function (e) { // добавление точки
                if (bool) {
                    var offset = $(this).offset();

                    if (($(".modal .plan .planHotspot").length == 0)){
                        $('.modal .plan').append(`
                            <span class="planHotspot"></span>
                        `);
                    }

                    addPlanHotspot(e.pageX - offset.left, e.pageY - offset.top);

                    bool = false;
                }
            });
            function addPlanHotspot(x_pos, y_pos) {
                var d = $('.planHotspot').get(0);
                d.style.position = "absolute";
                d.style.left = (x_pos - 10) + 'px';
                d.style.top = (y_pos - 10) + 'px';
            }

            $('body').on('click', '#save_panorama', function (e) { // сохранить панораму(когда добавили точку в модальном окне)
                var _this = $(this);

                var hotspot_x = _this.parents('.modal').find(".planHotspot").css("left");
                var hotspot_y = _this.parents('.modal').find(".planHotspot").css("top");

                cur_input.parent('.panorama-wrap').find('.x').val(hotspot_x);
                cur_input.parent('.panorama-wrap').find('.y').val(hotspot_y);

                $('.planHotspot').remove();

                $('#close_modal').click();
            });

        });
    </script>
@endsection
