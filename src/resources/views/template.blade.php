@extends('layout.app')

@section('content')

    @foreach(data_get($data, 'items', []) as $component)
        @component(data_get($component, 'component.view'), [
            'component' => data_get($component, 'component'),
            'lang' => $currentLang
        ])
        @endcomponent
    @endforeach

@endsection
