import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/simplyCountdown-cyber.min.css',
                'resources/js/app.js',
                'resources/js/simplyCountdown.umd.js',
                'resources/js/countdown.js'
            ],
            refresh: true,
        }),
    ],
});
