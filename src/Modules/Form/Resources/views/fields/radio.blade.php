<div class="line_form">
    @if(!empty(data_get($field, 'field_options')))
        @foreach(data_get($field, 'field_options') as $option)
            @if(!empty(data_get($option, "item.$currentLangKey")))
                <div class="option-container">
                    <input type="radio"
                           name="{{ data_get($field, 'field_name') }}"
                           id="{{ data_get($field, 'field_name') . '-' . $loop->index }}"
                           value="{{ data_get($option, "option.$currentLangKey") }}"
                           @if( data_get($field, 'required')) required @endif>
                    <label for="{{ data_get($field, 'field_name') . '-' . $loop->index }}" class="input-label">
                        {{ data_get($option, "item.$currentLangKey") }}
                    </label>
                </div>
            @endif
        @endforeach
    @endif
    <div class="field-error">
    </div>
</div>
