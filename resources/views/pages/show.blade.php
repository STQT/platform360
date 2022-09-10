@extends('layouts.static')

@section('content')
    <div class="row jumbotron">
        <div class="container">
            <header>
                <h1>{{ $page->title }}</h1>
            </header>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
