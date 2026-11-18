<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderService extends Model
{
    protected $fillable = [
        'provider_profile_id',
        'service_category_id',
        'title',
        'description',
        'experience_years',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function providerProfile(): BelongsTo
    {
        return $this->belongsTo(ProviderProfile::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProviderServicePrice::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(
            Skill::class,
            'provider_service_skills',
            'provider_service_id',
            'skill_id'
        )->withTimestamps();
    }

    /**
     * Memeriksa apakah layanan boleh ditampilkan kepada customer.
     */
    public function canBeShownToCustomers(): bool
    {
        // Layanan harus diaktifkan oleh provider.
        if (! $this->is_active) {
            return false;
        }

        // Pastikan kategori dan profil provider tersedia.
        $categorySlug = $this->category?->slug;
        $providerProfile = $this->providerProfile;

        if (! $categorySlug || ! $providerProfile) {
            return false;
        }

        // Pastikan dokumen wajib kategori sudah terverifikasi.
        return $providerProfile->isVerifiedForCategory($categorySlug);
    }
}
