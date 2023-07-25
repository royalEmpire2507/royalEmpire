<script setup>
import { watch, ref } from 'vue'
import { useRoute } from 'vue-router'

const keyApp = ref(0)
const route = useRoute()

watch(
  route,
  (newVal) => {
    if (typeof newVal.params.custom !== 'undefined') {
      keyApp.value += 1
    } else if(newVal.name == 'edit|cases' && typeof newVal.params.id !== 'undefined'){
      keyApp.value += 1
    }
  },
  { deep: true }
)
</script>
<template>
  <router-view :key="keyApp"></router-view>
</template>
