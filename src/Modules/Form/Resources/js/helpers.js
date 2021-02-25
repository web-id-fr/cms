import $ from "jquery";
import axios from 'axios';

//INIT AXIOS
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-Origin'] = location.href;

let token = axiosGet('/csrf');
let varnishToken;

token.then(function(response){
    varnishToken=  response.data;

    $('meta[name="csrf-token"]').remove();
    $('head').append( '<meta name="csrf-token" content="' + varnishToken  +'">' );

    axios.defaults.headers.common['X-CSRF-TOKEN'] = varnishToken;
});

/**
 * Axios POST
 * @param url
 * @param data
 * @return {Promise<AxiosResponse<T>>}
 */
export function axiosPost(url, data) {
    return axios.post(url, data);
}

/**
 * Axios GET
 * @param url
 * @param data
 * @return {Promise<AxiosResponse<T>>}
 */
export function axiosGet(url, data) {
    return axios.get(url, {
        params: data
    });
}

/**
 * @param error
 * @param form
 */
export function formError(error, form) {
    form.closest("form").find(".alert-success").hide();
    form.closest("form").find('.field-error span').remove();
    form.closest("form").find('.field-error br').remove();

    if (error.errors && error.errors.length === 0) {
        let $field_message = form.closest('form').find('.field-error').last();
        $field_message.append("<span>" + error.message + "</span><br>");
        $field_message.show();
    }

    $.each(error.errors, function (key) {
        let $field_error = form.closest("form").find('[name=' + key + ']');
        let $field_message = $field_error.closest('.line_form').find('.field-error');
        $field_message.append("<span>" + error.errors[key] + "</span><br>");
        $field_message.show();
    });

    //scroll to the first error
    $("form").animate({
        scrollTop: $('.field-error:visible').first().offset().top
    }, 300);
}

/**
 * @param form
 */
export function formSuccess(form) {
    form.closest(".popin").find('.field-error span').remove();
    form.closest(".popin").find('.field-error br').remove();
}

/**
 * @param error
 * @param form
 */
export function formErrorPopin(error, form) {
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
 * @param form
 */
export function formSuccessPopin(form) {
    form.closest(".popin").find('.field-error span').remove();
    form.closest(".popin").find('.field-error br').remove();
}
