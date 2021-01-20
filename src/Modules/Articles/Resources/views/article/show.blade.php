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
        Contenu (HTML) : {!! $article['content'] !!}
        ---
        Meta title : {{ $article['metatitle'] }}
        Meta description : {{ $article['metadescription'] }}
        Opengraph title : {{ $article['og_title'] }}
        Opengraph description : {{ $article['og_description'] }}
        Opengraph picture : <img width="150" src="{{ $article['og_picture'] }}">
        ---
        Tags : @foreach($article['tags'] as $tag)
@if(!empty($tag['name'])) <a href="{{ route('articles.tags.show', ['tag' => $tag['name']]) }}">#{{ $tag['name'] }}</a> @endif @endforeach
    </pre>
@endsection
