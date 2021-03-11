<div class="line_form">
    <div class="dropzone" data-max-files="{{ $maxFiles }}" data-max-total-size="{{ $maxTotalSize }}">
        <div class="dz-message" data-dz-message>
            <span>{{ __('template.form.dropzone_label', ['nb' => $maxFiles]) }}</span>
            <i class="fa fa-plus"></i>
        </div>
    </div>
    <div class="field-error">
    </div>
</div>
