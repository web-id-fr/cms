Layout : texte & image
texte : {!! $data['text'][$current_lang] !!}
Position du texte : {{ $data['text_position'] }}
image : <img width="150" src="{{ $data['image'] }}" alt="{{ $data['balise_alt'][$current_lang] }}">

