<meta charset="utf-8">
    @php
        $title = !empty($location->seo_title) ? $location->seo_title : $location->name;
    @endphp
    @if (Route::currentRouteAction() == 'App\Http\Controllers\HomeController@getIndex' && !$openedCategory)
        <title>Виртуальный тур по Узбекистану: увидеть панорамные и 3D фото, совершить интерактивный тур по Ташкенту и другим городам Узбекистана можно на Uzbekistan360.Uz</title>
        <meta name="description" content="Виртуальный тур по городам Узбекистана. Где можно найти 3D фотографии и VR панорамы интересных мест Ташкента и Узбекистана? Сайт Uzbekistan360.Uz предоставляет возможность посещения популярных мест и достопримечательностей Узбекистана в режиме 3D! Здесь можно совершить интерактивный тур по самым популярным местам и увидеть потрясающие панорамные виды городов Узбекистана с возможностью обзора 360 градусов!">
        <meta name="keywords" content="3д фото городов узбекистана, 3d фото городов узбекистана, панорамные фото городов узбекистана, фото 360 градусов городов узбекистана, панорамные снимки узбекистана, панорамы городов узбекистана, виртуальные туры узбекистан, 3d снимки узбекистан, панорамы улиц ташкента, панорамные фотографии ташкента, панорамные фото узбекистана, 3d узбекистан, увидеть виртуальный ташкент">
    @elseif ($openedCategory)
        @if ($openedCategory->meta)
            <title>{{ $openedCategory->meta->title }}</title>
            <meta name="description" content="{{ $openedCategory->meta->description }}">
            <meta name="keywords" content="{{ $openedCategory->meta->keywords }}">
        @endif
    @elseif ((empty($location->description) && $location->podlocparent_id !== null) || $location->podlocparent_id === null)
        @if (isset($location->meta->title) && $location->meta->title != '')
            <title>{{ $location->meta->title }}</title>
        @else
            <title>{{ $title }} в {{ $location->city->seo_title }}, Узбекистан: виртуальный тур, 3D фото, панорамные снимки, интерактивная версия – посмотреть {{ $title }} онлайн на Uzbekistan360.Uz</title>
        @endif
        @if (isset($location->meta->description) && !empty($location->meta->description))
            <meta name="description" content="{{ $location->meta->description }}">
        @else
            <meta name="description" content="Виртуальный тур по {{ $location->name }} в {{ $location->city->seo_title }}, Узбекистан. Посмотреть 3D фото и панорамные снимки {{ $location->name }} Вы можете на Uzbekistan360.Uz. На сайте представлен {{ $location->name }} в {{ $location->city->seo_title }} в режиме обзора 360 градусов и полным 3D погружением!">
        @endif
        @if (isset($location->meta->keywords) && !empty($location->meta->keywords))
             <meta name="keywords" content="{{ $location->meta->keywords }}">
        @else
            <meta name="keywords" content="{{ $location->name }}, {{ $location->name }} в {{ $location->city->seo_title }}, {{ $location->name }} в узбекистане, {{ $location->name }} виртуальный тур, {{ $location->name }} 3d фото, {{ $location->name }} 3д фото, {{ $location->name }} панорама, {{ $location->name }} панорамные снимки, {{ $location->name }} посмотреть онлайн, {{ $location->name }} обзор 360 градусов">
        @endif
    @elseif ($location->podlocparent_id !== null && !empty($location->description))
        @if (isset($location->meta->title) && $location->meta->title != '')
            <title>{{ $location->meta->title }}</title>
        @else
            <title>{{ $title }} в {{ $location->city->seo_title }}, 3D фото, панорамные снимки, интерактивная версия – посмотреть {{ $title }} онлайн на Uzbekistan360.Uz</title>
        @endif
        @if (isset($location->meta->description) && !empty($location->meta->description))
            <meta name="description" content="{{ $location->meta->description }}">
        @else
            <meta name="description" content="Виртуальный тур по {{ $title }} в {{ $location->city->seo_title }}. Посмотреть 3D фото и панорамные снимки {{ $title }} Вы можете на Uzbekistan360.Uz. На сайте представлен {{ $title }} в {{ $location->city->seo_title }} в режиме обзора 360 градусов и полным 3D погружением! {{ $location->description }}">
        @endif
        @if (isset($location->meta->keywords) && !empty($location->meta->keywords))
             <meta name="keywords" content="{{ $location->meta->keywords }}">
        @else
            <meta name="keywords" content="{{ $title }}, {{ $title }} в {{ $location->city->seo_title }}, {{ $title }} в узбекистане, {{ $title }} виртуальный тур, {{ $title }} 3d фото, {{ $title }} 3д фото, {{ $title }} панорама, {{ $title }} панорамные снимки, {{ $title }} посмотреть онлайн, {{ $title }} обзор 360 градусов, {{ $title }} {{ $location->description }}">
        @endif
    @else
        <title>{{ $location->name }} - Uzbekistan 360</title>
        <meta name="description" content="Самый большой и качественный интерактивный тур по Узбекистану">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">

    <meta itemprop="name" content="{{ $location->name }}">
    <meta itemprop="description" content="{{ !empty($location->description) ? $location->description : 'Самый большой и качественный интерактивный тур по Узбекистану' }}">
    <meta itemprop="image" content="/assets/socialpreview.jpg">
    <meta name="twitter:site" content="@Uzbekistan360uz">
    <meta name="twitter:title" content="{{ $location->name }}">
    <meta name="twitter:description" content="{{ !empty($location->description) ? $location->description : 'Самый большой и качественный интерактивный тур по Узбекистану' }}">
    <meta name="twitter:image:src" content="/assets/socialpreview.jpg">
    <meta property="og:title" content="{{ $location->name }}">
    <meta property="og:url" content="{{ request()->root() }}">
    @if($location && !empty($location->getThumb()))
        <meta property="og:image" content="{{ request()->root() . $location->getThumb() }}">
    @else
        <meta property="og:image" content="/assets/socialpreview.jpg">
    @endif
    <meta property="og:description" content="{{ !empty($location->description) ? $location->description : 'Самый большой и качественный интерактивный тур по Узбекистану' }}">
    <meta property="og:site_name" content="{{ $location->name }}">
    @if (strpos(request()->url(), '/location/') !== false)
        {{--    Мета теги для внутренних страниц    --}}
        @if ($location->podlocparent_id === null)
            @php
                $urlCanonical = $location->createUrl();
            @endphp
        @else
            @if ($location->parent)
                @if ($location->parent->description === $location->description || $location->description == '')
                    @php
                        $urlCanonical = $location->parent->createUrl();
                    @endphp
                @else
                    @php
                        $urlCanonical = $location->createUrl();
                    @endphp
                @endif
            @endif
        @endif
    @endif
    @if ($location->subdomain != '')
        @php
            $urlCanonical = $location->createUrl();
        @endphp
    @endif
    @if (isset($urlCanonical))
        @php
            $serverName = request()->root();
            if (strpos($serverName, getenv('SUBDOMAIN')) !== false) {
                $urlCanonical = str_replace(getenv('SUBDOMAIN'), getenv('MAIN_DOMAIN'), $serverName);
            }
        @endphp
        <link rel="canonical" href="{{ $urlCanonical }}"/>
    @endif
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="/assets/app.22400e48.css?033f3b348c8154cd9ec6" rel="stylesheet">
    <link href="/assets/slick-carousel/slick.css" rel="stylesheet">
    <link href="/assets/slick-carousel/slick-theme.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/flooreditor/css/annotator-pro.min.css">
    <link rel="stylesheet" href="/assets/css/jquery.fancybox.min.css">
    <link href="/assets/custom.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
