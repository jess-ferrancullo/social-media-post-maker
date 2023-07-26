import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/form.js',
                'resources/js/facebook.js',
                'resources/js/instagram.js',
            ],
            refresh: true,
        }),
    ],
});
