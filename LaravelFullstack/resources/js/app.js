require('./bootstrap');

window.Vue = require('vue');
import common from './common';
Vue.mixin(common)
//router
import router from './router'
//store
import store from './store'
//IView
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);

export const bus = new Vue();

Vue.component('main-app', require('./components/MainApp.vue').default);

const app = new Vue({
    el: '#app',
    store,
    router,
    data() {
    	return {

    	}
    }
});
