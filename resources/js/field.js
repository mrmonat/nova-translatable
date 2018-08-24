Nova.booting((Vue, router) => {
    Vue.component('detail-translatable', require('./components/DetailField'));
    Vue.component('form-translatable', require('./components/FormField'));
})
