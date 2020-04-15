Nova.booting((Vue, router) => {
    Vue.component('index-ServiceItemField', require('./components/IndexField'));
    Vue.component('detail-ServiceItemField', require('./components/DetailField'));
    Vue.component('form-ServiceItemField', require('./components/FormField'));
})
