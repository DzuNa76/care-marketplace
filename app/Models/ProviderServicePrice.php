<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderServicePrice extends Model
{
    protected $fillable = [
        'provider_service_id',
        'package_name',
        'duration_value',
        'duration_unit',
        'price',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_value' => 'integer',
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function providerService(): BelongsTo
    {
        return $this->belongsTo(ProviderService::class);
    }
}
