<div class="form-group{{ $errors->has('meta[title]') ? 'has-error' : ''}}">
    {!! Form::label('meta[title]', 'Title (тег)', ['class' => 'control-label']) !!}
    {!! Form::textarea('meta[title]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('meta[title]', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('meta[description]') ? 'has-error' : ''}}">
    {!! Form::label('meta[description]', 'Description (meta тег)', ['class' => 'control-label']) !!}
    {!! Form::textarea('meta[description]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('meta[description]', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('meta[keywords]') ? 'has-error' : ''}}">
    {!! Form::label('meta[keywords]', 'Keywords (meta тег)', ['class' => 'control-label']) !!}
    {!! Form::textarea('meta[keywords]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('meta[keywords]', '<p class="help-block">:message</p>') !!}
</div>
