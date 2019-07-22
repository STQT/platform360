<div class="form-group{{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
    {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}

    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('city_id	') ? 'has-error' : '' }}">
    {{Form::label('city_id', 'Город', ['class' => 'control-label'])}}

    {{Form::select('city_id', $cities->pluck('name', 'id') ,null,array('name'=>'city_id', 'class' => 'form-control'))}}

</div>
<div class="form-group{{ $errors->has('panorama') ? 'has-error' : ''}}">
    {!! Form::label('panorama', 'Panorama', ['class' => 'control-label']) !!}
    <input type="file" class="form-control plan_input" name="panorama"   />

    {!! $errors->first('panorama', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('icon') ? 'has-error' : ''}}">
    {!! Form::label('icon', 'Icon', ['class' => 'control-label']) !!}
    {!! Form::text('icon', 'copter.png',  ['class' => 'form-control'] ) !!}

    {!! $errors->first('icon', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('icon_svg') ? 'has-error' : ''}}">
    {!! Form::label('icon_svg', 'icon_svg', ['class' => 'control-label']) !!}
    {!! Form::text('icon_svg', 'copter.svg', ['class' => 'form-control'] ) !!}

    {!! $errors->first('icon_svg', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'Описание', ['class' => 'control-label']) !!}
    {!! Form::textarea('description', null,  ['class' => 'form-control'] ) !!}
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('skymainforcity') ? 'has-error' : ''}}">

    <input {{ isset($sky) ? ($sky->skymainforcity ? 'checked' : '') : ''}} id="skymainforcity" type="checkbox" class="" name="skymainforcity"> Основное небо для города?

    {!! $errors->first('skymainforcity', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>
