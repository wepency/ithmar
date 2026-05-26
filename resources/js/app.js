// require('./bootstrap');

import { createApp } from 'vue'

import router from './Router/index'
import store from './Store/index';
import App from './App.vue'

/* Ahmed PDF */
import print from 'vue3-print-nb'

import QrcodeVue from 'qrcode.vue';

import VueSocialSharing from 'vue-social-sharing'

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const app = createApp({
    components: { App }
});

app.use(router);
app.use(store);
app.use(print);
app.use(VueSocialSharing);

app.component('qrcode-vue', QrcodeVue);

app.mount('#app');


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    forceTLS: true,
    wsPort: 443,
    disableStats: true
});
