<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
           aria-selected="true">Категория</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#meta" role="tab" aria-controls="profile"
           aria-selected="false">Мета</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
            <input type="file" class="form-control plan_input" name="cat_icon"
                   @if (isset($category->cat_icon)) value="{!! $category['cat_icon'] !!}"@endif />
            @if(!empty($category->cat_icon))
                <a style="margin-top: 3px; display: block" href="/storage/cat_icons/{{ $category->cat_icon }}" target="_blank">{{ $category->cat_icon }}</a>
            @endif

            {!! $errors->first('cat_icon', '<p class="help-block">:message</p>') !!}

        </div>
        <div class="form-group{{ $errors->has('cat_icon_svg') ? 'has-error' : ''}}">
            {!! Form::label('name', 'Cat icon svg', ['class' => 'control-label']) !!}
            <input type="file" class="form-control plan_input" name="cat_icon_svg"
                   @if (isset($category->cat_icon_svg)) value="{!! $category['cat_icon_svg'] !!}"@endif />
            @if(!empty($category->cat_icon_svg))
                <a style="margin-top: 3px; display: block" href="/storage/cat_icons/{{ $category->cat_icon_svg }}" target="_blank">{{ $category->cat_icon_svg }}</a>
            @endif
            {!! $errors->first('cat_icon_svg', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="form-group{{ $errors->has('cat_photo') ? 'has-error' : ''}}">
            {!! Form::label('name', 'Фото', ['class' => 'control-label']) !!}
            <input type="file" class="form-control plan_input" name="cat_photo"
                   @if (isset($category->cat_photo)) value="{!! $category['cat_photo'] !!}"@endif />
            @if(!empty($category->cat_photo))
                <a style="margin-top: 3px; display: block" href="/storage/cat_icons/{{ $category->cat_photo }}" target="_blank">{{ $category->cat_photo }}</a>
            @endif
            {!! $errors->first('cat_photo', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="form-group{{ $errors->has('slug') ? 'has-error' : ''}}">
            {!! Form::label('slug', 'Slug', ['class' => 'control-label']) !!}
            {!! Form::text('slug', null, ['class' => 'form-control']) !!}

            {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="form-group{{ $errors->has('information') ? 'has-error' : ''}}">
            {!! Form::label('information', 'Информация', ['class' => 'control-label']) !!}
            {!! Form::textarea('information', null, ['class' => 'form-control']) !!}

            {!! $errors->first('information', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="form-group{{ $errors->has('parent_id') ? 'has-error' : '' }}">
            {{Form::label('parent_id', 'Родительская категория', ['class' => 'control-label'])}}

            {{Form::select('parent_id', $categories->pluck('name', 'id')->prepend('',''), null, array('name'=>'parent_id', 'class' => 'form-control'))}}
        </div>
    </div>
    <div class="tab-pane fade" id="meta" role="tabpanel" aria-labelledby="profile-tab">
        @include ('admin.categories.meta', [])
    </div>
</div>

<div class="form-group">
    {!! Form::submit($formMode === 'edit' ? 'Сохранить' : 'Создать', ['class' => 'btn btn-primary']) !!}
</div>
