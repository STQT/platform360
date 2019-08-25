
{{ Form::hidden('parrentid', $sky->id) }}




<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Название', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('category_id') ? 'has-error' : '' }}">
    {{Form::label('category_id', 'Категория', ['class' => 'control-label'])}}
    {{Form::select('category_id', $categories->pluck('name', 'id') ,null,array('name'=>'category_id', 'class' => 'form-control'))}}
</div>
<div class="form-group{{ $errors->has('city_id') ? 'has-error' : '' }}">
    {{Form::label('city_id', 'Город', ['class' => 'control-label'])}}

    {{Form::select('city_id', $cities->pluck('name', 'id') ,null,array('name'=>'city_id', 'class' => 'form-control'))}}

</div>
<div class="form-group{{ $errors->has('sky_id') ? 'has-error' : '' }}">

    {{Form::label('sky_id', 'Небо', ['class' => 'control-label'])}}


<select class="form-control" name="sky_id" id="sky_id">
<option selected value="">Неба нет</option>

   @foreach($skyy as $skyitem)

     @if (isset($sky) && $sky->sky_id == $skyitem->id)
       <option selected value="{{$skyitem->id}}">{{$skyitem->name}}</option>

   @else
     <option value="{{$skyitem->id}}">{{$skyitem->name}}</option>

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
    <input {{ isset($location) ? ($location->isfeatured ? 'checked' : '') : ''}} id="isfeatured" type="checkbox" class="" name="isfeatured"> Избранный?
</div>

<div class="form-group">
    <input {{ $sky->show_sublocation == 1 ? 'checked' : ''}} id="show_sublocation" type="checkbox" class="" name="show_sublocation" value="1"> Показывать в поиске
</div>

<div class="form-group">
    <input {{ isset($sky) ? ($sky->published ? 'checked' : '') : ''}} id="published" type="checkbox" class="" value="1" name="published"> Опубликовано
</div>

<div class="hasNo" style="{{ isset($location) ? ($location->isFloor ? 'display: none;' : '') : '' }}">
    <div class="form-group{{ $errors->has('panorama') ? 'has-error' : ''}}">
        {!! Form::label('panorama', 'Panorama', ['class' => 'control-label']) !!}
        {!! Form::file('panorama', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('panorama', '<p class="help-block">:message</p>') !!}
    </div>
</div>





<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
