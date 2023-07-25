import { fileURLToPath, URL } from 'url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import vueI18n from '@intlify/vite-plugin-vue-i18n'

// https://vitejs.dev/config/
export default defineConfig({
  root: process.cwd(),
  base: '/',
  plugins: [
    vue({
      template: {
        compilerOptions: {
          /*     isCustomElement: (tag) => ['AppLayout'].includes(tag), */
        },
      },
    }),
    vueI18n({
      include: path.resolve(path.dirname(fileURLToPath(import.meta.url)), './src/locales/**'),
    }),
  ],
  resolve: {
    alias: {
      '/@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  build: {
    minify: true,
    chunkSizeWarningLimit: 4600,
  },
})
