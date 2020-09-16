export function flushVarnish() {
    return window.axios
        .get('/vendor/card-actions/flush/varnish')
        .then(response => response.data);
}
