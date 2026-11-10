<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasActiveOrdering;

    /** Özel liste gösteren kurumsal sayfa şablonları (adres panelden değişse de şablon korunur). */
    public const TEMPLATE_INSURANCE = 'insurance';

    public const TEMPLATE_FLEET = 'fleet';

    public const TEMPLATES = [
        self::TEMPLATE_INSURANCE => 'Anlaşmalı sigortalar listesi',
        self::TEMPLATE_FLEET => 'Filo kuruluşları listesi',
    ];

    /** Kurumsal yan menüde ve sayfa kenar çubuğunda gösterilen sayfalar. */
    public const CORPORATE_SLUGS = ['hakkimizda', 'misyonumuz', 'vizyonumuz'];

    protected $fillable = [
        'title', 'slug', 'template', 'excerpt', 'content', 'cover_image',
        'seo_title', 'seo_description', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function url(): string
    {
        return site_url($this->slug);
    }
}
