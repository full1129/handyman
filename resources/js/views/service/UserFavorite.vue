<template>
<div>
  <breadcrumb-component :homeName="this.$route.meta.label" :sectionName="'Service'"/>
   <section class="our-service our-service-lists mar-top mar-bot editors service-card-box user-fav"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
      <div class="container">
        <div v-if="favoriteList.length > 0"  class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 list-inline">
          <div v-for="(data, index) in favoriteList" :key="index" class="col">
            <div class="card">
              <div class="iq-image position-relative">
                <router-link
                  :to="{
                    name: 'service-detail',
                    params: { service_id: data.service_id },
                  }"
                >
                  <div class="img">
                    <img
                      :src="data.service_attchments[0] ? data.service_attchments[0]:baseUrl+'/images/default.png'"
                      alt="image"
                      class="img-fluid w-100  "
                    />
                  </div>
                </router-link>            
              <span class="badge badge-2 bg-primary">{{ data.price_format }}</span>             
            </div>
            <div class="card-body">
              <div class="content-title mb-3">
                <h6 class="" data-bs-toggle="tooltip" data-bs-placement="top" :title="data.name"
                >{{ data.name }}
                </h6> 
                <favorite :servicedata="data.service_id" :favorited="data.is_favourite"  @service-list="userFavorite" />
              </div>
               <p class="para-ellipsis short-description mb-0 text-dark">
                  {{ data.description }}
                </p>
            </div>
          </div>
          </div>
        </div>
        <div v-else class="row">
          <img :src="baseUrl+'/images/frontend/data_not_found.png'"  />
        </div>
      </div>
   </section>
</div>
</template>
<script>
import favorite from '../../components/global-components/favorite/favorite.vue';
import {get} from '../../request'
export default {
  components: { favorite },
  name: "UserFavorite",
  data() {
    return {
      favoriteList: [],
      baseUrl : window.baseUrl
    };
  },
  mounted() {
    this.userFavorite();
  },
  methods: {
    userFavorite() {
      get("user-favourite-service", {
        params:{
          customer_id: this.$store.state.user ? this.$store.state.user.id : 0
        }
      }).then((response) => {
        this.favoriteList = response.data.data;
      });
    },
  },
};
</script>