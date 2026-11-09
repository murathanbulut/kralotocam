import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';

// Referans temanın yazı tipleri (Sora, Playfair Display, DM Sans) derleme sırasında
// indirilip yerel olarak sunulur; ziyaretçi tarayıcısı üçüncü taraf font sunucusuna bağlanmaz.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/light-theme.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Sora', { weights: [300, 400, 500, 600, 700], subsets: ['latin', 'latin-ext'] }),
                bunny('Playfair Display', { weights: [400, 500, 600, 700], subsets: ['latin', 'latin-ext'] }),
                bunny('DM Sans', { weights: [400, 500, 700], subsets: ['latin', 'latin-ext'] }),
            ],
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
