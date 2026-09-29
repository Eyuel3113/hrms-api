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
        $platforms = "'linkedin', 'github', 'twitter', 'facebook', 'instagram', 'portfolio', 'telegram', 'slack', 'whatsapp', 'skype', 'behance', 'dribbble', 'other'";
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE employee_social_links DROP CONSTRAINT IF EXISTS employee_social_links_platform_check");
            DB::statement("ALTER TABLE employee_social_links ADD CONSTRAINT employee_social_links_platform_check CHECK (platform::text = ANY (ARRAY[$platforms]::text[]))");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE employee_social_links MODIFY COLUMN platform ENUM($platforms) NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $platforms = "'linkedin', 'github', 'twitter', 'facebook', 'instagram', 'portfolio', 'other'";
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE employee_social_links DROP CONSTRAINT IF EXISTS employee_social_links_platform_check");
            DB::statement("ALTER TABLE employee_social_links ADD CONSTRAINT employee_social_links_platform_check CHECK (platform::text = ANY (ARRAY[$platforms]::text[]))");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE employee_social_links MODIFY COLUMN platform ENUM($platforms) NOT NULL");
        }
    }
};
