// require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router';

import router from './Router/index'
import store from './Store/index';
import App from './App.vue'

Vue.use(VueRouter)

/* Ahmed PDF */
import Print from 'vue-print-nb'
Vue.use(Print);

import QrcodeVue from 'qrcode.vue';
Vue.use(QrcodeVue);

import VueSocialSharing from 'vue-social-sharing'
Vue.use(VueSocialSharing);


const app = new Vue({
    el: '#app',
    router,
    store,
    components: { App }
});

// import Echo from "laravel-echo"
//
// window.io = require('socket.io-client');
//
// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':6001'
// });


import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    forceTLS: true,
    wsPort: 443,
    disableStats: true
    // wsHost: '127.0.0.1',
    // wsPort: 6001,
    // wssPort: 6001,
    // forceTLS: false,
    // disableStats: true
});
