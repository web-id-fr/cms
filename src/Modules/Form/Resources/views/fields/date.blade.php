<div class="line_form">
    @if( data_get($field, 'label'))
        <label class="input-label">
            {{ data_get($field, 'label', '') }}
            <span>
               @if( data_get($field, 'required')) * @endif
            </span>
        </label>
    @endif
    <input type="date" name="{{ data_get($field, 'field_name') }}" @if( data_get($field, 'required')) required @endif>
    <div class="field-error">
    </div>
</div>
