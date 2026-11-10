<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasActiveOrdering;

    /** Panelde seçilebilen kategoriler (sayfada bu sırayla gruplanır). */
    public const CATEGORIES = [
        'cam' => 'Cam Onarımı ve Değişimi',
        'adas' => 'ADAS ve Kamera Kalibrasyonu',
        'sigorta' => 'Sigorta ve Kasko',
        'diger' => 'Cam Filmi, Ayna ve Diğer Hizmetler',
        'iletisim' => 'Fiyat ve İletişim',
    ];

    protected $fillable = ['question', 'answer', 'category', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? 'Genel';
    }
}
