<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Doğrulama gerektiren kurum kayıtları (sigorta şirketi, filo kuruluşu).
 * Ön yüzde yalnızca published() kapsamı kullanılır: aktif VE doğrulanmış.
 */
trait HasVerification
{
    use HasActiveOrdering;

    public function initializeHasVerification(): void
    {
        $this->mergeFillable([
            'name', 'logo', 'logo_rights_confirmed', 'description',
            'is_verified', 'verified_at', 'internal_note', 'sort_order', 'is_active',
        ]);
        $this->mergeCasts([
            'logo_rights_confirmed' => 'boolean',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'is_active' => 'boolean',
        ]);
    }

    public static function bootHasVerification(): void
    {
        static::saving(function ($model) {
            if (! $model->is_verified) {
                $model->verified_at = null;
            } elseif (! $model->verified_at) {
                $model->verified_at = now();
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->active()->where($this->getTable().'.is_verified', true)->ordered();
    }

    /** Logo yalnızca kullanım hakkı onaylandıysa gösterilir. */
    public function publicLogo(): ?string
    {
        return $this->logo && $this->logo_rights_confirmed ? media_url($this->logo) : null;
    }
}
