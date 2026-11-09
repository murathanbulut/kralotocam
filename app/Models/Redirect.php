<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = ['from_path', 'to_path', 'status_code', 'is_active', 'hits'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /** Yolu karşılaştırma için normalleştirir: baştaki/sondaki eğik çizgiler kaldırılır, küçük harfe çevrilir. */
    public static function normalize(string $path): string
    {
        $path = (string) parse_url($path, PHP_URL_PATH);

        return mb_strtolower(trim(rawurldecode($path), '/'));
    }

    protected static function booted(): void
    {
        static::saving(function (self $redirect) {
            $redirect->from_path = static::normalize($redirect->from_path);
        });
    }
}
