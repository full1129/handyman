<template>
    <section class="our-service light-background editors mar-top"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
    <div class="container">       
        <div class="header-title d-flex align-items-center justify-content-between">
            <h3>{{__('messages.featured')}} {{__('messages.service')}}</h3>
            <router-link :to="{ name: 'services' }"> <span class="text-black pt-2">{{__('messages.see_all')}}
            </span></router-link>
        </div>
       <service-list :is_featured="true"/>      
    </div>
</section>
</template>
<script>
export default {
  name: "FeaturedService"
};
</script>