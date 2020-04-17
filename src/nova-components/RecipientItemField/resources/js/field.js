Nova.booting((Vue, router) => {
    Vue.component('index-RecipientItemField', require('./components/IndexField'));
    Vue.component('detail-RecipientItemField', require('./components/DetailField'));
    Vue.component('form-RecipientItemField', require('./components/FormField'));
})
