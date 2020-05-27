
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// // window.Vue = require('vue');
import Vue from 'vue'
import ElementUI from 'element-ui'
import Axios from 'axios'

import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'
locale.use(lang)

//Vue.use(ElementUI)
Vue.use(ElementUI, {size: 'small'})
Vue.prototype.$eventHub = new Vue()
Vue.prototype.$http = Axios

// import VueCharts from 'vue-charts'
// Vue.use(VueCharts); 
Vue.component('tenant-configurations-visual', require('../../../../../resources/js/views/tenant/configurations/visual.vue').default);

Vue.component('x-graph', require('../../../../../resources/js/components/graph/src/Graph.vue').default);
Vue.component('x-graph-line', require('../../../../../resources/js/components/graph/src/GraphLine.vue').default);
Vue.component('tenant-dashboard-index', require('./views/index.vue').default);
 

const app = new Vue({
    el: '#main-wrapper'
});
