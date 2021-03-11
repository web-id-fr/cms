<div class="line_form">
    @foreach(data_get($field, 'field_options') as $option)
        @if(!empty(data_get($option, "option.$currentLangKey")))
            <label>
                <input type="radio" name="{{ data_get($field, 'field_name') }}"
                       value="{{ data_get($option, "option.$currentLangKey") }}"
                       @if( data_get($field, 'required')) required @endif>
                {{ data_get($option, "option.$currentLangKey") }}
            </label>
        @endif
    @endforeach
    <div class="field-error">
    </div>
</div>
