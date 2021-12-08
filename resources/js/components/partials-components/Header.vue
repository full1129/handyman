<template>
    <nav class="nav navbar theme navbar-expand-xl bg-white iq-navbar navbar-scroll" aria-label="Main navigation">
      <div class="container-fluid p-0">
        <a href="#" class="navbar-brand">
          <img :src="generalsetting.site_logo ? generalsetting.site_logo : baseUrl +'/images/logo.svg'" class="img-fluid logo" alt="logo" />
        </a>
        <div class="input-group search-text search-device-lg" style="width: auto;">
            <input type="text" placeholder="Search" class="form-control"> 
            <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="search-icon">
              <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle> 
              <path d="M18.0186 18.4851L21.5426 22" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>
        <div class="d-flex align-items-center">
          <div class="search-text search-device-sm">
            <i class="fas fa-search s-icon"></i>
            <div class="search-content">
              <form>
                <div class="input-group  mb-0">
                  <input type="text" placeholder="Search" class="form-control"> 
                  <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="search-icon">
                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle> 
                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </div>                
              </form>            
            </div>        
          </div>
           <b-navbar-toggle target="navbarSupportedContent">
            <span class="navbar-toggler-icon">
                <span class="navbar-toggler-bar bar1 mt-2"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
          </b-navbar-toggle>
        </div>
       <b-collapse id="navbarSupportedContent" is-nav>
          <ul
            class="top-menu navbar-nav ms-auto navbar-list mb-2 mb-lg-0"
          >
            <li class="nav-item">
              <router-link :to="{ name: 'frontend-home' }" 
                :class="(currentRouteName === 'frontend-home' ? activeRouteClass + ' nav-link' : 'nav-link' )" 
                >{{__('messages.home')}}</router-link
              >
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'categories' }"   :class="(currentRouteName === 'categories' ? activeRouteClass + ' nav-link' : 'nav-link' )" 
                >{{__('messages.category')}}</router-link
              >
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'services' }"   :class="(currentRouteName === 'services' || currentRouteName === 'service-detail' || currentRouteName === 'category-service'  || currentRouteName === 'provider-service' ? activeRouteClass + ' nav-link' : 'nav-link' )" 
                >{{__('messages.service')}}</router-link
              >
            </li>
            <li v-if="isLogged" class="nav-item">
              <router-link :to="{ name: 'booking' }"   :class="(currentRouteName === 'booking' || currentRouteName === 'bookingdetail'  ? activeRouteClass + ' nav-link' : 'nav-link' )" 
                >{{__('messages.booking')}}</router-link
              >
            </li>
            <li v-if="isLogged" class="nav-item dropdown">
              <a
                href="#"
                class="nav-link search-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                @click="openDropdown"
              >
                <img
                  :src="userData.profile_image ? userData.profile_image : baseUrl+'/images/user/user.png'"
                  alt="User-Profile"
                  class="img-fluid avatar avatar-50 avatar-rounded"
                />
                <span>{{userData.display_name}}</span>
              </a>
              <ul
                :class="`dropdown-menu dropdown-menu-end user-dropdown ${
                  dropdownClass ? 'show' : ''
                }`"
                aria-labelledby="dropdownMenuButton1"
              >
                <li>
                  <router-link class="dropdown-item" :to="{ name: 'user-favourite-service' }"
                    >{{__('messages.favorite_list')}}</router-link
                  >
                </li>
                <li>
                  <a href="#" class="dropdown-item" @click="getHomePage()"
                    >{{__('messages.dashboard')}}</a
                  >
                </li>
                <li>
                  <a href="#" class="dropdown-item" @click="logout"
                    >{{__('messages.logout')}}</a
                  >
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a v-if="!isLogged" class="nav-link" @click="login" href="#">
                 {{__('messages.login')}}
              </a>
              <login-component ref="openModal" />

            </li>
            <li class="nav-item btn-nav-link" v-if="!isLogged">
              <button class="btn btn-primary" v-b-modal="'my-modal1'">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M9.87651 15.2063C6.03251 15.2063 2.74951 15.7873 2.74951 18.1153C2.74951 20.4433 6.01251 21.0453 9.87651 21.0453C13.7215 21.0453 17.0035 20.4633 17.0035 18.1363C17.0035 15.8093 13.7415 15.2063 9.87651 15.2063Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M9.8766 11.886C12.3996 11.886 14.4446 9.841 14.4446 7.318C14.4446 4.795 12.3996 2.75 9.8766 2.75C7.3546 2.75 5.3096 4.795 5.3096 7.318C5.3006 9.832 7.3306 11.877 9.8456 11.886H9.8766Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    
                  <path d="M19.2036 8.66919V12.6792" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    
                  <path d="M21.2497 10.6741H17.1597" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                 
                </svg>
                  {{__('messages.register')}}
              </button>
              <Register />
            </li>
          </ul>
      </b-collapse>
      </div>
    </nav>
</template>
<script>
import { mapGetters } from "vuex";
import Register from "../../views/auth/Register.vue";

export default {
  name: "Header",
  data() {
    return {
      email: "",
      password: "",
      dropdownClass: false,
      baseUrl:window.baseUrl,
      activeRouteClass: 'active'
    };
  },
  mounted(){
    jQuery(window).on('scroll', function (e) {
      if (jQuery(this).scrollTop() > 10) {
        jQuery('.navbar').addClass('menu-sticky');
      } else {
        jQuery('.navbar').removeClass('menu-sticky');
      }
    });
    jQuery(".search-device-sm .s-icon").on('click',function () {
      jQuery(".search-device-sm").toggleClass('search-open');
    });
  },
  components: {
    Register,
  },
  computed: {
    ...mapGetters(["isLogged","userData",'generalsetting',]),
    currentRouteName() {
      return this.$route.name;
    }
  },
  methods: {
    openDropdown() {
      this.dropdownClass = !this.dropdownClass;
    },

    logout() {
      this.$store.dispatch("logout");
    },

    getHomePage(){
      window.location.href = baseUrl + "/home";
    },

    login(){
      this.$refs.openModal.show();
    }
  },
};
</script>
