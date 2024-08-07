@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Локации</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/locations/create') }}" class="btn btn-success btn-sm" title="Add New Location">
                            <i class="fa fa-plus" aria-hidden="true"></i>Добавить новую
                        </a>

                        {!! Form::open(['method' => 'GET', 'class' => 'form-inline my-2 my-lg-0 float-right', 'role' => 'search'])  !!}
                        <div class="input-group">
                            {!! Form::select('city', $cities, $city, ['class' => 'form-control', 'placeholder' => 'Город']) !!}
                        </div>
                        <div class="input-group">
                            {!! Form::select('category', $categories, $category, ['class' => 'form-control', 'placeholder' => 'Категория']) !!}
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                            <span class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        {!! Form::close() !!}

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <div>Всего: <strong>{{ $totalLocations }}</strong> Опубликовано: <strong>{{ $publishedLocations }}</strong> Выключено: <strong>{{ $unpublishedLocations }}</strong></div>
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>№</th><th>Название</th><th>Подлокации</th><th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($locations as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->sublocations->count() }}</td>
                                        <td>
                                            <a href="{{ url('/admin/floors/' . $item->id) }}" title="Этажи"><button class="btn btn-info btn-sm"><i class="fa fa-arrows-v" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/admin/podloc/' . $item->id) }}" title="Подлокации"><button class="btn btn-info btn-sm"><i class="fa fa-map-marker" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/admin/locations/ru/' . $item->id) }}" title="Установить точки"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/admin/videos/' . $item->id) }}" title="Редактировать видео"><button class="btn btn-info btn-sm"><i class="fa fa-video-camera" aria-hidden="true"></i></button></a>
                                            <a href="{{ url('/admin/locations/' . $item->id . '/edit/ru?returnUrl=' . urlencode(url()->full())) }}" title="Edit Location"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                            @if($item->panorama)
                                                @php
                                                $originalPano = 'storage/panoramas/' . pathinfo(str_replace('.tiles', '', $item->xmlName(json_decode($item->panorama)[0]->panoramas[0]->panorama)))['filename'];
                                                if (file_exists($originalPano . '.jpg')) {
                                                    $originalPano = '/' . $originalPano . '.jpg';
                                                } else {
                                                    $originalPano = '/' . $originalPano . '.jpeg';
                                                }
                                                @endphp
                                                <a href="{{  $originalPano }}" title="Оригинал"><button class="btn btn-info btn-sm"><i class="fa fa-image" aria-hidden="true"></i></button></a>
                                            @endif
                                            @if($item->video)
                                                <a href="{{ route('admin.locations.video', $item->id) }}" title="Show Video"><button class="btn btn-info btn-sm"><i class="fa fa-video-camera" aria-hidden="true"></i></button></a>
                                            @endif
                                            @if(auth()->user()->hasRole('Admin'))
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/locations', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'title' => 'Delete Location',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $locations->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
