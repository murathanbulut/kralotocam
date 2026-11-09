<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasActiveOrdering;

    protected $fillable = [
        'title', 'menu_title', 'slug', 'excerpt', 'content', 'cover_image', 'icon',
        'gallery_images', 'seo_title', 'seo_description', 'seo_keywords',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function url(): string
    {
        return site_url($this->slug);
    }

    public function label(): string
    {
        return $this->menu_title ?: $this->title;
    }
}
