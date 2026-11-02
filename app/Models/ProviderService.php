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
}
