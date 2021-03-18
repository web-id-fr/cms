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
