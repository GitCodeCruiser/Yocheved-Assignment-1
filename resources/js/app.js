import "./validation-rules";
import "./axios.config";
import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import BootstrapVue from "bootstrap-vue";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';
import CKEditor from '@ckeditor/ckeditor5-vue2';
import emitter from './eventBus';
import constants from './constants';

Vue.use(BootstrapVue);
Vue.use(VueToast);
Vue.use( CKEditor );
Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);
Vue.prototype.$emitter = emitter;
Vue.prototype.$constants = constants;


new Vue({
    router,
    render: (h) => h(App),
}).$mount("#app");
