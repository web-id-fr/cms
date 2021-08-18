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

<h5 data-submenu-trigger-mobile-only aria-hidden="true">{{ $title }}</h5>
<ul>
    @foreach($menu_items as $item)
        @if(!empty($item['url']))
            @php $link_url = data_get($item, 'url', ''); @endphp
        @elseif(!empty($item['full_path']))
            @php $link_url = data_get($item, 'full_path', ''); @endphp
        @endif

        {{-- Si on a un lien ET un titre, on affiche le lien --}}
        @if(!empty($link_url) && !empty(data_get($item, 'title')))
            @if (data_get($item, 'children'))
                <li class="dropdown-menu-container">
                    <span data-submenu-trigger="true">{{ data_get($item, 'title', '') }}</span>
                    <ul class="dropdown-menu">
                        @foreach (data_get($item, 'children') as $children)
                            @if(!empty($children['url']))
                                @php $link_url = data_get($children, 'url', ''); @endphp
                            @elseif(!empty($children['full_path']))
                                @php $link_url = data_get($children, 'full_path', ''); @endphp
                            @endif

                            @include('components.menu.child', [
                                'children' => $children
                            ])
                        @endforeach
                    </ul>
                </li>
            @else
                <li>
                    <a
                            class=" @if(current_url_is($link_url) && $link_url !== '#') active @endif"
                            href="{{ $link_url }}">
                        {{ data_get($item, 'title', '') }}
                    </a>
                </li>
            @endif
        @endif

        @php $link_url = ''; @endphp
    @endforeach
</ul>
