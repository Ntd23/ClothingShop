import { createApp } from 'vue';
import Antd from 'ant-design-vue';

import App from './App.vue';
import router from './router';
import store from './store/index';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'ant-design-vue/dist/reset.css';


createApp(App)
    .use(Antd)
    .use(store)
    .use(router)
    .mount('#app');
