<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_verification_documents', function (Blueprint $table) {
            $table->unique(
                ['provider_profile_id', 'document_type'],
                'pvd_profile_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('provider_verification_documents', function (Blueprint $table) {
            $table->dropUnique('pvd_profile_type_unique');
        });
    }
};
