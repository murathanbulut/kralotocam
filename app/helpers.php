<?php

use App\Models\Setting;
use Illuminate\Support\Str;

if (! function_exists('site_url')) {
    /**
     * Ön yüz için sonu "/" ile biten kanonik adres üretir.
     * Örn: site_url('binek-arac-cam-degisimi') => https://kralotocam.com/binek-arac-cam-degisimi/
     */
    function site_url(string $path = ''): string
    {
        $path = trim($path, '/');

        return $path === '' ? rtrim(url('/'), '/').'/' : url($path).'/';
    }
}

if (! function_exists('feature')) {
    /** config/kral.php içindeki görsel geliştirme anahtarlarını okur. */
    function feature(string $name): bool
    {
        return (bool) config('kral.features.'.$name, false);
    }
}

if (! function_exists('feature_body_classes')) {
    /** Açık olan özellikler için <body> sınıfları (ör. "fx-slider-text fx-cursor"). */
    function feature_body_classes(): string
    {
        $map = [
            'slider_text_animation' => 'fx-slider-text',
            'cursor_follower' => 'fx-cursor',
            'scroll_header' => 'fx-scroll-header',
        ];

        return implode(' ', array_keys(array_filter(array_flip($map), fn ($feature) => feature($feature))));
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('media_url')) {
    /**
     * Yüklenen dosya yolunu (public disk) veya statik varlık yolunu tam adrese çevirir.
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback ? asset($fallback) : null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, ['images/', '/images/', 'build/'])) {
            return asset(ltrim($path, '/'));
        }

        // Storage::url() APP_URL'e bağlıdır; asset() isteğin geldiği adresi kullandığı için
        // farklı port/alan adlarında da doğru çalışır (public/storage sembolik bağlantısı).
        return asset('storage/'.ltrim($path, '/'));
    }
}

if (! function_exists('address_lines')) {
    /**
     * Adresi son virgülden iki satıra böler; ilçe / il ikinci satırda bütün kalır.
     * "Teksan Sanayi Sitesi, A4 Blok No: 1, Odunpazarı / Eskişehir"
     *   => ["Teksan Sanayi Sitesi, A4 Blok No: 1,", "Odunpazarı / Eskişehir"]
     *
     * @return array<int, string>
     */
    function address_lines(?string $address): array
    {
        $address = trim((string) $address);
        $cut = mb_strrpos($address, ', ');

        if ($cut === false) {
            return [$address];
        }

        return [mb_substr($address, 0, $cut + 1), mb_substr($address, $cut + 2)];
    }
}

if (! function_exists('phone_href')) {
    /** "0532 488 96 03" => "tel:+905324889603" */
    function phone_href(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '90'.substr($digits, 1);
        }

        return 'tel:+'.$digits;
    }
}

if (! function_exists('whatsapp_url')) {
    function whatsapp_url(?string $message = null): string
    {
        $number = preg_replace('/\D+/', '', (string) setting('whatsapp'));
        $text = $message ?? setting('whatsapp_message');

        return 'https://wa.me/'.$number.($text ? '?text='.rawurlencode($text) : '');
    }
}

if (! function_exists('founding_year')) {
    /**
     * Doğrulanmış kuruluş yılı. Doğrulanmadıysa veya geçersizse null döner;
     * böylece hiçbir sayfada, meta veride veya JSON-LD'de yayınlanmaz.
     */
    function founding_year(): ?int
    {
        $year = (int) setting('founding_year');
        $verified = filter_var(setting('founding_year_verified'), FILTER_VALIDATE_BOOLEAN);

        return $verified && $year >= 1900 && $year <= (int) date('Y') ? $year : null;
    }
}

if (! function_exists('experience_years')) {
    /** Doğrulanmış kuruluş yılından bu yana geçen yıl (yalnızca yıl farkı; ek iddia üretmez). */
    function experience_years(): ?int
    {
        $year = founding_year();

        return $year ? (int) date('Y') - $year : null;
    }
}
