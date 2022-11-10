import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import fs from 'fs'

const server = {
  port: 5173,
}
// if (DEV) {
//   server.https = {
//     key: fs.readFileSync('./local_certs/key.pem'),
//     cert: fs.readFileSync('./local_certs/cert.pem'),
//   }
// }

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
    vue({
      reactivityTransform: true,
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  server,
})
