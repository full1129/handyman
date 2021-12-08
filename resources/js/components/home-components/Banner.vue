<template>
<div>
  <div class="swiper-container mainBanner">
    <div class="swiper-wrapper">
        <div v-for="(slider,index) in sliderData" :key="index" class="swiper-slide item-slide"   v-bind:style="{ backgroundImage: 'url(' + slider.slider_image  + ')' }">
            <div class="inner-content">
                <h2>{{slider.title}}</h2>
                <p class="short-text">{{slider.description}}</p>
            </div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</div>
</template>
<script>
import {get} from '../../request'
import Swiper, { Navigation, Pagination, Parallax, Autoplay } from 'swiper'
Swiper.use([Navigation, Pagination, Parallax, Autoplay])
export default {
  name: "Banner",
  data() {
    return{
      sliderData:[],
      defualt: window.baseUrl + '/images/default.png'
    }
  },
  mounted(){
    var swiper = new Swiper(".mainBanner", {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        breakpoints: {
          320: { slidesPerView: 1 },
          550: { slidesPerView: 1 },
          991: { slidesPerView: 1},
          1400: { slidesPerView: 1 },
          1500: { slidesPerView: 1 },
          1920: { slidesPerView: 1},
      },
    });
    this.getSlider()
  },
  methods:{
    getSlider(){
      get("slider-list", {})
      .then((response) => {
        this.sliderData = response.data.data;
      });
    }
  }
};
</script>