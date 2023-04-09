@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Подлокации   <b>{{$parentname}}</b></div>
                    <div class="card-body">
                      <a href="{{ url('/admin/locations') }}" class="btn btn-success btn-sm"  style="background-color:grey;border:0px;">
                        Назад
                      </a>
                        <a href="{{ url('/admin/podloc/create/'.$parentid.'') }}" class="btn btn-success btn-sm" title="Add New sky">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name</th><th>Actions</th><th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($podlocs as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                        <button class="btn btn-info btn-sm show-sublocation" data-id="{{ $item->id }}" title="Показывать в поиске"><i class="fa fa-check-square"></i></button>
                                            <a href="{{ url('/admin/locations/ru/' . $item->id) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/admin/videos/' . $item->id) }}" title="Редактировать видео"><button class="btn btn-info btn-sm"><i class="fa fa-video-camera" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/admin/podloc/edit/' . $item->id . '/ru') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            @php
                                                $originalPano = 'storage/panoramas/' . pathinfo(str_replace('.tiles', '', $item->xmlName(json_decode($item->panorama)[0]->panoramas[0]->panorama)))['filename'];
                                                if (file_exists($originalPano . '.jpg')) {
                                                    $originalPano = '/' . $originalPano . '.jpg';
                                                } else {
                                                    $originalPano = '/' . $originalPano . '.jpeg';
                                                }
                                            @endphp
                                            <a href="{{ $originalPano }}" title="Оригинал"><button class="btn btn-info btn-sm"><i class="fa fa-image" aria-hidden="true"></i></button></a>
                                            @if(auth()->user()->hasRole('Admin'))
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/locations', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'title' => 'Delete sky',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td>
                                            <img src="/storage/panoramas/unpacked/{{\App\Location::folderNames([$item])[0]}}/thumb.jpg" width="150">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $podlocs->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
