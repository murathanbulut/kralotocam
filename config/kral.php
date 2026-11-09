<?php

/*
|--------------------------------------------------------------------------
| Kral Oto Cam - Görsel geliştirme anahtarları
|--------------------------------------------------------------------------
| Her özellik birbirinden bağımsızdır. Beğenilmeyen bir özelliği kapatmak için
| .env dosyasında ilgili değeri false yapın ve "php artisan config:clear" çalıştırın.
| Kapalı özellik sayfaya hiçbir sınıf/stil/davranış eklemez; mevcut koyu tasarım aynen görünür.
|
| Özelliğin kodu ayrıca kendi dosyalarında durur (bkz. docs/VISUAL-ENHANCEMENTS.md),
| böylece istenirse tek bir commit geri alınarak tamamen kaldırılabilir.
*/

return [
    'features' => [
        // A. Slayt değişiminde küçük başlık > başlık > açıklama > butonlar sırasıyla beliren metinler
        'slider_text_animation' => (bool) env('KRAL_FX_SLIDER_TEXT', true),

        // B. Masaüstünde fareyi takip eden imleç efekti (dokunmatik cihazlarda devre dışı)
        'cursor_follower' => (bool) env('KRAL_FX_CURSOR', true),

        // C. Aşağı kaydırırken gizlenen, yukarı kaydırırken üstten beliren header
        'scroll_header' => (bool) env('KRAL_FX_SCROLL_HEADER', true),

        // D. Ana sayfa "Hizmetlerimiz" bölümünde görselli kartlar (kapalıysa mevcut ikonlu kartlar)
        'service_image_cards' => (bool) env('KRAL_FX_SERVICE_IMAGES', true),

        // E. İsteğe bağlı açık tema (varsayılan görünüm her zaman koyu temadır)
        'light_theme' => (bool) env('KRAL_FX_LIGHT_THEME', true),
    ],
];
