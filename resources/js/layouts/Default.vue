<template>
  <div>
    <div id="loading">
      <Loader />
    </div>
    <main class="main-content">
      <div class="position-relative">
        <header>
          <Header />
          <sub-header />
         </header>
      </div>
      <div class="conatiner-fluid content-inner p-0">
        <router-view></router-view>
      </div>
      <Footer />
    </main>
  </div>
</template>
<script>
import Header from "../components/partials-components/Header.vue";
import Loader from "../components/partials-components/Loader.vue";
import Footer from "../components/partials-components/Footer.vue";
import SubHeader from '../components/partials-components/SubHeader.vue';
export default {
  name: "Default",
  components: {
    Loader,
    Header,
    Footer,
    SubHeader,
  }
};
</script>