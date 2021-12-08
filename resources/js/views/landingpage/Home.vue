<template>
  <div>
    <banner />
    <category />
    <service/>
    <offer/>
    <customer/>
    <app-download/>
    <discount-service/>
    <our-expert />
  </div>
</template>

<script>
import AppDownload from '../../components/home-components/AppDownload.vue';
import Banner from "../../components/home-components/Banner.vue";
import Category from "../../components/home-components/Category.vue";
import Customer from '../../components/home-components/Customer.vue';
import DiscountService from '../../components/home-components/FeaturedService.vue';
import Offer from '../../components/home-components/Offer.vue';
import OurExpert from "../../components/home-components/OurExpert.vue";
import Service from "../../components/home-components/Service.vue";
export default {
  name: "Home",
  components: {
    Banner,
    Category,
    Service,
    OurExpert,
    Offer,
    Customer,
    AppDownload,
    DiscountService

  }
};
</script>
