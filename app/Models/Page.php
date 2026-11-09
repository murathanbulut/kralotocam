<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasActiveOrdering;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'cover_image',
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
