import './assets/main.css'
import './assets/base.scss'
import 'primevue/resources/themes/aura-light-green/theme.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from "primevue/config";
import 'bootstrap'
import App from './App.vue'
import router from './router'
import Button from "primevue/button"
import InputNumber from 'primevue/inputnumber';
import axios from 'axios'
import moment from 'moment';
import Toast from 'primevue/toast'
import ToastService from 'primevue/toastservice';
import Loadingform from './components/LoadingForm.vue';
import 'bootstrap/dist/css/bootstrap.min.css'	// Import Bootstrap CSS and JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js'	// Import Bootstrap CSS and JS

const app = createApp(App);
const http = axios.create();
var stage = 'local';
var local = 'http://localhost';  // Ubah IP ke localhost
var live = 'http://next.workfren.com';

app.component('Toast', Toast);


app.use(PrimeVue, {
	ripple: true 
});
app.use(ToastService);
app.use(createPinia())
app.use(router)

var host = stage == 'live' ? live : local;


import Helper from './Helper.js';
app.config.globalProperties.$helper = Helper;

app.component('Loadingform', Loadingform);
app.component('Button', Button);
app.component('InputNumber', InputNumber);
app.config.globalProperties.$http = http
app.config.globalProperties.$moment = moment
app.config.globalProperties.$googleRecaptchaKey = '6LexD-cUAAAAAI631dX2weRbXtwrqfQSTDVqFGvX';
app.config.globalProperties.$host = host;
app.config.globalProperties.$apiserver = host+'/api';
app.config.globalProperties.$publicUrl = host+'/public/uploads/images/';

app.use(router);
app.mount('#app')
