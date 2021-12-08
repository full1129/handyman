<template>
<section class="main-breadcrumb light-background">
    <h3 class="text-center mb-0">{{sectionName}}</h3>
    <nav class="d-flex justify-content-center align-items-center">
        <ol class="breadcrumb align-items-center p-0">
            <li class="breadcrumb-item ps-3 active" aria-current="page"> <i aria-hidden="true" class="fa fa-home mr-1"></i> <router-link  :to="{ name: 'frontend-home' }">{{__('messages.home')}}</router-link></li>
            <li class="breadcrumb-item ps-3" aria-current="page">{{homeName}}</li>
        </ol>
    </nav>
</section>
</template>
<script>
export default {
  name: "breadcrumb",
  props:{
      sectionName:{ type: String},
      homeName:{ type: String},
      childSection:{type:String}
  }
};
</script>