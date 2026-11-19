
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_verification_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('provider_verification_document_id');
            $table->unsignedBigInteger('actor_id')->nullable();

            $table->string('action', 30);

            $table->string('previous_status', 20)->nullable();
            $table->string('new_status', 20)->nullable();

            $table->string('original_filename')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['provider_verification_document_id', 'created_at'],
                'pvl_document_created_idx'
            );

            $table->foreign(
                'provider_verification_document_id',
                'pvl_document_fk'
            )->references('id')
                ->on('provider_verification_documents')
                ->restrictOnDelete();

            $table->foreign(
                'actor_id',
                'pvl_actor_fk'
            )->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_verification_logs');
    }
};
