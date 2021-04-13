<div class="line_form input-container input-container--url">
    @if( data_get($field, 'label'))
        {{ data_get($field, 'label', '') }}
        @if( data_get($field, 'required')) * @endif
    </label>
    <input type="url" id="{{ form_field_id($field, $id_form) }}" name="{{ data_get($field, 'field_name') }}"
           placeholder="{{ data_get($field, 'placeholder', '') }}" @if( data_get($field, 'required')) required @endif/>
    <div class="field-error">
    </div>
</div>
