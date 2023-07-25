<script setup>
import { onMounted, ref, watch } from 'vue'

const mytextareaRef = ref(null)
const editorglobal = ref({})

const props = defineProps({
  content: {
    type: String,
    required: false,
    default: '',
  },
})
onMounted(() => {
  window.test = tinymce
  tinymce.init({
    target: mytextareaRef.value,
    selector: 'textarea',
    plugins: 'code link image table lists',
    toolbar: 'code | undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table',
    setup: function (editor) {
      editor.on('init', () => {
        editor.setContent(props.content)
      })
      editor.on('init', () => {
        editorglobal.value = editor
      })
    },

    init_instance_callback: function (editor) {
      document.querySelector('.tox-promotion-link').remove()
      document.querySelector('.tox-statusbar__branding').remove()
    },
    visual: false,
    height: 500,
  })
})
defineExpose({
  mostrarContenido,
})
function mostrarContenido() {
  return editorglobal.value.getContent()
}
</script>

<template>
  <div>
    <textarea ref="mytextareaRef" v-on:change="handleInput"></textarea>
    <!-- <button @click="mostrarContenido">Mostrar contenido</button> -->
  </div>
</template>

<style lang="css">
.el-overlay {
  z-index: 1200 !important;
}
</style>