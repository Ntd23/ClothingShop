import { createApp } from 'vue';
import App from './App.vue';
import store from './store';
import router from './router';
import Antd from 'ant-design-vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'ant-design-vue/dist/reset.css';
import 'ant-design-vue/dist/reset.css';

createApp(App)
  .use(store).use(router).use(Antd).mount('#app')
