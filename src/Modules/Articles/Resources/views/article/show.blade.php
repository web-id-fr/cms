@extends('layout.app', [
    'meta' => [
        'title' => $article['metatitle'] ?? null,
        'description' => $article['metadescription'] ?? null,
        'og_title' => $article['og_title'] ?? null,
        'og_description' => $article['og_description'] ?? null,
        'type' => null,
    ]
])

@section('content')
    <pre>
        Titre : {{ $article['title'] }}
        Slug : {{ $article['slug'] }}
        Date de publication : {{ $article['publish_at'] }}
        ---
        Image : <img width="150" src="{{ $article['image'] }}">
        Statut : {{ $article['status'] }}
        Extrait (HTML) : {!! $article['extrait'] !!}
        Contenu (HTML) :
       @foreach($article['content'] ?? [] as $content)
            @component('articles::content.' . $content['layout'], [
                'data' => $content
            ])
            @endcomponent
        @endforeach
        ---
        Meta title : {{ $article['metatitle'] }}
        Meta description : {{ $article['metadescription'] }}
        Opengraph title : {{ $article['og_title'] }}
        Opengraph description : {{ $article['og_description'] }}
        Opengraph picture : <img width="150" src="{{ $article['og_picture'] }}">
        ---
        Categories : @foreach($article['categories'] as $category)
@if(!empty($category['name'])) <a href="{{ route('articles.categories.show', ['category' => $category['name']]) }}">#{{ $category['name'] }}</a> @endif @endforeach
    </pre>
@endsection
