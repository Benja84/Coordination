import axios from 'axios';
axios.defaults.withCredentials = true;

import { createApp } from 'vue';
import Login from './login/Login.vue';
createApp(Login).mount('#login');