import {axiosPost} from './helpers';

$(document).ready(function () {
    // NEWSLETTER
    $('.newsletter').on('submit', function (e) {
        e.preventDefault();
        let email = $(this).find('input[type="email"]').val();
        let feedback_newsletter = $(this).find('.feedback_newsletter');
        let lang = $('html').attr('lang');
        axiosPost(lang + '/ajax/newsletter', {
            'email': email
        }).then(function (response) {
            let message = response.data;
            $(this).trigger('reset');
            feedback_newsletter.removeClass('error');
            feedback_newsletter.addClass('success');
            feedback_newsletter.text(message.message);
        }).catch(function (error) {
            feedback_newsletter.removeClass('success');
            feedback_newsletter.addClass('error');
            let data = error.response.data;
            try {
                feedback_newsletter.text(data.errors.email[0]);
            } catch (e) {
                feedback_newsletter.text('Server error !');
            }
        });
    });
});
