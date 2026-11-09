<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    public const CACHE_KEY = 'site_settings';

    protected $fillable = ['key', 'value'];

    /**
     * Varsayılan değerler: veritabanında kayıt yoksa kullanılır.
     * Bilgiler proje gereksinimlerinde verilen doğrulanmış işletme bilgileridir.
     */
    public const DEFAULTS = [
        'site_name' => 'Kral Oto Cam',
        'site_tagline' => 'Eskişehir Oto Cam Servisi',
        'logo' => null,
        'favicon' => null,
        'phone_gsm' => '0532 488 96 03',
        'phone_landline' => '0222 228 22 22',
        'whatsapp' => '905324889603',
        'whatsapp_message' => 'Merhaba, araç camım hakkında bilgi almak istiyorum.',
        'email' => 'info@kralotocam.com',
        'address' => 'Teksan Sanayi Sitesi, A4 Blok No: 1, Odunpazarı / Eskişehir',
        'address_locality' => 'Odunpazarı',
        'address_region' => 'Eskişehir',
        'address_country' => 'TR',
        'working_hours' => null,
        'map_embed_url' => 'https://www.google.com/maps?q=Teksan+Sanayi+Sitesi+A4+Blok+Odunpazar%C4%B1+Eski%C5%9Fehir&output=embed',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Teksan+Sanayi+Sitesi+A4+Blok+No+1+Odunpazar%C4%B1+Eski%C5%9Fehir',
        'facebook_url' => null,
        'instagram_url' => null,
        'x_url' => null,
        'youtube_url' => null,
        'footer_text' => 'Eskişehir\'de araç camı onarımı ve değişimi, ADAS kamera kalibrasyonu, cam filmi ile dikiz ve yan ayna uygulamalarında profesyonel servis.',
        'seo_home_title' => 'Kral Oto Cam | Eskişehir Oto Cam Değişimi ve Onarımı',
        'seo_home_description' => 'Kral Oto Cam; Eskişehir\'de araç camı onarımı ve değişimi, ADAS kamera kalibrasyonu, cam filmi ile dikiz ve yan ayna onarımında profesyonel servis desteği sunar.',
        'seo_default_description' => 'Kral Oto Cam, Eskişehir Teksan Sanayi Sitesi\'nde oto cam onarımı, değişimi ve ADAS kalibrasyonu hizmeti verir.',
        'seo_og_image' => null,
        'google_site_verification' => null,
        'home_about_title' => 'Kral Oto Cam',
        'home_about_text' => null,
        'home_about_image' => null,
        'cookie_notice' => 'Sitemizde yalnızca sitenin çalışması için gerekli çerezler kullanılmaktadır.',
    ];

    /** İstek içi bellek (her setting() çağrısında önbellek deposuna gidilmesini önler). */
    protected static ?array $memo = null;

    public static function flushMemo(): void
    {
        static::$memo = null;
        Cache::forget(self::CACHE_KEY);
    }

    public static function allValues(): array
    {
        return static::$memo ??= Cache::rememberForever(self::CACHE_KEY, function () {
            $stored = [];

            try {
                if (Schema::hasTable('settings')) {
                    $stored = static::query()->pluck('value', 'key')->all();
                }
            } catch (\Throwable) {
                $stored = [];
            }

            return array_merge(self::DEFAULTS, array_filter($stored, fn ($v) => $v !== null && $v !== ''));
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::allValues()[$key] ?? $default;
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => is_array($value) ? json_encode($value) : $value]);
        }

        static::flushMemo();
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushMemo());
        static::deleted(fn () => static::flushMemo());
    }
}
