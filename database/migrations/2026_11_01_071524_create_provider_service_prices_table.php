<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_service_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('provider_service_id')
                ->constrained('provider_services')
                ->restrictOnDelete();

            $table->string('package_name', 100);

            $table->unsignedInteger('duration_value');
            $table->enum('duration_unit', ['day', 'week', 'month']);

            $table->unsignedBigInteger('price');

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(
                ['provider_service_id', 'is_active'],
                'provider_service_prices_service_active_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_service_prices');
    }
};
