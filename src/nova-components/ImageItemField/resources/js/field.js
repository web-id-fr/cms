Nova.booting((Vue, router, store) => {
  Vue.component('index-ImageItemField', require('./components/IndexField'))
  Vue.component('detail-ImageItemField', require('./components/DetailField'))
  Vue.component('form-ImageItemField', require('./components/FormField'))
})
