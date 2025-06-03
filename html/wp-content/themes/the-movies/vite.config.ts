import { defineConfig } from 'vite';
import path from 'path';
import browserSync from 'browser-sync';

const bs = browserSync.create();

export default defineConfig({
    root: 'src',
    base: '/wp-content/themes/the-movies/dist/',
    build: {
        outDir: '../dist',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                main: path.resolve(__dirname, 'src/js/main.ts'),
                style: path.resolve(__dirname, 'src/scss/main.scss'),
            },
            output: {
                entryFileNames: 'js/[name].js',
                chunkFileNames: 'js/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash][extname]',
            },
        },
    },
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
    plugins: [
        {
            name: 'vite-plugin-browsersync',
            closeBundle() {
                if(!bs.active){
                    bs.init({
                        proxy: 'http://localhost:8080',
                        files: [
                            'dist/**/*.css',
                            'dist/**/*.js',
                            '**/*.php'
                        ],
                        open: true,
                        notify: true,
                        injectChanges: false,
                        reloadDebounce: 500,
                    });
                } else {
                    bs.reload();
                }
            }
        }
    ]
});
