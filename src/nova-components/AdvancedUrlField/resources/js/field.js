Nova.booting((Vue, router, store) => {
  Vue.component('index-advanced-url', require('./components/IndexField'))
  Vue.component('detail-advanced-url', require('./components/DetailField'))
  Vue.component('form-advanced-url', require('./components/FormField'))
})
