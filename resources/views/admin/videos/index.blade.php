@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Videos</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/videos/create') }}" class="btn btn-success btn-sm" title="Add New Video">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name</th><th>Дата создания</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($videos as $video)
                                    <tr>
                                        <td>{{ $video->iteration or $video->id }}</td>
                                        <td>{{ $video->video }}</td>
                                        <td>{{ $video->created_at }}</td>
                                        <td>
                                            <a href="{{ url('/admin/videos/' . $video->id . '/edit/ru') }}" title="Edit video"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/videos', $video->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                            @if(auth()->user()->hasRole('Admin'))
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'title' => 'Delete video',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            @endif
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $videos->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
