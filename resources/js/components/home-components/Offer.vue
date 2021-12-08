<template>
<section class="our-offer editors mar-top"  data-iq-gsap="onStart">
    <div class="container">
        <div class="header-title text-center">
             <h3>{{__('messages.best_offer')}}</h3>
        </div>
        <div  class="swiper-container offerSlider">
            <div class="swiper-wrapper">
                <div  class="swiper-slide" v-for="(data,index) in serviceList" :key="index">
                    <div class="offer-box">
                        <div class="offer-info">
                            <div class="offer-title">
                                <span class="text-primary sub-title">Best deal month</span>
                                <a href="#" class="main-title"><h3 class="text">{{data.name}}</h3></a>
                            </div>
                            <div class="offer-text">
                                <b>{{data.discount}}% off</b> on item
                            </div>
                        </div>
                        <div class="offer-image">
                            <img :src="data.attchments[0]" >
                            <div class="offer-disc">
                                <span class="disc-name">From {{data.price_format}}</span>
                            </div>
                        </div>                               
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
</template>
<script>
import {get} from '../../request'
import Swiper, { Navigation, Pagination, Parallax, Autoplay } from 'swiper'
Swiper.use([Navigation, Pagination, Parallax, Autoplay])
export default {
  name: "Offer",
  data() {
    return {
        serviceList:[],
        baseUrl:window.baseUrl
    };
  },
  mounted() {
       var offerSlider = new Swiper(".offerSlider", {
        slidesPerView: 2,
        spaceBetween: 15,
        loopFillGroupWithBlank: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            320: { slidesPerView: 1 },
            768: { slidesPerView: 2},
            1199: { slidesPerView: 2 },
            1400: { slidesPerView: 2 },
            1500: { slidesPerView: 2 },
            1920: { slidesPerView: 2},
        },
    });
      this.getOfferData()
  },
  methods:{
    getOfferData(){
        get("service-list", {
            params: {
                is_discount: true,
                per_page: 5
            }
        })
        .then((response) => {
            this.serviceList = response.data.data;
        });
    }
  }
};
</script>