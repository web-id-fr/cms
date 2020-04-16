Nova.booting((Vue, router) => {
    Vue.component('index-FieldItemField', require('./components/IndexField'));
    Vue.component('detail-FieldItemField', require('./components/DetailField'));
    Vue.component('form-FieldItemField', require('./components/FormField'));
})
