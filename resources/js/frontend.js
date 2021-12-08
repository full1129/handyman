require('./bootstrap');

window.Vue = require('vue').default;

import { BootstrapVue } from 'bootstrap-vue'
import Vuex from 'vuex'
import store from './store/index';
import router from './router.js';

import VCalendar from 'v-calendar';

import Default from './layouts/Default.vue';
import './request.js'

import Snackbar from 'node-snackbar/src/js/snackbar.js';
import 'node-snackbar/src/sass/snackbar.sass';
import Vuelidate from 'vuelidate';
import Multiselect from 'vue-multiselect'


Vue.use(VCalendar, { componentPrefix: 'vc' });
Vue.use(Vuex);
Vue.use(Snackbar);
Vue.use(BootstrapVue)
Vue.use(Vuelidate);
Vue.component('multi-select', Multiselect)

Vue.mixin(require('./trans'))

Vue.component('login-component', require('./views/auth/Login.vue').default)
Vue.component('rating-component', require('./components/global-components/rating/ratingstar.vue').default)
Vue.component('breadcrumb-component', require('./components/global-components/breadcrumb/breadcrumb.vue').default)
Vue.component('service-list', require('./components/global-components/service-card/ServiceList.vue').default)

let element = document.getElementById('app');
if (element !== null) {
  window.vm = new Vue({
    router,
    store,
    data: {
      baseUrl: baseUrl
    },
    created() {
      const userInfo = localStorage.getItem('user')
      if (userInfo) {
        const userData = JSON.parse(userInfo)
        this.$store.commit('setUserData', userData)
      }
      axios.interceptors.response.use(
        response => response,
        error => {
          if (error.response.status === 401) {
            this.$store.dispatch('logout')
          }
          return Promise.reject(error)
        }
      )
      this.$store.dispatch('dashboardData')
    },
    render: h => h(Default)
  }).$mount('#app');
}