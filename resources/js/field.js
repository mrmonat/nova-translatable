Nova.booting((Vue, router) => {
    Vue.component('index-translatable', require('./components/IndexField'));
    Vue.component('detail-translatable', require('./components/DetailField'));
    Vue.component('form-translatable', require('./components/FormField'));
})
