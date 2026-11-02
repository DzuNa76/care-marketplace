<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_verification_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('provider_profile_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('document_type', 50);
            $table->string('document_number', 100)->nullable();
            $table->string('file_path');
            $table->string('original_filename')->nullable();

            $table->string('status', 20)->default('pending')->index();

            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->date('expires_at')->nullable();

            $table->timestamps();

            $table->index(
                ['provider_profile_id', 'document_type'],
                'pvd_profile_type_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_verification_documents');
    }
};
