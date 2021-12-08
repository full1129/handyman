<template>
<div>
  <VueStarRating 
      :inline="true" 
      :increment="0.5" 
      :star-size="10" 
      :read-only="readonly" 
      :show-rating="showrating"
      :rating="ratingvalue"
      @rating-selected="setCurrentSelectedRating"
  ></VueStarRating>
</div>
</template>
<script>
import VueStarRating from 'vue-star-rating'
export default {
  name: 'RatingStar',
  components:{
    VueStarRating
  },
  props: {
    fill: {
      type: [Boolean, String],
      default: false
    },
    readonly:{
      type: [Boolean, String],
      default: false
    },
    showrating:{
      type: [Boolean, String],
      default: false
    },
    ratingvalue:{
      type: [Number, String],
      default: 0
    }
  },
  data(){
    return {}
  },
  methods:{
    setCurrentSelectedRating: function(rating) {
      this.$emit('add-service-rating', rating);
    }
  }
}
</script>
