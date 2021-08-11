<ul class="breadcrumb">
    @foreach($breadcrumb as $item)
        @if ($loop->last)
            <li>{{ $item['title'] }}</li>
        @else
            @if(!empty($item['title']))
                <li>
                    <a href="{{ get_full_url_for_page($item['slug']) }}">
                        {{ $item['title'] }}
                    </a>
                </li>
            @endif
        @endif
    @endforeach
</ul>
