import './bootstrap';
import 'toastr/build/toastr.css';
import { createApp } from 'vue';
 import App from './App.vue';
import router from './router';
import axios from 'axios';
axios.defaults.withCredentials = true;

toastr.options = {
  positionClass: 'toast-top-right', // Confirme la position
  progressBar: true,
  closeButton: true,
  timeOut: 3000,
};
// createApp(App).mount('#app');
createApp(App).use(router).mount('#app');