{{--
 |--------------------------------------------------------------------------
 | Zone de contenu MENU
 |--------------------------------------------------------------------------
 |
 | Les variables disponibles sont :
 |  • menu_items        un array, la liste des items du menu actuel
 |  • title             une string, le titre du menu (traduisible)
 |  • wrap              optionnel, une string, le nom de la balise qui contient le lien
 |  • title_wrap        optionnel, une string, contient la balise dans laquelle on affiche le titre,
 |                          une valeur vide signifie qu'on affiche pas le titre
 |
--}}
{{-- Si besoin, on affiche le titre du menu --}}
@if(!empty($title_wrap) && is_string($title_wrap))
    {!! "<{$title_wrap}>" !!}{{ "$title" }}{!! "</{$title_wrap}>" !!}
@endif

@foreach($menu_items as $item)
    {{-- On définit la variable $link_url selon le type d'item --}}
    @if(!empty($item['url']))
        @php $link_url = data_get($item, 'url', ''); @endphp
    @elseif(!empty($item['slug']))
        @php $link_url = route('pageFromSlug', [ 'slug' => data_get($item, 'slug', '') ]); @endphp
    @endif

    {{-- Si on a une balise parente, on l'ouvre ici --}}
    @if(!empty($wrap)) {!! "<{$wrap}>" !!} @endif

    {{-- Si on a un lien ET un titre, on affiche le lien --}}
    @if(!empty($link_url) && !empty(data_get($item, 'title')))
        <a href="{{ $link_url }}" @if(current_url_is($link_url)) class="active" @endif>{{ data_get($item, 'title', '') }}</a>
    @endif

    {{-- Si on a une balise parente, on la ferme ici --}}
    @if(!empty($wrap)) {!! "</{$wrap}>" !!} @endif

    {{-- On reset la variable $link_url --}}
    @php $link_url = ''; @endphp
@endforeach