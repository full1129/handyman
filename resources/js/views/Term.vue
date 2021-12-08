<template>
<div>
    <breadcrumb-component  :sectionName="this.$route.meta.label"/>
    <div class="container mar-top mar-bot terms-content">        
        <div v-if="term_conditions.value != null "  v-html="term_conditions.value">
        </div>  
        <div v-else>
            <img :src="baseUrl+'/images/frontend/data_not_found.png'"  />
        </div>               
    </div>
</div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    name: "Term",
    mounted(){
    },
    data() {
        return {
            baseUrl:window.baseUrl
        }
    },
    computed: {
        ...mapGetters(['term_conditions',]),
    },
}
</script>