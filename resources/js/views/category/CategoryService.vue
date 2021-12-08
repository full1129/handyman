<template>
<div>
  <breadcrumb-component :sectionName="'Service List'" :homeName="this.$route.name"  />
  <section class="our-service our-service-lists mar-top mar-bot editors service-card-box"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
    <div class="container">
      <service-list :categoryid="$route.params.category_id" :key="componentKey"  />
    </div>
  </section>
</div>
</template>
<script>
export default {
  name: "CategoryService",
  data() {
    return {
      componentKey: 0
    };
  },
  props: {
    category_id: { type: Number },
    name: {type: String}
  },
  mounted() {
  },
  methods:{
     forceRerender() {
      this.componentKey += 1;  
    }
  },
  watch: { 
     '$route.params.category_id': {
        handler: function(category_id,old) {
           this.forceRerender()
        },
        deep: true,
        immediate: true
      }
  }
};
</script>