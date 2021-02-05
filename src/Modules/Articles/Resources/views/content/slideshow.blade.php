Layout : slideshow
Titre : {{ $data['slideshow_select']['title'] }}
Affichage des fléches : {{ $data['slideshow_select']['js_controls'] }}
Animation auto : {{ $data['slideshow_select']['js_animate_auto'] }}
Vitesse de défilement : {{ $data['slideshow_select']['js_speed'] }}
Slides :
@foreach($data['slideshow_select']['slides']  as $slide)
    Titre : {{ $slide['title'] }}
    Description : {!! $slide['description'] !!}
    Nom du bouton : {{ $slide['cta_name'] }}
    Url du bouton : {{ $slide['cta_url'] }}
    Url : {{ $slide['url'] }}
    Image : <img width="150" src="{{ $slide['image'] }}">
@endforeach
