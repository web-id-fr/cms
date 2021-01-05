<div class="line_form date_reservation">
    <div class="date">
        <div class="title">{{ data_get($field, 'date_field_title') }}</div>
        <input name="{{ data_get($field, 'field_name') }}" type="text" placeholder="{{ data_get($field, 'date_field_placeholder') }}" @if( data_get($field, 'required')) required @endif/>
    </div>
    <div class="time">
        <div class="title">{{ data_get($field, 'time_field_title') }}</div>
        <input name="{{ data_get($field, 'field_name_time') }}" type="text" placeholder="{{ data_get($field, 'time_field_placeholder') }}" @if( data_get($field, 'required')) required @endif/>
    </div>
    <div class="duration">
        <div class="title">{{ data_get($field, 'duration_field_title') }}</div>
        <div class="select_custom">
            <select name="{{ data_get($field, 'field_name_duration') }}" id="select_duration" @if( data_get($field, 'required')) required @endif>
                @foreach(data_get($field, 'field_options') as $option)
                    <option value="{{ data_get($option, "attributes.item.$currentLang") }}"> {{ data_get($option, "attributes.item.$currentLang") }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
