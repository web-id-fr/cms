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
                    @component("form::fields.services" , [
                        'services' => data_get($form, 'services'),
                        'title' => data_get($form, 'title_service')
                    ])
                    @endcomponent
                @endif
                @foreach(data_get($form, 'fields') as $field)
                    @component("form::fields." . data_get($field, 'field_type') , [
                        'field' => $field,
                    ])
                    @endcomponent
                @endforeach
                <input type="hidden" name="form_id" value="{{ data_get($form, 'id') }}">
                <input type="hidden" name="confirmation_email_name" value="{{ data_get($form, 'confirmation_email_name') }}">
                @if(!empty($form_extra))
                    <input type="hidden" name="extra" value="{{ json_encode($form_extra) }}">
                @endif
            </div>
            <button type="submit"
                    class="button button_bg_black fullwidth submit_desktop submit_form">{{ data_get($form, 'cta_name') }}</button>
            <p class="mentions">{!! data_get($form, 'rgpd_mention') !!}</p>
        </div>
    </div>
</form>


@section('scripts')
    <script type="text/javascript" src="{{ asset('js/send_form.js') }}"></script>
@endsection
