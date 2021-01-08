// Nova.booting((Vue, router, store) => {
//   Vue.component('custom-resource-toolbar', require('./components/Tool'))
// })

Nova.booting((Vue, router) => {
    // Vue.component('custom-detail-toolbar', require('./components/CustomDetailToolbar'));
    // Vue.component('templates-detail-toolbar', require('./components/TemplatesDetailToolbar'));
    Vue.component('custom-create-header', require('./components/CustomDetailToolbar'));
    Vue.component('templates-create-header', require('./components/TemplatesDetailToolbar'));
})
