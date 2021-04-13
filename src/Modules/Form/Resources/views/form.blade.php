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
                        'id_form' => $data_popin
                    ])
                    @endcomponent
                @endforeach
                <input type="hidden" name="form_id" value="{{ data_get($form, 'id') }}">
                <input type="hidden" name="confirmation_email_name" value="{{ data_get($form, 'confirmation_email_name') }}">
                @if(!empty($form_extra))
                    <input type="hidden" name="extra" value="{{ encrypt($form_extra) }}">
                @endif
            </div>
            <button type="submit"
                    class="button button_bg_black fullwidth submit_desktop submit_form">{{ data_get($form, 'cta_name') }}</button>
            <div class="alert-success hide">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="24px" width="24px" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                {{ __('template.form.success_message') }}
            </div>
            <div class="mentions pad_popin">
                {!! data_get($form, 'rgpd_mention') !!}
            </div>
        </div>
    </div>
</form>


@section('scripts')
    <script type="text/javascript" src="{{ asset('js/send_form.js') }}"></script>
@endsection
