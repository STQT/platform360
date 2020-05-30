@extends('layouts.static')

@section('content')
<div class="container">
    <h1>{{ $page->title }}</h1>
    <div class="content">
        {!! $page->content !!}
    </div>
</div>
@endsection
