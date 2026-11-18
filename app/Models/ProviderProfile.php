<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\ProviderDocumentType;

class ProviderProfile extends Model
{
    protected $fillable = [
        'display_name',
        'phone',
        'bio',
        'experience_years',
    ];

    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'verification_submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function providerServices(): HasMany
    {
        return $this->hasMany(ProviderService::class);
    }

    public function verificationDocuments(): HasMany
    {
        return $this->hasMany(ProviderVerificationDocument::class);
    }

    /**
     * Mengambil daftar dokumen wajib berdasarkan kategori layanan.
     */
    public function requiredDocumentsForCategory(string $categorySlug): array
    {
        return match ($categorySlug) {
            'caregiver',
            'babysitter',
            'cleaning-service' => [
                ProviderDocumentType::IDENTITY_CARD,
                ProviderDocumentType::SELFIE,
            ],

            'perawat' => [
                ProviderDocumentType::IDENTITY_CARD,
                ProviderDocumentType::SELFIE,
                ProviderDocumentType::NURSE_STR,
                ProviderDocumentType::NURSE_CERTIFICATE,
            ],

            default => [],
        };
    }

    /**
     * Memeriksa apakah seluruh dokumen wajib kategori sudah disetujui
     * dan belum kedaluwarsa.
     */
    public function isVerifiedForCategory(string $categorySlug): bool
    {
        $requiredDocuments = $this->requiredDocumentsForCategory($categorySlug);

        // Kategori tidak dikenal tidak boleh dianggap terverifikasi.
        if (empty($requiredDocuments)) {
            return false;
        }

        foreach ($requiredDocuments as $documentType) {
            $document = $this->verificationDocuments()
                ->where('document_type', $documentType->value)
                ->orderByDesc('submitted_at')
                ->orderByDesc('id')
                ->first();

            // Dokumen wajib belum diunggah.
            if (! $document) {
                return false;
            }

            // Dokumen wajib belum disetujui.
            if ($document->status !== 'approved') {
                return false;
            }

            // Dokumen sudah kedaluwarsa.
            if (
                $document->expires_at !== null &&
                $document->expires_at->lt(today())
            ) {
                return false;
            }
        }

        return true;
    }
}
