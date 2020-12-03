<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1280">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="nofollow,noindex">

    <title>{{ env('APP_NAME') }} BO</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('app.css', 'vendor/nova') }}">
    <link rel="stylesheet" href="{{ asset('cms/css/language.css') }}">

    <!-- Tool Styles -->
    @foreach(\Laravel\Nova\Nova::availableStyles(request()) as $name => $path)
        <link rel="stylesheet" href="/nova-api/styles/{{ $name }}">
    @endforeach

<!-- Custom Meta Data -->
    @include('nova::partials.meta')

<!-- Theme Styles -->
    @foreach(\Laravel\Nova\Nova::themeStyles() as $publicPath)
        <link rel="stylesheet" href="{{ $publicPath }}">
    @endforeach
</head>
<body class="min-w-site bg-40 text-black min-h-full">
<div id="nova">
    <div v-cloak class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="min-h-screen flex-none pt-header min-h-screen w-sidebar bg-grad-sidebar px-6">
            <a href="{{ \Laravel\Nova\Nova::path() }}">
                <div class="absolute pin-t pin-l pin-r bg-logo flex items-center w-sidebar h-header px-6 text-white" style="background-color:#31006f">
                    @include('nova::partials.logo')
                </div>
            </a>

            @foreach (\Laravel\Nova\Nova::availableTools(request()) as $tool)
                {!! $tool->renderNavigation() !!}
            @endforeach
        </div>

        <!-- Content -->
        <div class="content">
            <div class="flex items-center relative shadow h-header bg-white z-20 px-view">
                <a v-if="@json(\Laravel\Nova\Nova::name() !== null)" href="{{ \Illuminate\Support\Facades\Config::get('nova.url') }}" class="no-underline dim font-bold text-90 mr-6">
                    {{ env('APP_NAME') }} BO
                </a>

                @if (count(\Laravel\Nova\Nova::globallySearchableResources(request())) > 0)
                    <global-search dusk="global-search-component"></global-search>
                @endif

                <dropdown class="ml-auto h-9 flex items-center dropdown-right">
                    @include('nova::partials.user')
                </dropdown>
            </div>

            <div data-testid="content" class="px-view py-view mx-auto">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script>
    window.config = @json(\Laravel\Nova\Nova::jsonVariables(request()));
</script>

<!-- Scripts -->
<script src="{{ mix('manifest.js', 'vendor/nova') }}"></script>
<script src="{{ mix('vendor.js', 'vendor/nova') }}"></script>
<script src="{{ mix('app.js', 'vendor/nova') }}"></script>

<!-- Build Nova Instance -->
<script>
    window.Nova = new CreateNova(config)
</script>

<!-- Tool Scripts -->
@foreach (\Laravel\Nova\Nova::availableScripts(request()) as $name => $path)
    @if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://']))
        <script src="{!! $path !!}"></script>
    @else
        <script src="/nova-api/scripts/{{ $name }}"></script>
    @endif
@endforeach

<script>
    const baseUrl = "{{ URL::to('/') }}";
    var usedLanguage = @json(\Webid\LanguageTool\Models\Language::orderBy('name')->get());
    var actualLanguage = "{{ \App::getLocale() }}";
</script>

<!-- Start Nova -->
<script>
    Nova.liftOff()
</script>
</body>
</html>
