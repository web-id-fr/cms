Nova.booting((Vue) => {
  Vue.component('index-confirmation-email-item-field', require('./components/IndexField'))
  Vue.component('detail-confirmation-email-item-field', require('./components/DetailField'))
  Vue.component('form-confirmation-email-item-field', require('./components/FormField'))
})
