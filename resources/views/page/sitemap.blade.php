@extends('layouts.static')

@section('content')
<div class="container">
    @foreach ($sitemap as $href => $title)
        <a href="https://{{ $href }}">{{ $title }}</a><br>
    @endforeach
</div>
@endsection
