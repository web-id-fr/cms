<div class="line_form input-container input-container--radio">
    @if( data_get($field, 'label'))
        <label class="input-label">
            {{ data_get($field, 'label', '') }}
            <span>
               @if( data_get($field, 'required')) * @endif
            </span>
        </label>
    @endif
    @if(!empty(data_get($field, 'field_options')))
        @foreach(data_get($field, 'field_options') as $option)
            @if(!empty(data_get($option, "item.$currentLangKey")))
                <div class="option-container">
                    <input type="radio"
                           name="{{ data_get($field, 'field_name') }}"
                           id="{{ str_slug($id_form . '-' . data_get($option, "option.$currentLangKey") . '-' . $loop->index) }}"
                           value="{{ data_get($option, "option.$currentLangKey") }}"
                           @if( data_get($field, 'required')) required @endif>
                    <label
                            for="{{ str_slug($id_form . '-' . data_get($option, "option.$currentLangKey") . '-' . $loop->index) }}"
                            class="input-label">
                        {{ data_get($option, "item.$currentLangKey") }}
                    </label>
                </div>
            @endif
        @endforeach
    @endif
    <div class="field-error">
    </div>
</div>
