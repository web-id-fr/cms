@if (!empty($children['children']))
    <li class="dropdown-menu-container">
        <span data-submenu-trigger="true">{{ data_get($children, 'title', '') }}</span>
        <ul class="dropdown-menu">
            @foreach ($children['children'] as $child)
                @if(!empty($child['url']))
                    @php $link_url = data_get($child, 'url', ''); @endphp
                @elseif(!empty($child['full_path']))
                    @php $link_url = data_get($child, 'full_path', ''); @endphp
                @endif

                @include('components.menu.child', [
                    'children' => $child
                ])
            @endforeach
        </ul>
    </li>
@else
    <li>
        <a
            class=" @if(current_url_is($link_url) && $link_url !== '#') active @endif"
            href="{{ $link_url }}">
            {{ $children['title'] ?? "" }}
        </a>
    </li>
@endif
