import { defineConfig } from 'vite'
import nodePolyfills from 'rollup-plugin-node-polyfills';

export default defineConfig({
    plugins: [],
    resolve: {
        alias: {
            // version: 'rollup-plugin-node-polyfills/polyfills/version',
        }
    },
    optimizeDeps: {
        esbuildOptions: {
            // Node.js global to browser globalThis
            target: 'esnext',
            define: {
                global: 'globalThis'
            },
            // Enable esbuild polyfill plugins
            plugins: []
        }
    },
    build: {
        target: 'esnext',
        sourcemap: true,
        rollupOptions: {
            plugins: [
                // Enable rollup polyfills plugin
                // used during production bundling
                // @ts-ignore
                // nodePolyfills(),
            ],
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`
            }
        }
    }
})