<form class="form-contact form-group" enctype="multipart/form-data">
    @honeypot
    <div class="content_form">
        <div class="inner">
            <h2>{{ data_get($form, 'title') }}</h2>
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
                <button type="submit" class="button button_bg_black fullwidth submit_desktop submit_form">Envoyer</button>
            </div>
        </div>
    </div>
</form>


@section('scripts')
    <script type="text/javascript" src="{{ asset('js/send_form.js') }}"></script>
@endsection
