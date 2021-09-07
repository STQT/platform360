<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('seo_title', 'Название SEO', ['class' => 'control-label']) !!}
    {!! Form::text('seo_title', null, ['class' => 'form-control']) !!}
    {!! $errors->first('seo_title', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group{{ $errors->has('meta[title]') ? 'has-error' : ''}}">
    {!! Form::label('meta[title]', 'Title (тег)', ['class' => 'control-label']) !!}
    {!! Form::text('meta[title]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('meta[title]', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('meta[description]') ? 'has-error' : ''}}">
    {!! Form::label('meta[description]', 'Description (meta тег)', ['class' => 'control-label']) !!}
    {!! Form::text('meta[description]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('meta[description]', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('meta[keywords]') ? 'has-error' : ''}}">
    {!! Form::label('meta[keywords]', 'Keywords (meta тег)', ['class' => 'control-label']) !!}
    {!! Form::text('meta[keywords]', null, ['class' => 'form-control']) !!}
    {!! $errors->first('meta[keywords]', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('tags') ? 'has-error' : '' }}">
    {{Form::label('tags', 'Теги', ['class' => 'control-label'])}}
    {{Form::select('tags[]', $tags, null, array('id' => 'tags_list', 'class' => 'form-control', 'multiple'))}}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Сохранить' : 'Создать', ['class' => 'btn btn-primary']) !!}
</div>
