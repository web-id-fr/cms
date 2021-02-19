{{--
 |--------------------------------------------------------------------------
 | Zone de contenu MENU
 |--------------------------------------------------------------------------
 |
 | Les variables disponibles sont :
 |  • menu_items        un array, la liste des items du menu actuel
 |  • title             une string, le titre du menu (traduisible)
 |
--}}

@foreach($menu_items as $item)
    {{-- On définit la variable $link_url selon le type d'item --}}
    @if(!empty($item['url']))
        @php $link_url = data_get($item, 'url', ''); @endphp
    @elseif(!empty($item['slug']))
        @php $link_url = route('pageFromSlug', [ 'slug' => data_get($item, 'slug', '') ]); @endphp
    @endif

    {{-- Si on a un lien ET un titre, on affiche le lien --}}
    @if(!empty($link_url) && !empty(data_get($item, 'title')))
        <div>
            <a href="{{ $link_url }}"
               @if(current_url_is($link_url)) class="active" @endif>{{ data_get($item, 'title', '') }}</a>

            @if (data_get($item, 'children'))
                <div class="submenu">
                    @foreach (data_get($item, 'children') as $children)
                        @if(!empty($children['url']))
                            @php $link_url = data_get($children, 'url', ''); @endphp
                        @elseif(!empty($children['slug']))
                            @php $link_url = route('pageFromSlug', [ 'slug' => data_get($children, 'slug', '') ]); @endphp
                        @endif
                        <div>
                            <a @if(current_url_is($link_url)) class="active" @endif href="{{ $link_url }}">{{ data_get($children, 'title', '') }}</a>
                            {{ $children['description'] }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- On reset la variable $link_url --}}
    @php $link_url = ''; @endphp
@endforeach
