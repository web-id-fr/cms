import axios from 'axios';

//INIT AXIOS
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-Origin'] = location.href;
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

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
export function form_error(error, form) {
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
export function form_success(form) {
    form.closest(".popin").find('.field-error span').remove();
    form.closest(".popin").find('.field-error br').remove();
}

/**
 * @param error
 * @param form
 */
export function form_error_popin(error, form) {
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
export function form_success_popin(form) {
    form.closest(".popin").find('.field-error span').remove();
    form.closest(".popin").find('.field-error br').remove();
}
