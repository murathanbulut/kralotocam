<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    use HasActiveOrdering;

    protected $fillable = [
        'gallery_album_id', 'title', 'description', 'alt_text', 'image', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function alt(): string
    {
        return $this->alt_text ?: $this->title;
    }
}
