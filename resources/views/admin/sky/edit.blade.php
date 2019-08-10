@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Редактировать небо #{{ $sky->id }}</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/sky') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/sky/'.$sky->id.'/edit/ru') }}" ><button class="btn btn-success btn-sm">Русский</button></a>

                        <a href="{{ url('/admin/sky/'.$sky->id.'/edit/uzb') }}" ><button class="btn btn-success btn-sm">Узбекский</button></a>
                        <a href="{{ url('/admin/sky/'.$sky->id.'/edit/en') }}" ><button class="btn btn-info btn-sm">Английский</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($sky, [
                            'method' => 'PATCH',
                            'url' => ['/admin/sky', $sky->id, $language],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('admin.sky.form', ['formMode' => 'edit'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
