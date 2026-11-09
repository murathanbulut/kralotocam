<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryAlbum extends Model
{
    use HasActiveOrdering;

    protected $fillable = [
        'title', 'slug', 'description', 'cover_image',
        'seo_title', 'seo_description', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function activeImages(): HasMany
    {
        return $this->images()->active()->ordered();
    }

    public function url(): string
    {
        return site_url('galeri/'.$this->slug);
    }

    /** Kapak görseli yoksa albümün ilk görselini kullanır. */
    public function coverPath(): ?string
    {
        return $this->cover_image ?: $this->activeImages()->value('image');
    }
}
