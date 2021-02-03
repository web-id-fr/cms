<div class="line_form">
    <div class="select_custom">
        <select name="{{ data_get($field, 'field_name') }}" @if( data_get($field, 'required')) required @endif>
            @foreach(data_get($field, 'field_options') as $option)
                <option value="{{ data_get($option, "attributes.option.$currentLangKey") }}"> {{ data_get($option, "attributes.option.$currentLangKey") }}</option>
            @endforeach
        </select>
    </div>
</div>
