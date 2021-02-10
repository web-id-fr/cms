<div class="line_form">
    @foreach(data_get($field, 'field_options') as $option)
        <div>
            <input type="radio" id="huey" name="{{ data_get($field, 'field_name') }}"
                   value="{{ data_get($option, "attributes.option.$currentLangKey") }}"
                   @if( data_get($field, 'required')) required @endif>
            <label for="{{ data_get($option, "attributes.option.$currentLangKey") }}">
                {{ data_get($option, "attributes.option.$currentLangKey") }}
            </label>
        </div>
    @endforeach
    <div class="field-error">
    </div>
</div>
