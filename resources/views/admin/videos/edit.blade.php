@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Edit Video #{{ $video->id }}</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/videos') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/videos/'.$video->id.'/edit/ru') }}" ><button class="btn btn-success btn-sm">Русский</button></a>

                        <a href="{{ url('/admin/videos/'.$video->id.'/edit/uzb') }}" ><button class="btn btn-success btn-sm">Узбекский</button></a>
                        <a href="{{ url('/admin/videos/'.$video->id.'/edit/en') }}" ><button class="btn btn-info btn-sm">Английский</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($video, [
                            'method' => 'PATCH',
                            'url' => ['/admin/videos', $video->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('admin.videos.form', ['formMode' => 'edit'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
