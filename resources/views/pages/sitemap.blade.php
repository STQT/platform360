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
                <h3>Категории</h3>
                <ul>
                    @foreach ($data['categories'] as $category)
                        <li><a href="{{ $category->createUrl() }}">{{ $category->name }}</a>
                            @if ($category->subcategories)
                                <ul>
                                    @foreach($category->subcategories as $subcategory)
                                        <li><a href="{{ $subcategory->createUrl() }}">{{ $subcategory->name }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <h3>Локации</h3>
                <ul>
                    @foreach ($data['locations'] as $location)
                        <li><a href="{{ $location->createUrl() }}">{{ $location->name }}</a>
                            @if ($location->sublocations)
                                <ul>
                                    @foreach($location->sublocations as $sublocation)
                                        <li><a href="{{ $sublocation->createUrl() }}">{{ $sublocation->name }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
