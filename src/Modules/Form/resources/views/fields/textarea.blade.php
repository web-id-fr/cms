<div class="line_form">
    <textarea name="{{ data_get($field, 'field_name') }}" placeholder="{{ data_get($field, 'placeholder', '') }}" @if( data_get($field, 'required')) required @endif></textarea>
    <div class="field-error">
    </div>
</div>
