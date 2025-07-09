import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/css/admin.css',
                'resources/css/home.css',
                'resources/js/home.js',

            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Tüm IP'lerden erişime izin verir
        port: 5173, // Vite varsayılan portu
        hmr: {
            host: '192.168.1.246' // ← burayı kendi IP adresinle güncelle
        }
    }
});
