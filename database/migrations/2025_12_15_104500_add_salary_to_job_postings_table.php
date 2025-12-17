<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->decimal('min_salary', 12, 2)->nullable()->after('vacancy');
            $table->decimal('max_salary', 12, 2)->nullable()->after('min_salary');
            $table->string('salary_currency', 10)->default('ETB')->after('max_salary');
            $table->boolean('salary_negotiable')->default(false)->after('salary_currency');
            $table->boolean('show_salary')->default(true)->after('salary_negotiable');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn([
                'min_salary',
                'max_salary',
                'salary_currency',
                'salary_negotiable',
                'show_salary'
            ]);
        });
    }
};