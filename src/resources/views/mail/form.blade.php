@foreach($mail as $name => $value)
    @if($loop->first) @else
        <span style="color:#5e6977;font-size:16px;line-height:24px;">{{ ucfirst($name) }} : {{ $value }}</span><br>
    @endif
@endforeach
