@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Edit Category #{{ $category->id }}</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/categories') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/categories/'.$category->id.'/edit/ru') }}" ><button class="btn btn-success btn-sm" type="button">Русский</button></a>
                        <a href="{{ url('/admin/categories/'.$category->id.'/edit/es') }}" ><button class="btn btn-success btn-sm" type="button">Испанский</button></a>
                        <a href="{{ url('/admin/categories/'.$category->id.'/edit/tr') }}" ><button class="btn btn-success btn-sm" type="button">Турецкий</button></a>
                        <a href="{{ url('/admin/categories/'.$category->id.'/edit/en') }}" ><button class="btn btn-info btn-sm" type="button">Английский</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($category, [
                            'method' => 'PATCH',
                            'url' => ['/admin/categories', $category->id, $language],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('admin.categories.form', ['formMode' => 'edit'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
