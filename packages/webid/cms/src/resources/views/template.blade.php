@extends('layout.app')

@section('content')
    @include('nav')

    @foreach(data_get($data, 'items') as $component)
        @component(data_get($component, 'component.view'), [
            'component' => data_get($component, 'component'),
            'lang' => $currentLang
        ])
        @endcomponent
    @endforeach

    @include('footer')
@endsection
