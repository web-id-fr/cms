<div class="line_form">
    <input type="tel" name="{{ data_get($field, 'field_name') }}" placeholder="{{ data_get($field, 'placeholder', '') }}" @if( data_get($field, 'required')) required @endif/>
    <div class="field-error">
    </div>
</div>
