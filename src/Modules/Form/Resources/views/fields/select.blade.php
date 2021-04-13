<div class="line_form input-container input-container--select">
    <label class="input-label" for="{{ form_field_id($field, $id_form) }}">
        {{ data_get($field, 'label', '') }}
        @if( data_get($field, 'required')) * @endif
    </label>
    @if(!empty(data_get($field, 'field_options')))
        <select id="{{ form_field_id($field, $id_form) }}" name="{{ data_get($field, 'field_name') }}" @if( data_get($field, 'required')) required @endif>
            @if(!empty(data_get($field, 'field_options')))
                @foreach(data_get($field, 'field_options') as $option)
                    @if(!empty(data_get($option, "item.$currentLangKey")))
                        <option value="{{ data_get($option, "item.$currentLangKey") }}">
                            {{ data_get($option, "item.$currentLangKey") }}
                        </option>
                    @endif
                @endforeach
            @endif
        </select>
    @endif
</div>
