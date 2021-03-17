<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @routes('form')
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ data_get($meta, 'title') }}</title>
    <meta name="description" content="{{ strip_tags(data_get($meta, 'description', '')) }}">
    <meta name="keywords" content="{{ strip_tags(data_get($meta, 'keywords', '')) }}">
    <meta property="og:title" content="{{ strip_tags(data_get($meta, 'og_title', '')) }}">
    <meta property="og:type" content="{{ data_get($meta, 'type', 'website') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:description" content="{{ strip_tags(data_get($meta, 'og_description', '')) }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('cms/css/override_nova.css') }}" />
    @yield('assets')
</head>
<body>
    <div id="app">
        <div class="main_wrap" id="@yield('id_page')">
            @yield('content')

            @include('popup')
        </div>
    </div>

    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')
</body>
