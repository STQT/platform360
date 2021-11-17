<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Панорама</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#meta" role="tab" aria-controls="profile" aria-selected="false">Мета</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      <div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
          {!! Form::label('name', 'Название', ['class' => 'control-label']) !!}
          {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
          {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('category_id') ? 'has-error' : '' }}">
          {{Form::label('category_id', 'Категория', ['class' => 'control-label'])}}
          {{Form::select('category_id', $categories->pluck('name', 'id'), null,array('name'=>'category_id', 'class' => 'form-control'))}}
      </div>

      <div class="form-group{{ $errors->has('city_id') ? 'has-error' : '' }}">
          {{Form::label('city_id', 'Город', ['class' => 'control-label'])}}

          {{Form::select('city_id', $cities->pluck('name', 'id') ,null,array('name'=>'city_id', 'class' => 'form-control'))}}
      </div>

      <div class="form-group{{ $errors->has('sky_id') ? 'has-error' : '' }}">
          {{Form::label('sky_id', 'Небо', ['class' => 'control-label'])}}
          <select class="form-control" name="sky_id" id="sky_id">
          <option selected value="">Неба нет</option>
             @foreach($sky as $sky)
               @if (isset($location->sky_id) && $location->sky_id == $sky->id)
                 <option selected value="{{$sky->id}}">{{$sky->name}}</option>
               @else
                 <option value="{{$sky->id}}">{{$sky->name}}</option>
               @endif
             @endforeach
           </select>
      </div>
      <div class="form-group{{ $errors->has('address') ? 'has-error' : ''}}">
          {!! Form::label('address', 'Адрес', ['class' => 'control-label']) !!}
          {!! Form::text('address', null, ['class' => 'form-control'] ) !!}
          {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('number') ? 'has-error' : ''}}">
          {!! Form::label('number', 'Номер телефона', ['class' => 'control-label']) !!}
          {!! Form::text('number', null, ['class' => 'form-control']) !!}
          {!! $errors->first('number', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
          {!! Form::label('description', 'Описание', ['class' => 'control-label']) !!}
          {!! Form::textarea('description', null,  ['class' => 'form-control'] ) !!}
          {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('working_hours') ? 'has-error' : ''}}">
          {!! Form::label('working_hours', 'Рабочие часы', ['class' => 'control-label']) !!}
          {!! Form::text('working_hours', null, ['class' => 'form-control']) !!}
          {!! $errors->first('working_hours', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('website') ? 'has-error' : ''}}">
          {!! Form::label('website', 'Веб-сайт', ['class' => 'control-label']) !!}
          {!! Form::text('website', null, ['class' => 'form-control'] ) !!}
          {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('facebook') ? 'has-error' : ''}}">
          {!! Form::label('facebook', 'Facebook', ['class' => 'control-label']) !!}
          {!! Form::text('facebook', null,['class' => 'form-control'] ) !!}
          {!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('instagram') ? 'has-error' : ''}}">
          {!! Form::label('instagram', 'Instagram', ['class' => 'control-label']) !!}
          {!! Form::text('instagram', null, ['class' => 'form-control'] ) !!}
          {!! $errors->first('instagram', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('telegram') ? 'has-error' : ''}}">
          {!! Form::label('telegram', 'Telegram', ['class' => 'control-label']) !!}
          {!! Form::text('telegram', null, ['class' => 'form-control'] ) !!}
          {!! $errors->first('telegram', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="form-group">
          <input {{ isset($location) ? ($location->onmap ? 'checked' : '') : ''}} id="onmap" type="checkbox" class="" name="onmap"> Показать на карте?
      </div>
      <div class="form-group{{ $errors->has('lat') ? 'has-error' : ''}}">
          {!! Form::label('lat', 'lat', ['class' => 'control-label']) !!}
          {!! Form::text('lat', null, ['class' => 'form-control'] ) !!}
          {!! $errors->first('lat', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group{{ $errors->has('lng') ? 'has-error' : ''}}">
          {!! Form::label('lng', 'lng', ['class' => 'control-label']) !!}
          {!! Form::text('lng', null, ['class' => 'form-control'] ) !!}
          {!! $errors->first('lng', '<p class="help-block">:message</p>') !!}
      </div>

      <div class="form-group">
          <input {{ isset($location) ? ($location->isDefault ? 'checked' : '') : ''}} id="isDefault" type="checkbox" class="" name="isDefault"> Основной
      </div>
      <div class="form-group">
          <input {{ isset($location) ? ($location->isfeatured ? 'checked' : '') : ''}} id="isfeatured" type="checkbox" class="" name="isfeatured"> Избранный?
      </div>
      <div class="form-group">
          <input {{ isset($location) ? ($location->published ? 'checked' : '') : ''}} id="published" type="checkbox" class="" value="1" name="published"> Опубликовано
      </div>

      <ul class="nav nav-tabs" id="pano-tabs" role="tablist">
          <li class="nav-item">
              <a class="nav-link active" id="pano-tab" data-toggle="tab" href="#pano" role="tab" aria-controls="pano"
                 aria-selected="true">Панорама</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" id="video-pano-tab" data-toggle="tab" href="#video" role="tab" aria-controls="video"
                 aria-selected="false">Видео</a>
          </li>
      </ul>
      <div class="tab-content">
          <div class="tab-pane fade show active" id="pano" role="tabpanel" aria-labelledby="pano-tab">
              <div class="hasNo" style="{{ isset($location) ? ($location->isFloor ? 'display: none;' : '') : '' }}">
                  <div class="form-group{{ $errors->has('panorama') ? 'has-error' : ''}}">
                      {!! Form::label('panorama', 'Панорама', ['class' => 'control-label']) !!}
                      {!! Form::file('panorama', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('panorama', '<p class="help-block">:message</p>') !!}
                  </div>
              </div>
          </div>
          <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-pano-tab">
              <div class="hasNo" style="{{ isset($location) ? ($location->isFloor ? 'display: none;' : '') : '' }}">
                  <div class="form-group{{ $errors->has('video') ? 'has-error' : ''}}">
                      {!! Form::label('video', 'Видео', ['class' => 'control-label']) !!}
                      {!! Form::file('video', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('video', '<p class="help-block">:message</p>') !!}
                  </div>
              </div>

              <div class="hasNo" style="{{ isset($location) ? ($location->isFloor ? 'display: none;' : '') : '' }}">
                  <div class="form-group{{ $errors->has('preview') ? 'has-error' : ''}}">
                      {!! Form::label('preview', 'Обложка', ['class' => 'control-label']) !!}
                      {!! Form::file('preview', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('preview', '<p class="help-block">:message</p>') !!}
                  </div>
              </div>
          </div>
      </div>

      <hr>

      <div class="hasNo">
          <div class="form-group{{ $errors->has('panorama') ? 'has-error' : ''}}">
              {!! Form::label('audio', 'Аудио', ['class' => 'control-label']) !!}
              {!! Form::file('audio', null, ['class' => 'form-control']) !!}
              {!! $errors->first('audio', '<p class="help-block">:message</p>') !!}
          </div>
      </div>

      <div class="form-group">
          {!! Form::submit($formMode === 'edit' ? 'Сохранить' : 'Создать', ['class' => 'btn btn-primary']) !!}
      </div>
  </div>
  <div class="tab-pane fade" id="meta" role="tabpanel" aria-labelledby="profile-tab">
    @include ('admin.locations.meta', [])
  </div>
</div>
