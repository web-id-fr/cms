Nova.booting((Vue, router, store) => {
  Vue.component(
      'index-advanced-url',
      require('./components/IndexField').default
  )
  Vue.component(
      'detail-advanced-url',
      require('./components/DetailField').default
  )
  Vue.component(
      'form-advanced-url',
      require('./components/FormField').default
  )
})
