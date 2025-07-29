import { createRouter, createWebHistory } from 'vue-router';
import Login      from '../login/Login.vue';
import App        from '../App.vue';   // your existing medical app

const routes = [
  { path: '/login',  name: 'login',  component: Login },
  { path: '/',       name: 'dashboard', component: App, meta: { auth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  axios.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : '';

  if (to.meta.auth && !token) return next({ name: 'login' });
  if (to.name === 'login' && token) return next({ name: 'dashboard' });
  next();
});

export default router;