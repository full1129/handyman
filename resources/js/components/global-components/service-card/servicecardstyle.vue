<template>
    <div :class="`card text-center bg-transparent circle-clip-effect ${classname}`" >
        <div :class="`card-body service-card ${innerclass}`">
            <img :src="image" alt="image" class="img-fluid" >
        </div>
    </div>
</template>
<script>

export default {
  name: 'servicecardstyle',
  props: {
    image: { type: String },
    classname: { type: String },
    innerclass: { type: String }
  }
}
</script>
