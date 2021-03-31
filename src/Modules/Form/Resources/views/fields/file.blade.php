<div class="line_form">
    @if( data_get($field, 'label'))
        <label class="input-label">
            {{ data_get($field, 'label', '') }}
            <span>
               @if( data_get($field, 'required')) * @endif
            </span>
        </label>
    @endif
    <div class="dropzone" data-max-files="{{ $maxFiles }}" data-max-total-size="{{ $maxTotalSize }}">
        <div class="dz-message" data-dz-message>
            <span>{{ __('template.form.dropzone_label', ['nb' => $maxFiles]) }}</span>
            <i class="fa fa-plus"></i>
        </div>
    </div>
    <div class="field-error">
    </div>
</div>
