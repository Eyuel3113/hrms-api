<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new platforms to the ENUM
        DB::statement("ALTER TABLE employee_social_links MODIFY COLUMN platform ENUM('linkedin', 'github', 'twitter', 'facebook', 'instagram', 'portfolio', 'telegram', 'slack', 'whatsapp', 'skype', 'behance', 'dribbble', 'other') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM
        // Note: This might fail if there are records with the new platforms.
        // For strict reversibility, we would need to handle data loss or mapping, but for now reverting schema is enough.
        DB::statement("ALTER TABLE employee_social_links MODIFY COLUMN platform ENUM('linkedin', 'github', 'twitter', 'facebook', 'instagram', 'portfolio', 'other') NOT NULL");
    }
};
