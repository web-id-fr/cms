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
        Type : {{ $article['article_type'] }}
        Titre : {{ $article['title'] }}
        Slug : {{ $article['slug'] }}
        Date de publication : {{ $article['publish_at'] }}
        ---
        Image : <img width="150" src="{{ $article['image'] }}" alt="{{ $article['image_alt'] }}">
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
        Opengraph picture : <img width="150" src="{{ $article['og_picture'] }}" alt="{{ $article['og_picture_alt'] }}">
        ---
                Categories : @foreach($article['categories'] as $category)
            @if(!empty($category['name'])) <a href="{{ route('articles.categories.show', ['category' => $category['name']]) }}">#{{ $category['name'] }}</a> @endif @endforeach

        Articles en relations :
        @foreach($article['related']['articles'] as $article_related)
            Type : {{ $article_related['article_type'] }}
            Titre : {{ $article_related['title'] }}
            Slug : {{ $article_related['slug'] }}
            Date de publication : {{ $article_related['publish_at'] }}
            ---
            Image : <img width="150" src="{{ $article_related['image'] }}"
                         alt="{{ $article_related['image_alt'] }}">
            Statut : {{ $article_related['status'] }}
            Extrait (HTML) : {!! $article_related['extrait'] !!}
            Contenu (HTML) :
            @foreach($article_related['content'] ?? [] as $content)
                @component('articles::content.' . $content['layout'], [
                    'data' => $content
                ])
                @endcomponent
            @endforeach
            Categories : @foreach($article_related['categories'] as $category)
                @if(!empty($category['name'])) <a href="{{ route('articles.categories.show', ['category' => $category['name']]) }}">#{{ $category['name'] }}</a> @endif @endforeach
        @endforeach
    </pre>
@endsection
