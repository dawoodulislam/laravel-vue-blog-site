require('./bootstrap');

import Vue from 'vue'
// window.Vue = require('vue')
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import router from './router'
import store from './store'

Vue.use(ViewUI);

import common from './common'
Vue.mixin(common)
// Vue.component('app', require('./component/app.vue').default)

// console.log(routes)

import App from './component/app.vue'

const app = new Vue({
    router,
    render: h => h(App),
    store,
}).$mount('#app')


// console.log(routes)