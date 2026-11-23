import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve, dirname } from 'node:path'
import { fileURLToPath } from 'node:url'

const __dirname = dirname(fileURLToPath(import.meta.url))

const env = loadEnv('', __dirname, 'VITE_')
const vitePort = Number(env.VITE_PORT)

if (!Number.isInteger(vitePort) || vitePort < 1 || vitePort > 65535) {
  throw new Error('Please configure a valid VITE_PORT in .env')
}

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src/vue')
    }
  },
  publicDir: false,
  server: {
    host: '0.0.0.0',
    port: vitePort,
    strictPort: true,
    cors: true,
    hmr: {
      host: 'localhost'
    }
  },
  build: {
    outDir: 'public/vue',
    emptyOutDir: true,
    rollupOptions: {
      input: resolve(__dirname, 'src/vue/main.js'),
      output: {
        entryFileNames: 'app.js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name?.endsWith('.css')) {
            return 'main.css'
          }
          return 'assets/[name]-[hash][extname]'
        }
      }
    }
  }
})