import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import App from '@/view/popup.vue';
import store from '@/store';
import '@/assets/scss/popup.scss';

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'welcome',
      component: () => import('../view/pages/welcome.vue'),
    },
    {
      path: '/home',
      name: 'home',
      component: () => import('../view/pages/home.vue'),
    },
  ],
});

createApp(App).use(router).use(store).mount('#app');
