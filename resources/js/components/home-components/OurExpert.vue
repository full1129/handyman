<template>
 <section class="our-provider editors mar-top mar-bot"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
    <div class="container">
      <div class="header-title d-flex align-items-center justify-content-between">
        <h3>{{__('messages.provider')}}</h3>
        <router-link :to="{ name: 'provider' }" class="text-black pt-2"
          >{{__('messages.see_all')}}
          <i class="fas fa-long-arrow-alt-right"></i>
        </router-link>
      </div>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-4 list-inline">
        <div v-for="(provider, index) in providers" :key="index" class="col">
          <div class="provider-box">
            <div class="img-box">
                <router-link
                  :to="{
                    name: 'provider-service',
                    params: { provider_id: provider.id },
                  }"
                >
                  <img :src="provider.profile_image" class="team-img" alt="">
                </router-link>
                <div class="certi-box">
                    <img :src="baseUrl + '/images/frontend/certi.png'" alt=""> 
                </div>
            </div>
            <div class="provider-box-content">
                <h5>{{provider.display_name}}</h5>
                <span class="primary-color">{{provider.providertype}}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import {get} from '../../request'
export default {
  name: "OurExpert",
  data() {
    return {
      providers: [],
      baseUrl:window.baseUrl
    };
  },
  mounted() {
    get("user-list", {
        params: {
          user_type: "provider",
          per_page: 4,
        },
      })
      .then((response) => {
        this.providers = response.data.data;
      });
  },
};
</script>
