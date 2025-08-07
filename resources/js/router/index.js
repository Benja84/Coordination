import { createRouter, createWebHistory } from 'vue-router';
import Login      from '../login/Login.vue';
import CreateUser from '../user/User-create.vue'
import App        from '../App.vue';   // your existing medical app

const routes = [
  { 
    path: '/',
    name: 'dashoard', 
    component: App,
    meta: { auth: true },
    children: [] // Les enfants seront gérés dans App.vue
  },
  { 
    path: '/users/create', 
    name: 'create-user', 
    component: CreateUser,
    meta: { requiresAdmin: true, auth: true }
  },
  // { 
  //   path: '/', 
  //   component: App,
  //   meta: { auth: true },
  //   children: [
  //     { 
  //       path: '/users/create', 
  //       name: 'create-user', 
  //       component: CreateUser,
  //       meta: { requiresAdmin: true, auth: true }
  //     },
  //   ]
  // },
  { path: '/login',  name: 'login',  component: Login },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  // const token = localStorage.getItem('token');
  // axios.defaults.headers.common['Authorization'] = token ? `Bearer ${token}` : '';

  // if (to.meta.auth && !token) return next({ name: 'login' });
  // if (to.name === 'login' && token) return next({ name: 'dashboard' });
  // 1. Skip API call for public pages
  if (to.path === '/login') return next();

  // 2. Fetch user only once
  let user = null;
  try {
    const { data } = await axios.get('/user', { withCredentials: true });
    user = data;
  } catch {
    user = null;
  }

  if (to.meta.auth && !user) return next({ name: 'login' });
  if (to.meta.requiresAdmin && !user?.is_admin) return next('/');
  next();
});

export default router;