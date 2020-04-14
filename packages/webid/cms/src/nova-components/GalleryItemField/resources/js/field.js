Nova.booting((Vue, router) => {
    Vue.component('index-GalleryItemField', require('./components/IndexField'));
    Vue.component('detail-GalleryItemField', require('./components/DetailField'));
    Vue.component('form-GalleryItemField', require('./components/FormField'));
})
