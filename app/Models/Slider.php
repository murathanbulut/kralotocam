<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasActiveOrdering;

    protected $fillable = [
        'subtitle', 'title', 'description', 'image', 'image_alt',
        'primary_button_text', 'primary_button_url',
        'secondary_button_text', 'secondary_button_url',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function primaryUrl(): string
    {
        return static::resolveUrl($this->primary_button_url, 'iletisim');
    }

    public function secondaryUrl(): string
    {
        return static::resolveUrl($this->secondary_button_url, 'hizmetler');
    }

    /** Tam adres, çapa (#) ve tel/mailto bağlantılarını olduğu gibi bırakır; göreli yolları kanonik adrese çevirir. */
    public static function resolveUrl(?string $url, string $fallback): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return site_url($fallback);
        }

        if (preg_match('#^(https?:|mailto:|tel:|\#)#i', $url)) {
            return $url;
        }

        [$path, $fragment] = array_pad(explode('#', $url, 2), 2, null);

        return site_url($path).($fragment ? '#'.$fragment : '');
    }
}
