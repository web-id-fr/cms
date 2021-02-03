Nova.booting((Vue, router, store) => {
  Vue.component('index-article-categories-item-field', require('./components/IndexField'))
  Vue.component('detail-article-categories-item-field', require('./components/DetailField'))
  Vue.component('form-article-categories-item-field', require('./components/FormField'))
})
