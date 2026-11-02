<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_category_id')
                ->constrained('service_categories')
                ->restrictOnDelete();

            $table->string('name', 100);
            $table->string('slug', 120);
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['service_category_id', 'slug'],
                'skills_category_slug_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
