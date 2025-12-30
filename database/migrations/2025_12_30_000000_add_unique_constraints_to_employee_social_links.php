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
        Schema::table('employee_social_links', function (Blueprint $table) {
            // Constraint: One link per platform per employee
            $table->unique(['employee_id', 'platform'], 'unique_employee_platform');
            
            // Constraint: Unique URL per employee (cannot add same link twice)
            // Note: We scope this to employee_id so two different employees can have same portfolio link if needed (rare but possible)
            // or if we want global uniqueness for URL, we can just use unique('url').
            // Based on request "in the employee the url canot be same one employee", scoping to employee_id is correct.
            $table->unique(['employee_id', 'url'], 'unique_employee_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_social_links', function (Blueprint $table) {
            $table->dropUnique('unique_employee_platform');
            $table->dropUnique('unique_employee_url');
        });
    }
};
