<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('lat') ? 'has-error' : ''}}">
    {!! Form::label('lat', 'Lat', ['class' => 'control-label']) !!}
    {!! Form::text('lat', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    
    {!! $errors->first('lat', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('lng') ? 'has-error' : ''}}">
    {!! Form::label('lng', 'Lng', ['class' => 'control-label']) !!}
    {!! Form::text('lng', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    
    {!! $errors->first('lng', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('position') ? 'has-error' : ''}}">
    {!! Form::label('position', 'Position', ['class' => 'control-label']) !!}
    {!! Form::text('position', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    
    {!! $errors->first('position', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('is_default') ? 'has-error' : ''}}">
    
    <input {{ isset($cities) ? ($cities->is_default ? 'checked' : '') : ''}} id="is_default" type="checkbox" class="" name="is_default"> Основной
        
    {!! $errors->first('is_default', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
