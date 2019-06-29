<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}

    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('color') ? 'has-error' : ''}}">
    {!! Form::label('color', 'Цвет', ['class' => 'control-label']) !!}
    {!! Form::text('color', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}

    {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('cat_icon') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Cat icon', ['class' => 'control-label']) !!}
      <input type="file" class="form-control plan_input" name="cat_icon"   @if (isset($category->cat_icon)) value="{!! $category['cat_icon'] !!}"@endif />

    {!! $errors->first('cat_icon', '<p class="help-block">:message</p>') !!}

</div>
<div class="form-group{{ $errors->has('cat_icon_svg') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Cat icon svg', ['class' => 'control-label']) !!}
      <input type="file" class="form-control plan_input" name="cat_icon_svg"   @if (isset($category->cat_icon_svg)) value="{!! $category['cat_icon_svg'] !!}"@endif />

    {!! $errors->first('cat_icon_svg', '<p class="help-block">:message</p>') !!}

</div>

<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
