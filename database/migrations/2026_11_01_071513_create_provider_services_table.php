<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('provider_profile_id')
                ->constrained('provider_profiles')
                ->restrictOnDelete();

            $table->foreignId('service_category_id')
                ->constrained('service_categories')
                ->restrictOnDelete();

            $table->string('title', 150);
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('experience_years')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(
                ['provider_profile_id', 'service_category_id'],
                'provider_services_provider_category_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_services');
    }
};
