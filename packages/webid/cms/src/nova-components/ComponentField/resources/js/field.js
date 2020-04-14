Nova.booting((Vue, router) => {
    Vue.component('index-ComponentField', require('./components/IndexField'));
    Vue.component('detail-ComponentField', require('./components/DetailField'));
    Vue.component('form-ComponentField', require('./components/FormField'));
})
