<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function providerServices(): BelongsToMany
    {
        return $this->belongsToMany(
            ProviderService::class,
            'provider_service_skills',
            'skill_id',
            'provider_service_id'
        )->withTimestamps();
    }
}
