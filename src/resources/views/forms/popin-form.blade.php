<div class="popin right_to_left" id="{{ $data_popin }}">
    <div>
        <div class="closePopin" data-popin="{{ $data_popin }}"><span></span></div>
        <form class="form-contact form-group" enctype="multipart/form-data">
            @honeypot
            <div class="content_popin">
                <div class="inner">
                    <h2 class="pad_popin">{{ data_get($form, 'title') }}</h2>
                    <div class="form_container">
                        <div>
                            {!! data_get($form, 'description') !!}
                        </div>
                        @if(data_get($form, 'services'))
                            @component("forms.fields.services" , [
                                'services' => data_get($form, 'services'),
                                'title' => data_get($form, 'title_service')
                            ])
                            @endcomponent
                        @endif
                        @foreach(data_get($form, 'fields') as $field)
                            @component("forms.fields." . data_get($field, 'field_type') , [
                                'field' => $field,
                                'lang' => $lang
                            ])
                            @endcomponent
                        @endforeach
                        <input type="hidden" name="form_id" value="{{ data_get($form, 'id') }}">
                    </div>
                    <div class="pad_popin">
                        <button type="submit" class="button button_bg_black fullwidth submit_desktop submit_form">gr
                        </button>
                    </div>
                </div>
            </div>
            <button type="submit" class="button button_bg_black fullwidth submit_mobile animation_btn" value="Envoyer"></button>
        </form>
    </div>
</div>

@section('scripts')
    <script type="text/javascript" src="{{ asset('cms/js/send_form.js') }}"></script>
@endsection
