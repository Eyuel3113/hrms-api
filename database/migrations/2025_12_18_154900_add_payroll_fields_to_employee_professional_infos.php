<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employee_professional_infos', function (Blueprint $table) {
            $table->decimal('transport_allowance', 12, 2)->default(0)->after('basic_salary');
            $table->boolean('has_pension')->default(true)->after('transport_allowance');
        });
    }

    public function down(): void
    {
        Schema::table('employee_professional_infos', function (Blueprint $table) {
            $table->dropColumn(['transport_allowance', 'has_pension']);
        });
    }
};
