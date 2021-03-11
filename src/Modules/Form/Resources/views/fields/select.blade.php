<div class="line_form">
    <div class="select_custom">
        @if(!empty(data_get($field, 'field_options')))
            <select name="{{ data_get($field, 'field_name') }}" @if( data_get($field, 'required')) required @endif>
                @foreach(data_get($field, 'field_options') as $option)
                    @if(!empty(data_get($option, "option.$currentLangKey")))
                        <option value="{{ data_get($option, "option.$currentLangKey") }}"> {{ data_get($option, "option.$currentLangKey") }}</option>
                    @endif
                @endforeach
            </select>
        @endif
    </div>
</div>
