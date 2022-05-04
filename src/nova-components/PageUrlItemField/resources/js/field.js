import IndexField from './components/IndexField.vue';
import DetailField from './components/DetailField.vue';

Nova.booting((Vue, router, store) => {
  Vue.component('index-PageUrlItemField', IndexField)
  Vue.component('detail-PageUrlItemField', DetailField)
})
