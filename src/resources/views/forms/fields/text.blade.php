<div class="line_form">
    <input type="text" name="{{ data_get($field, 'field_name') }}" placeholder="{{ data_get($field, 'placeholder', '') }}" @if( data_get($field, 'required')) required @endif />
    <div class="field-error">
    </div>
</div>
