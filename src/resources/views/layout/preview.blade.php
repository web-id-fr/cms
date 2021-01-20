<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @routes('form')
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Preview -  {{ data_get($data, 'title') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}" />
    @yield('assets')
</head>
<body>
<div id="app">
    <div class="main_wrap" id="@yield('id_page')">
        @yield('content')
    </div>
</div>

<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@yield('scripts')
</body>
