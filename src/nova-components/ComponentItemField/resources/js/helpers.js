import Vue from 'vue';
import Toasted from 'vue-toasted';
Vue.use(Toasted);

export function showToast(message, defaultMessage = 'An unexpected error occured.') {
    Vue.toasted.show(message || defaultMessage, {
        duration: 6000,
        position: 'bottom-right',
        action: {
            text: 'x',
            onClick: (e, toastObject) => {
                toastObject.goAway(0);
            }
        }
    });
}

export function successToast(message, defaultMessage = 'An unexpected error occured.') {
    Vue.toasted.success(message || defaultMessage, {
        duration: 6000,
        position: 'bottom-right',
        action: {
            text: 'x',
            onClick: (e, toastObject) => {
                toastObject.goAway(0);
            }
        }
    });
}

export function errorToast(message, defaultMessage = 'An unexpected error occured.') {
    Vue.toasted.error(message || defaultMessage, {
        duration: 6000,
        position: 'bottom-right',
        action: {
            text: 'x',
            onClick: (e, toastObject) => {
                toastObject.goAway(0);
            }
        }
    });
}
