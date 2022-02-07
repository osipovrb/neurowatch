require('./bootstrap');
require('./components');

import Vue from 'vue';

import axios from 'axios';

import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);

import VueClipboard from 'vue-clipboard2';
Vue.use(VueClipboard);

import VueElementLoading from "vue-element-loading";
Vue.component("VueElementLoading", VueElementLoading);

import { BootstrapVue } from 'bootstrap-vue';
Vue.use(BootstrapVue, {
    BModal: {
        cancelTitle: 'Отмена',
        okTitle: 'Сохранить',
    }
});

import '../sass/app.scss';

require('./notifications');

import { router } from './router';
import { store } from './store';

require('./websockets');

var app = new Vue({
	el: '#app',
	router: router,
    store: store,
});
