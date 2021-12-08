<template>
<div>
<breadcrumb-component :homeName="'Category List'" :sectionName="this.$route.meta.label" />
 <section class="our-category our-category-lists" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
    <div class="container">
      <div v-if="category.length > 0" class="row">
        <div class="mar-top mar-bot category-box">
          <ul class="row row-cols-2 row-cols-lg-3 row-cols-xl-4 list-inline">
            <li class="col mb-4" v-for="(data, index) in category" :key="index">
              <router-link :to="{
                name: 'category-service',
                params: { category_id: data.id },
              }">
                <div class="card mb-0 bg-soft-primary circle-clip-effect bg-img undefined">
                    <div class="card-body service-card undefined">
                        <img :src="data.category_image" alt="image" class="img-fluid "> 
                    </div>                                
                </div>
                <div class="card-body category-content undefined text-center">
                    <h5 class="categories-name">
                        {{ data.name }}
                    </h5>
                    <p class="category-desc mb-0"> {{ data.description }}</p>
                </div>
              </router-link>
            </li>
          </ul>
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
import {get} from '../../request'
export default {
  name: "Categories",
  data() {
    return {
      category: [],
      baseUrl:window.baseUrl
    };
  },
  mounted() {
    this.getCategory();
  },
  methods: {
    getCategory() {
      get("category-list", {}).then((response) => {
        this.category = response.data.data;
      });
    },
  },
};
</script>