@extends('layout.app', [
    'meta' => [
        'title' => null,
        'description' => null,
        'og_title' => null,
        'og_description' => null,
        'type' => null,
    ]
])

@section('content')
    <h1>Liste des articles correspondant à la catégorie {{ $category }} : {{ count($articles) }}</h1>

    @foreach($articles as $article)
        <hr>
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
                @component($content['layout'], [
                    'data' => $content,
                    'current_lang' => $current_lang
                ])
                @endcomponent
            @endforeach
            ---
            Meta title : {{ $article['metatitle'] }}
            Meta description : {{ $article['metadescription'] }}
            Opengraph title : {{ $article['og_title'] }}
            Opengraph description : {{ $article['og_description'] }}
            Opengraph picture : <img width="150" src="{{ $article['og_picture'] }}">

            <a href="{{ route('articles.show', ['slug' => $article['slug']]) }}">[ Voir cet article ]</a>
        </pre>
    @endforeach

@endsection
