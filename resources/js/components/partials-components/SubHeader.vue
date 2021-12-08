<template>
  <div class="cat-menu-bar bg-primary">
    <div class="nav-scroller">
      <div class="container-fluid  p-0">
        <div class="nav-scroll-main mx-auto">
          <nav
            class="nav nav-underline bg-primary header-center pb-0"
            aria-label="Secondary navigation"
          >
              <router-link
              active-class="active"
              v-for="(item, index) in featured_category"
              :to="{ name: 'category-service', params: { category_id: item.id } }"
              :key="index"
              class="nav-link"
              >{{ item.name }}</router-link
            >
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {get} from '../../request'
import { mapGetters } from "vuex";
export default {
  name: "SubHeader",
  data() {
    return {
      subheader: [],
    };
  },
  computed: {
    ...mapGetters(['featured_category']),
  },
  mounted() {
    get("category-list", {
        params: {
          per_page: "all",
        },
      })
      .then((response) => {
        this.subheader = response.data.data;
      });
  },
};
</script>
