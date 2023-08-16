import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';
import App from '@/view/popup.vue';
import store from '@/store';

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../view/pages/home.vue'),
    },
  ],
});

createApp(App).use(router).use(store).mount('#app');
