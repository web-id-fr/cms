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
