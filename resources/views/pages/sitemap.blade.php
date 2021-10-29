@extends('layouts.static')

@section('content')
    <div class="row jumbotron">
        <div class="container">
            <header>
                <h1>Карта сайта</h1>
            </header>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <h3>Локации</h3>
                    @foreach ($sitemap as $href => $title)
                        <li><a href="https://{{ $href }}">{{ $title }}</a><br></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
