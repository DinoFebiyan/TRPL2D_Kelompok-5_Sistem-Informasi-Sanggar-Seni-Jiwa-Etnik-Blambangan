import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        // Bind to all interfaces so Vite is reachable inside Docker
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            // Browser connects to localhost, not the container hostname
            host: 'localhost',
            port: 5173,
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
