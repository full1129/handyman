import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from '../router'
import {post,get} from '../request'
Vue.use(Vuex)


export default new Vuex.Store({
  state: {
    user: null,
    auth: false,
    configurations : [],
    currencypostion:'',
    currencysymobl:{},
    discounttype: '%',
    generalsetting:{},
    appsetting:{},
    privacy_policy:{},
    term_conditions:{},
    featured_category:[]
  },

  mutations: {
    setUserData(state, userData) {
      state.user = userData.data
      state.auth = true
      localStorage.setItem('user', JSON.stringify(userData))
      axios.defaults.headers.common.Authorization = `Bearer ${userData.data.api_token}`
    },

    clearUserData() {
      localStorage.removeItem('user')
      router.push({ name: 'frontend-home' })
      location.reload()
    },
    setDashboardData(state, data){
      state.configurations =  data.configurations
      state.generalsetting = data.generalsetting
      state.appsetting = data.app_setting
      state.privacy_policy = data.privacy_policy
      state.term_conditions = data.term_conditions
      state.featured_category = data.category
      var valObj = data.configurations.filter(function(elem){
        if(elem.key === 'CURRENCY_POSITION'){
          state.currencypostion  = elem.value
        }
        if(elem.key === 'CURRENCY_COUNTRY_ID'){
          state.currencysymobl  = elem.country.symbol
        }
      });
    }
  },

  actions: {
    login({ commit }, credentials) {
      return post('login', credentials)
        .then(({ data }) => {
          commit('setUserData', data)
        })
    },
    register({ commit }, data) {
      return post('register', data)
        .then(({ data }) => {
          commit('setUserData', data)
        })
    },
    logout({ commit }) {
      commit('clearUserData')
    },
    dashboardData({commit}){
      return get('dashboard-detail')
      .then(({ data }) => {
        commit('setDashboardData', data)
      })
    }
  },

  getters: {
    isLogged: state => !!state.user,
    userData: state => state.user,
    configurations: state=> state.configurations,
    currencyPostion:state => state.currencypostion,
    currencySymobl:state => state.currencysymobl,
    discountType:state=> state.discounttype,
    generalsetting: state => state.generalsetting,
    appsetting:state => state.appsetting,
    privacy_policy:state=>state.privacy_policy,
    term_conditions:state=>state.term_conditions,
    featured_category:state => state.featured_category
  }
})
