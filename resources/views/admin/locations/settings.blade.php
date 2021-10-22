<div class="form-group{{ $errors->has('subdomain') ? 'has-error' : ''}}">
    {!! Form::label('subdomain', 'Субдомен', ['class' => 'control-label']) !!}
    {!! Form::text('subdomain', null, ['class' => 'form-control']) !!}
    {!! $errors->first('subdomain', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('order') ? 'has-error' : ''}}">
    {!! Form::label('order', 'Порядок', ['class' => 'control-label']) !!}
    {!! Form::text('order', null, ['class' => 'form-control']) !!}
    {!! $errors->first('order', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('visibility') ? 'has-error' : '' }}">
    {{Form::label('visibility', 'Видимость', ['class' => 'control-label'])}}

    {{Form::select('visibility', $location->getVisibilityOptions() ,null,array('name'=>'visibility', 'class' => 'form-control'))}}
</div>

<div class="form-group">
    <p>Ссылка: <a href="http://<?= getenv('MAIN_DOMAIN') ?>/ru/location/<?= $location->slug ?>" target="_blank">
            <?= getenv('MAIN_DOMAIN') ?>/ru/location/<?= $location->slug ?></a>
    </p>
</div>

<h5>Кнопка назад</h5>
<div class="form-group{{ $errors->has('information[back_button_from_domain]') ? 'has-error' : ''}}">
    {!! Form::label('information[back_button_from_domain]', 'Переход с домена', ['class' => 'control-label']) !!}
    {!! Form::text('information[back_button_from_domain]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('information[back_button_from_domain]', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('information[back_button_background_color]') ? 'has-error' : ''}}">
    {!! Form::label('information[back_button_background_color]', 'Фон кнопки', ['class' => 'control-label']) !!}
    {!! Form::text('information[back_button_background_color]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('information[back_button_background_color]', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('information[back_button_font]') ? 'has-error' : ''}}">
    {!! Form::label('information[back_button_font]', 'Шрифт', ['class' => 'control-label']) !!}
    {!! Form::text('information[back_button_font]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('information[back_button_font]', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('information[back_button_font_size]') ? 'has-error' : ''}}">
    {!! Form::label('information[back_button_font_size]', 'Размер шрифта', ['class' => 'control-label']) !!}
    {!! Form::text('information[back_button_font_size]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('information[back_button_font_size]', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('information[back_button_font_color]') ? 'has-error' : ''}}">
    {!! Form::label('information[back_button_font_color]', 'Цвет ссылки', ['class' => 'control-label']) !!}
    {!! Form::text('information[back_button_font_color]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('information[back_button_font_color]', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('information[back_button_file]') ? 'has-error' : ''}}">
  {!! Form::label('information[back_button_file]', 'Иконка "Назад"', ['class' => 'control-label']) !!}
  {!! Form::file('information[back_button_file]', null, ['class' => 'form-control']) !!}
  {!! $errors->first('information[back_button_file]', '<p class="help-block">:message</p>') !!}
</div>
