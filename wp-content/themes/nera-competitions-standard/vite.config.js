import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
  plugins: [vue(), tailwindcss()],

  build: {
    outDir: 'dist',
    manifest: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/main.js'),
        'instant-wins-vue': resolve(__dirname, 'frontend/instant-wins-vue-init.js'),
        'product-gallery-vue': resolve(
          __dirname,
          'frontend/components/ProductGallery/index-vue.js'
        ),
        'winners-modal-vue': resolve(__dirname, 'frontend/components/shared/WinnersModal-vue.js'),
        'winners-vue': resolve(__dirname, 'frontend/winners-vue-init.js'),
        'archive-winners-vue': resolve(__dirname, 'frontend/archive-winners-vue-init.js'),
      },
      output: {
        manualChunks: {
          'vue-vendor': ['vue'],
        },
      },
    },
  },

  server: {
    // CORS configuration for WordPress
    cors: true,
    // Expose to network for WordPress access
    host: true,
    port: 5173,
    strictPort: true,
    // HMR configuration
    hmr: {
      host: 'localhost',
    },
    // Watch PHP files and trigger reload when they change
    watch: {
      // Watch all PHP files in the theme directory
      include: ['**/*.php'],
      // Ignore node_modules and dist
      ignored: ['**/node_modules/**', '**/dist/**'],
    },
  },

  // Configure CSS to rescan content on change
  css: {
    // Force Tailwind to rescan PHP files for utility classes
    devSourcemap: true,
  },
});
