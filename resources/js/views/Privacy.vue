<template>
    <div>
        <breadcrumb-component  :sectionName="this.$route.meta.label"/>
        <div class="container mar-top mar-bot terms-content">            
            <div v-if="privacy_policy.value != null " v-html="privacy_policy.value">
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
    name: "Privacy",
    data(){
        return{
            baseUrl:window.baseUrl
        }
    },
    computed: {
        ...mapGetters(['privacy_policy',]),
    },
}
</script>