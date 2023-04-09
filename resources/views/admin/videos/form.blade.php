<div class="form-group{{ $errors->has('hfov') ? 'has-error' : ''}}">
    {!! Form::label('hfov', 'hfov', ['class' => 'control-label']) !!}
    {!! Form::text('hfov', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    
    {!! $errors->first('hfov', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('yaw') ? 'has-error' : ''}}">
    {!! Form::label('yaw', 'yaw', ['class' => 'control-label']) !!}
    {!! Form::text('yaw', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}

    {!! $errors->first('yaw', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('pitch') ? 'has-error' : ''}}">
    {!! Form::label('pitch', 'pitch', ['class' => 'control-label']) !!}
    {!! Form::text('pitch', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}

    {!! $errors->first('pitch', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('roll') ? 'has-error' : ''}}">
    {!! Form::label('roll', 'roll', ['class' => 'control-label']) !!}
    {!! Form::text('roll', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}

    {!! $errors->first('roll', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">Видео: <input class="form-control-file" type="file" name="video"></div>

<input type="hidden" name="lang" value="{{ $lang }}">

<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
