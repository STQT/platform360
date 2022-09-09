<div class="form-group{{ $errors->has('key') ? 'has-error' : ''}}">
    {!! Form::label('key', 'Key', ['class' => 'control-label']) !!}
    {!!  Form::select('key', trans('uzb360'), null, ['placeholder' => 'Choose...', 'class' => 'form-control', 'required' => 'required']) !!}
    {!! $errors->first('key', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group{{ $errors->has('value_ru') ? 'has-error' : ''}}">
    {!! Form::label('value_ru', 'Текст (Ru)', ['class' => 'control-label']) !!}
    {!! Form::text('value_ru', isset($translation) ? $translation->getTranslation('value', 'ru') : null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('value_ru', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('value_uzb') ? 'has-error' : ''}}">
    {!! Form::label('value_uzb', 'Текст (Uz)', ['class' => 'control-label']) !!}
    {!! Form::text('value_uzb', isset($translation) ? $translation->getTranslation('value', 'uzb') : null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('value_uzb', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('value_en') ? 'has-error' : ''}}">
    {!! Form::label('value_en', 'Текст (En)', ['class' => 'control-label']) !!}
    {!! Form::text('value_en', isset($translation) ? $translation->getTranslation('value', 'en') : null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('value_en', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
