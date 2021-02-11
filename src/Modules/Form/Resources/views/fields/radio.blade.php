<div class="line_form">
    @foreach(data_get($field, 'field_options') as $option)
        <label>
            <input type="radio" name="{{ data_get($field, 'field_name') }}"
                   value="{{ data_get($option, "attributes.option.$currentLangKey") }}"
                   @if( data_get($field, 'required')) required @endif>
            {{ data_get($option, "attributes.option.$currentLangKey") }}
        </label>
    @endforeach
    <div class="field-error">
    </div>
</div>
