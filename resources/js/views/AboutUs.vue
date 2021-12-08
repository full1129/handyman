<template>
<div>
  <breadcrumb-component  :sectionName="this.$route.meta.label"/>
  <section-one :data="jsonData.sectionOne"/>
  <section-two :data="jsonData.sectionTwo" />
  <section-four :data="jsonData.sectionFour" />
  <section-five :data="jsonData.sectionFive" />
  <section-six :data="jsonData.sectionSix"/>
</div>
</template>
<script>
import getJson from '../about-us.json'
import SectionFive from '../components/aboutus-components/SectionFive.vue'
import SectionFour from '../components/aboutus-components/SectionFour.vue'
import SectionOne from '../components/aboutus-components/SectionOne.vue'
import SectionSix from '../components/aboutus-components/SectionSix.vue'
import SectionTwo from '../components/aboutus-components/SectionTwo.vue'
export default {
  name: "AboutUs",
  components: { SectionOne, SectionTwo, SectionFive, SectionSix, SectionFour },
  data(){
    return{
        jsonData:{}
    }
  },
  mounted() {
    this.jsonData = getJson
  },
}
</script>