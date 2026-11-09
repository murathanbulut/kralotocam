<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'service_id', 'service_title', 'message',
        'kvkk_consent', 'ip_address', 'user_agent', 'is_read', 'mailed_at',
    ];

    protected function casts(): array
    {
        return [
            'kvkk_consent' => 'boolean',
            'is_read' => 'boolean',
            'mailed_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
