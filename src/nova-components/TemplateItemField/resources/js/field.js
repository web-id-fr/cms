Nova.booting((Vue, router) => {
    Vue.component('index-TemplateItemField', require('./components/IndexField'));
    Vue.component('detail-TemplateItemField', require('./components/DetailField'));
    Vue.component('form-TemplateItemField', require('./components/FormField'));
})
