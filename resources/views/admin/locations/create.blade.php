@extends('layouts.backend')

@section('content')
    {!! Form::open(['url' => '/admin/locations', 'class' => 'form-horizontal', 'files' => true]) !!}
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Create New Location</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/locations') }}" title="Back">
                            <button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                                Назад
                            </button>
                        </a>
                        <br/>
                        <br/>

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @include ('admin.locations.form', ['formMode' => 'create'])

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Настройки</div>
                    <div class="card-body">
                        @include ('admin.locations.settings', [])
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
