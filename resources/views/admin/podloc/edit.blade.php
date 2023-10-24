@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Редактировать подлокацию {{ $sky->name }}</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/podloc/'.$sky->podlocparent_id.'') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/podloc/edit/'.$sky->id.'/ru') }}" ><button class="lang btn btn-success btn-sm {{ Lang::locale() == 'ru' ? 'current' : '' }}">Русский</button></a>

                        <a href="{{ url('/admin/podloc/edit/'.$sky->id.'/it') }}" ><button class="lang btn btn-success btn-sm {{ Lang::locale() == 'it' ? 'current' : '' }}">Итальянский</button></a>
                        <a href="{{ url('/admin/podloc/edit/'.$sky->id.'/en') }}" ><button class="lang btn btn-info btn-sm {{ Lang::locale() == 'en' ? 'current' : '' }}">Английский</button></a>
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
                            'url' => ['/admin/podloc/update', $sky->id, $language],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}
                        @php $sky->id = $sky->podlocparent_id; @endphp
                        @include ('admin.podloc.form', ['formMode' => 'edit', 'tags' => $tags])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
