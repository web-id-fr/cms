<div class="form_separator"></div>
<div class="title_section_form">{{ $title }}</div>
<div class="line_form">
    <div class="select_custom">
        <select name="service">
            @foreach($services as $service)
                <option value="{{ data_get($service, 'id') }}">{{ data_get($service, 'name') }}</option>
            @endforeach
        </select>
    </div>
</div>
