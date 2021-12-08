<template>

  <section v-if="data" class="container about-us mar-top mar-bot">  
    <div class="row">
      <div class="col-lg-6 about-images">
        <div class="row row-cols-lg-2 row-cols-1">
            <div class="col mb-4 h-100">
                <div class="service-box">
                    <h2 class="citi-count title-white mb-3">{{data.card.counter}}</h2>
                    <p class="title-white mb-0">{{data.card.text}}</p>
                </div>
            </div>
            <div class="col mb-4 h-100">
                <img class="w-100" :src="baseUrl + '/images/frontend/' + data.image_one">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <img class="w-100" :src="baseUrl + '/images/frontend/' + data.image_two">
            </div>                    
        </div>
      </div>
       <div class="col-lg-6 mt-lg-0 mt-5">
            <div class="about-content">
                <h3 class="mb-5">{{data.title}}</h3>
                <div class="icon-box-wrapper">
                    <div class="icon-content">
                        <h6 class="mb-1">{{data.sub_title}}</h6>
                        <p class="mb-0">{{data.text}}</p>
                    </div>
                </div>
            </div>                
        </div>
    </div>
  </section>
</template>
<script>
export default {
  name: "SectionOne",
  props:{
    data:{type:Object}
  },
  data(){
    return{
        baseUrl:window.baseUrl
    }
  }
}
</script>