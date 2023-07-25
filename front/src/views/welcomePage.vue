<script setup>
// import { ref, reactive } from 'vue'
// import { EmailEditor } from 'vue-email-editor'
import AppLayout from '../layouts/AppLayout.vue'

// const emailEditor = ref(null)

// function editorLoaded() {
//   console.log('editorLoaded')
//   // Pass the template JSON here
//   // this.$refs.emailEditor.editor.loadDesign({});
// }
// // called when the editor has finished loading
// function editorReady() {
//   console.log('editorReady')
// }

// function saveDesign() {
//   emailEditor.value.editor.saveDesign((design) => {
//     console.log('design', design)
//   })

//   emailEditor.value.editor.exportHtml((data) => {
//     console.log('html', data.html.replace(/\n/g, ' '))
//   })
// }
</script>

<template>
  <AppLayout>
    <!-- <div>
      <EmailEditor width="100%" ref="emailEditor" v-on:load="editorLoaded" v-on:ready="editorReady" />
    </div>
    <button v-on:click="saveDesign">Save Design</button> -->
  </AppLayout>
</template>

