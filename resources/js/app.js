import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './bootstrap';
import routes from './routes';
import main_panel from './components/panel/main_panel'


Vue.use(BootstrapVue);
Vue.use(VueRouter);


const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

const app = new Vue({
    el: '#app',
    components: {main_panel},
    router,
});
export default app;