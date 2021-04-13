<div class="line_form input-container input-container--text">
    <label class="input-label" for="{{ form_field_id($field, $id_form) }}">
        {{ data_get($field, 'label', '') }}
        @if( data_get($field, 'required')) * @endif
    </label>
    <input type="text" id="{{ form_field_id($field, $id_form) }}" name="{{ data_get($field, 'field_name') }}"
           placeholder="{{ data_get($field, 'placeholder', '') }}" @if( data_get($field, 'required')) required @endif />
    <div class="field-error">
    </div>
</div>
