@if(!empty($popins))
    @foreach($popins as $popin)
        Titre : {{ $popin['title'] }}
        Description : {!! $popin['description'] !!}
        Image : <img width="150" src="{{ $popin['image'] }}" alt="{{ $popin['image_alt'] }}">

        ----
        Call to action 1 :
        Afficher le bouton : {{ $popin['display_call_to_action'] }}
        url du bouton : {{ $popin['button_1_url'] }}
        Nom du bouton : {{ $popin['button_1_title'] }}

        ----
        Call to action 2 :
        Afficher le bouton : {{ $popin['display_second_button'] }}
        url du bouton : {{ $popin['button_2_url'] }}
        Nom du bouton : {{ $popin['button_2_title'] }}
    @endforeach
@endif
