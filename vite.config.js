import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/home.css',
                'resources/css/dataAnalysis.css',
                'resources/css/category.css',
                'resources/css/equipment.css',
                'resources/css/comingGoing.css',
                'resources/css/fault.css',
                'resources/css/equipmentStatus.css',
                'resources/css/reporting.css',
                'resources/css/statusCheck.css',
                'resources/css/stock.css',
                'resources/css/users.css',
                'resources/css/works.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/home.js',
                'resources/js/dataAnalysis.js',
                'resources/js/category.js',
                'resources/js/comingGoing.js',
                'resources/js/equipment.js',
                'resources/js/equipmentStatus.js',
                'resources/js/fault.js',
                'resources/js/reporting.js',
                'resources/js/statusCheck.js',
                'resources/js/stock.js',
                'resources/js/users.js',
                'resources/js/works.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Tüm IP'lerden erişime izin verir
        port: 5173, // Vite varsayılan portu
        hmr: {
            host: '192.168.1.200', // Kendi bilgisayarının ağ IP'si
        },
    },
});
