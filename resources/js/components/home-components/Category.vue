<template>
  <section class="our-category" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
     <div class="container">
        <div  class="mar-top mar-bot category-box">
          <div class="header-title d-flex justify-content-between mb-4">
            <h3>{{__('messages.category')}}</h3>
            <router-link :to="{ name: 'categories' }" class="text-black pt-2"
              >{{__('messages.see_all')}}
              <i class="fas fa-long-arrow-alt-right"></i>
            </router-link>
          </div>
          <ul v-if="category.length > 0" class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-6 list-inline">
            <li class="col mar-bot-res" v-for="(cat, index) in category" :key="index">
              <router-link
                :to="{
                  name: 'category-service',
                  params: { category_id: cat.id },
                }"
              >
                <servicecardstyle :image="cat.category_image" />
              </router-link>
            </li>
          </ul>
          <div v-else class="row">
            <img :src="baseUrl+'/images/frontend/data_not_found.png'"  />
        </div>
        </div>
    </div>
  </section>
</template>
<script>
import Servicecardstyle from "../global-components/service-card/servicecardstyle.vue";
import {get} from '../../request'

export default {
  name: "Category",
  data() {
    return {
      category: [],
      baseUrl:window.baseUrl
    };
  },
  mounted() {
      get("category-list", {
        params: {
          per_page: 6,
        },
      })
      .then((response) => {
        this.category = response.data.data;
      });
  },
  components: {
    Servicecardstyle,
  },
};
</script>