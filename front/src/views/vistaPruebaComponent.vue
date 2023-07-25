<template>
 <AppLayout>
<p>holaaaaaaaaaaaaaaaaa</p>
<div class="col-sm-12 col-xs-12 col-md-12 col-lg-8">
      <label >Producto</label>
      <VueSearch v-model="producto" :mode="'object'">
      </VueSearch>
 </div>
  
 </AppLayout>
  
</template>

<script>

import AppLayout from '../layouts/AppLayout.vue';
import VueSearch from "../components/VueSearch.vue";

export default {
components : {
    AppLayout,
    VueSearch
},
 data(){
    return {
        
    }
 }
}
</script>