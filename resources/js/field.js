Nova.booting(Vue => {
    Vue.component('index-nova-inline-relationship', require('./components/IndexField').default);
    Vue.component('detail-nova-inline-relationship', require('./components/DetailField').default);
    Vue.component('form-nova-inline-relationship', require('./components/FormField').default);

    Vue.config.devtools = true;
});
