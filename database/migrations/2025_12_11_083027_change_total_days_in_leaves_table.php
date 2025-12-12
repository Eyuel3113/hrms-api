<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('total_days'); // remove generated column
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('total_days')->after('end_date'); // normal column
        });
    }

    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('total_days');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('total_days')->nullable();
        });
    }
};