<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_professional_infos', function (Blueprint $table) {
            $table->string('document_path')->nullable()->after('tax_id');
            $table->timestamp('document_uploaded_at')->nullable()->after('document_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_professional_infos', function (Blueprint $table) {
            $table->dropColumn(['document_path', 'document_uploaded_at']);
        });
    }
};
