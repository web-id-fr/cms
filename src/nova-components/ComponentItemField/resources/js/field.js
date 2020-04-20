Nova.booting((Vue, router, store) => {
  Vue.component('index-ComponentItemField', require('./components/IndexField'))
  Vue.component('detail-ComponentItemField', require('./components/DetailField'))
  Vue.component('form-ComponentItemField', require('./components/FormField'))
})
