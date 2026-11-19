<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderVerificationLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'provider_verification_document_id',
        'actor_id',
        'action',
        'previous_status',
        'new_status',
        'original_filename',
        'rejection_reason',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(
            ProviderVerificationDocument::class,
            'provider_verification_document_id'
        );
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(
            ProviderVerificationLog::class,
            'provider_verification_document_id'
        )->orderBy('created_at');
    }
}
