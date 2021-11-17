<div class="form-group{{ $errors->has('location_id') ? 'has-error' : ''}}">
    {!! Form::label('location_id', 'Location Id', ['class' => 'control-label']) !!}
    {!! Form::number('location_id', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('location_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('destination_id') ? 'has-error' : ''}}">
    {!! Form::label('destination_id', 'Destination Id', ['class' => 'control-label']) !!}
    {!! Form::number('destination_id', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('destination_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('h') ? 'has-error' : ''}}">
    {!! Form::label('h', 'H', ['class' => 'control-label']) !!}
    {!! Form::text('h', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('h', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('v') ? 'has-error' : ''}}">
    {!! Form::label('v', 'V', ['class' => 'control-label']) !!}
    {!! Form::text('v', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('v', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
