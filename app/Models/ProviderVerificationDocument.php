<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ProviderDocumentType;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderVerificationDocument extends Model
{
    protected $fillable = [
        'provider_profile_id',
        'document_type',
        'document_number',
        'file_path',
        'original_filename',
        'expires_at',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => ProviderDocumentType::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'expires_at' => 'date',
        ];
    }

    public function providerProfile(): BelongsTo
    {
        return $this->belongsTo(ProviderProfile::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(
            ProviderVerificationLog::class,
            'provider_verification_document_id'
        )->orderBy('created_at');
    }
}
