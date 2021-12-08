<template>
  <div class="container">
    <div class="row">
      <section
        class="editors mar-top"
        data-iq-gsap="onStart"
        data-iq-position-y="70"
        data-iq-rotate="0"
        data-iq-trigger="scroll"
        data-iq-ease="power.out"
        data-iq-opacity="0"
      >
        <service-list :providerid="$route.params.provider_id" />
      </section>
    </div>
  </div>
</template>
<script>
export default {
  name: "providerService"
};
</script>