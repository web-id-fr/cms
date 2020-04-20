Nova.booting((Vue, router) => {
    Vue.component('index-MenuItemField', require('./components/IndexField'));
    Vue.component('detail-MenuItemField', require('./components/DetailField'));
    Vue.component('form-MenuItemField', require('./components/FormField'));
})
