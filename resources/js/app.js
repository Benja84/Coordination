import './bootstrap';
import 'toastr/build/toastr.css';
import { createApp } from 'vue';
import App from './App.vue';

toastr.options = {
  positionClass: 'toast-top-right', // Confirme la position
  progressBar: true,
  closeButton: true,
  timeOut: 3000,
};
createApp(App).mount('#app');