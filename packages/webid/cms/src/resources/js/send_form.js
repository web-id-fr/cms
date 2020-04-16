import {zipObject} from 'lodash';
import {axiosPost} from "./helpers";
import Lang from 'lang.js/dist/lang.min';
import messages from '../lang/dropzone-traduction';
import Dropzone from 'dropzone/dist/min/dropzone.min';

Dropzone.autoDiscover = false;  // MUST BE JUST AFTER THE IMPORT !

const lang = new Lang({messages});
const maxTotalSize = $('.dropzone').attr('data-max-total-size');

/**
 * Displays possible errors
 * @param error
 * @param form
 */
export function form_error(error, form) {
    form.closest(".popin").find(".alert-success").hide();
    form.closest(".popin").find('.field-error span').remove();
    form.closest(".popin").find('.field-error br').remove();

    if (error.errors && error.errors.length === 0) {
        let $field_message = form.closest('.popin').find('.field-error').last();
        $field_message.append("<span>" + error.message + "</span><br>");
        $field_message.show();
    }

    $.each(error.errors, function (key) {
        let $field_error = form.closest(".popin").find('[name=' + key + ']');
        let $field_message = $field_error.closest('.line_form').find('.field-error');
        $field_message.append("<span>" + error.errors[key] + "</span><br>");
        $field_message.show();
    });

    //scroll to the first error
    $(".popin").animate({
        scrollTop: $('.field-error:visible').first().offset().top
    }, 300);
}

/**
 * Displays possible success
 * @param form
 */
export function form_success(form) {
    form.closest(".popin").find('.field-error span').remove();
    form.closest(".popin").find('.field-error br').remove();
}

/**
 * Récupère les données d'un formulaire et les formate correctement pour la requête ajax
 * @param $form L'instance jQuery du formulaire concerné
 * @returns {{}}
 */
export function extractDataFromForm($form) {
    let serializedData = $form.serializeArray();

    let _names = serializedData.map(item => item.name);
    let _values = serializedData.map(item => item.value);

    // Merge names and values
    return zipObject(_names, _values);
}

$(() => {
    let $dropzone = $(".dropzone");
    let textRemoveFile = lang.get('dropzone.delete-files');
    let textCancelUpload = lang.get('dropzone.dictCancelUpload');
    let textFallbackMessage = lang.get('dropzone.dictFallbackMessage');
    let textFallbackText = lang.get('dropzone.dictFallbackText');
    let textFileTooBig = lang.get('dropzone.dictFileTooBig');
    let textInvalidFileType = lang.get('dropzone.dictInvalidFileType');
    let textResponseError = lang.get('dropzone.dictResponseError');
    let textCancelUploadConfirmation = lang.get('dropzone.dictCancelUploadConfirmation');
    let textMaxFilesExceeded = lang.get('dropzone.dictMaxFilesExceeded');
    let currentLang = $('html').attr('lang');

    function isButtonSubmitDisabled() {
        return !!$(".submit_form").attr('disabled');
    }

    function toggleButtonSubmit() {
        $(".submit_form").prop('disabled', function (i, v) {
            return !v;
        });
    }

    function toggleErrorText() {
        let dropzone_field = $('.dropzone');
        dropzone_field.closest('.line_form').toggleClass("show-error");
        if (dropzone_field.closest('.line_form').hasClass("show-error")) {
            dropzone_field.closest('.line_form').find('.field-error').append("<span>" + lang.get('dropzone.dictMaxSize') + "</span><br>");
        }
    }

    $dropzone.dropzone({
        paramName: "file",
        // Prevents Dropzone from uploading dropped files immediately
        autoProcessQueue: false,
        uploadMultiple: true,
        addRemoveLinks: true,
        parallelUploads: 100,
        dictRemoveFile: textRemoveFile,
        dictCancelUpload: textCancelUpload,
        dictFallbackMessage: textFallbackMessage,
        dictFallbackText: textFallbackText,
        dictFileTooBig: textFileTooBig,
        dictInvalidFileType: textInvalidFileType,
        dictResponseError: textResponseError,
        dictCancelUploadConfirmation: textCancelUploadConfirmation,
        dictMaxFilesExceeded: textMaxFilesExceeded,
        url: route('send.form', currentLang),
        maxFiles: $dropzone.data("maxFiles"),
        error: function (file, errorMessage, xhr) {
            // Calls the function form_error
            form_error(errorMessage, $dropzone.closest("form"));
            // Allow file to be reuploaded !
            file.status = Dropzone.QUEUED;
        },

        init: function () {
            var totalsize = 0.0;
            let myDropzone = this;

            // Change the button to actually tell Dropzone to process the queue.
            $(".submit_form").click(function (e) {
                // Make sure that the form isn't actually being sent.
                e.preventDefault();
                e.stopPropagation();

                let form = $(this);
                let data = extractDataFromForm(form.closest("form"));

                // Checks that at least one file is uploaded
                if (myDropzone.files.length > 0) {
                    myDropzone.processQueue();
                } else {
                    // If dropzone has no files store item without images
                    axiosPost(route('send.form', currentLang), data).then(() => {
                        $("form").trigger('reset');
                        form_success(form);
                    }).catch((data) => {
                        // Calls the function form_error
                        form_error(data.response.data, form);
                    });
                }
            });

            // On sending via dropzone append token and form values
            myDropzone.on("sending", function (file, xhr, formData) {
                $dropzone.closest("form").find(":input:not(:button)").each(function () {
                    formData.append($(this).attr("name"), $(this).val());
                });
                formData.append("_token", $("meta[name=csrf-token]").attr("content"));
            });

            myDropzone.on("addedfile", function (file) {
                // increment total size when we add a file
                totalsize += file.size;
                if (parseFloat((totalsize / 1000000).toFixed(2)) > maxTotalSize && !isButtonSubmitDisabled()) {
                    toggleButtonSubmit();
                    toggleErrorText();
                }
            });

            myDropzone.on("removedfile", function (file) {
                // decrement total size when we remove a file
                totalsize -= file.size;
                if (parseFloat((totalsize / 1000000).toFixed(2)) <= maxTotalSize && isButtonSubmitDisabled()) {
                    toggleButtonSubmit();
                    toggleErrorText();
                }
            });

            myDropzone.on("successmultiple", function () {
                $("form").trigger('reset');
                this.removeAllFiles(true);
            })
        }
    });

    $(".submit_form").click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        let form = $(this);

        if (!form.closest('.form-group').find('.dropzone').length) {
            let data = extractDataFromForm(form.closest("form"));

            axiosPost(route('send.form', lang), data).then(() => {
                $("form").trigger('reset');
                form_success(form);
            }).catch((data) => {
                // Calls the function form_error
                form_error(data.response.data, form);
            });
        }
    });
});
