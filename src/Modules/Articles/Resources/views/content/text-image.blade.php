Layout : texte & image
texte : {!! $data['text'][$currentLangKey] !!}
Position du texte : {{ $data['text_position'] }}
image : <img width="150" src="{{ $data['full_image'] }}" alt="{{ $data['balise_alt'][$currentLangKey] }}">

