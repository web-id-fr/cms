<div class="line_form line_form input-container input-container--file">
    @if( data_get($field, 'label'))
        <label class="input-label">
            {{ data_get($field, 'label', '') }}
            <span>
               @if( data_get($field, 'required')) * @endif
            </span>
        </label>
    @endif
    <div class="dropzone" id="dropzone-{{ $id_form }}" data-max-files="{{ $maxFiles }}" data-max-total-size="{{ $maxTotalSize }}">
        <div class="dz-message" data-dz-message>
            <span>{{ __('template.form.dropzone_label', ['nb' => $maxFiles]) }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
    <div class="field-error">
    </div>
</div>
