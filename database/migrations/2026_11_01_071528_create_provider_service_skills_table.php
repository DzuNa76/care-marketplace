<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_service_skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('provider_service_id')
                ->constrained('provider_services')
                ->restrictOnDelete();

            $table->foreignId('skill_id')
                ->constrained('skills')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique(
                ['provider_service_id', 'skill_id'],
                'provider_service_skills_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_service_skills');
    }
};
