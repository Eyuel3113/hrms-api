<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // Morning Shift, Night Shift, Factory A
            $table->time('start_time'); // 09:00:00
            $table->time('end_time');   // 17:30:00
            $table->integer('late_threshold_minutes')->default(15);
            $table->integer('half_day_minutes')->default(240); // less than 4h = half day
            $table->decimal('overtime_rate', 5, 2)->default(1.50); // 1.5x
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};