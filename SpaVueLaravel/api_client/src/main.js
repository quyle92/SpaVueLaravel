import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

require('@/store/subscribe');
//axios.defaults.baseURL = 'http://test.com/apiAuth/public/api/'
axios.defaults.baseURL = 'http://127.0.0.1:8000/api/'
Vue.config.productionTip = false

store.dispatch('auth/attempt', localStorage.getItem('token')).then( () => {//(1)
	new Vue({
	  router,
	  store,
	  render: h => h(App)
	}).$mount('#app')

}).catch( () => {//(1)
	new Vue({
	  router,
	  store,
	  render: h => h(App)
	}).$mount('#app')
});

/*
Note
 */
// then() and catch() is to to prevent Flickering when  vue instance  get initilized.
