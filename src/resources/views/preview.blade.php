@extends('layout.preview')

@section('content')

    @foreach(data_get($data, 'components', []) as $component)
        @component(data_get($component, 'view') ,[
            'component' => $component,
            'lang' => $currentLang
        ])
        @endcomponent
    @endforeach

@endsection
