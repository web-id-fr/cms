{{--
 |--------------------------------------------------------------------------
 | Zone de contenu MENU
 |--------------------------------------------------------------------------
 |
 | Les variables disponibles sont :
 |  • items        un array, la liste des items du menu actuel
 |  • title             une string, le titre du menu (traduisible)
 |
--}}
@foreach($items as $item)
    {{-- On définit la variable $link_url selon le type item --}}
    @if(!empty($item['url']))
        @php $link_url = data_get($item, 'url', ''); @endphp
    @elseif(!empty($item['slug']))
        @php $link_url = route('pageFromSlug', [ 'slug' => data_get($item, 'slug', '') ]); @endphp
    @endif

    {{-- Si on a un lien ET un titre, on affiche le lien --}}
    @if(!empty($link_url) || data_get($item, 'is_popin') && !empty(data_get($item, 'title')))
        <div>
            @if(data_get($item, 'is_popin'))
                <span class="showPopin"
                      data-popin="{{ str_slug(data_get($item, 'title', '')) . '-' . data_get($item, 'id') . '-' . $loop->iteration }}">{{ data_get($item, 'title', '') }}</span>
                @push('popins')
                    @parent
                    @include('forms.popin-form', [
                        'form' => data_get($item, 'form'),
                        'data_popin' => str_slug(data_get($item, 'title', '')) . '-' . data_get($item, 'id') . '-' . $loop->iteration,
                    ])
                @endpush
            @else
                <a @if(!empty(data_get($item, 'target'))) target="{{ data_get($item, 'target') }}"
                   @endif href="{{ $link_url }}"
                   @if(current_url_is($link_url) && empty(data_get($item, 'target'))) class="active" @endif>{{ data_get($item, 'title', '') }}</a>
            @endif
            @if (data_get($item, 'children'))
                <div class="submenu">
                    @foreach (data_get($item, 'children') as $children)
                        @if(data_get($children, 'is_popin'))
                            <div>
                                <span class="showPopin"
                                      data-popin="{{ str_slug(data_get($children, 'title', '')) . '-' . data_get($children, 'id') . '-' . $loop->iteration}}">
                                    {{ data_get($children, 'title', '') }}
                                </span>
                            </div>
                            @push('popins')
                                @parent
                                @include('forms.popin-form', [
                                    'form' => data_get($children, 'form'),
                                    'data_popin' => str_slug(data_get($children, 'title', '')) . '-' . data_get($children, 'id') . '-' . $loop->iteration,
                                ])
                            @endpush
                        @else
                            @if(!empty($children['url']))
                                @php $link_url = data_get($children, 'url', ''); @endphp
                            @elseif(!empty($children['slug']))
                                @php $link_url = route('pageFromSlug', [ 'slug' => data_get($children, 'slug', '') ]); @endphp
                            @endif
                            <div>
                                <a @if(current_url_is($link_url) && empty(data_get($children, 'target'))) class="active"
                                   @endif href="{{ $link_url }}"
                                   @if(!empty(data_get($children, 'target'))) target="{{ data_get($children, 'target') }}" @endif>
                                    {{ data_get($children, 'title', '') }}
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- On reset la variable $link_url --}}
    @php $link_url = ''; @endphp
@endforeach
