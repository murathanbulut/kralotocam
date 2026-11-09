<?php

namespace App\Models;

use App\Models\Concerns\HasActiveOrdering;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasActiveOrdering;

    protected $fillable = ['parent_id', 'title', 'url', 'open_in_new_tab', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'open_in_new_tab' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Ana menü ağacı. İstek başına bir kez sorgulanır (once).
     * Not: Laravel 13 güvenlik gereği Eloquent modellerini önbellekten geri açmaz
     * (config/cache.php "serializable_classes" => false), bu yüzden kalıcı önbellek kullanılmaz.
     */
    public static function tree(): Collection
    {
        return once(fn () => static::query()
            ->whereNull('parent_id')
            ->active()
            ->ordered()
            ->with(['children' => fn ($q) => $q->active()->ordered()])
            ->get());
    }

    /** Göreli bağlantıları tam adrese çevirir. */
    public function href(): string
    {
        $url = trim((string) $this->url);

        if ($url === '' || $url === '#') {
            return '#';
        }

        if (preg_match('#^(https?:|mailto:|tel:)#i', $url)) {
            return $url;
        }

        return site_url($url);
    }

    public function isCurrent(): bool
    {
        $href = $this->href();

        if ($href === '#') {
            return $this->children->contains(fn (self $child) => $child->isCurrent());
        }

        $path = trim((string) parse_url($href, PHP_URL_PATH), '/');
        $current = trim(request()->path(), '/');

        return $path === '' ? $current === '' : ($current === $path || str_starts_with($current, $path.'/'));
    }
}
