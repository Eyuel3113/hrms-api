<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');                           // 2025-12-04
            $table->string('ethiopian_date')->nullable();   // ታህሳስ 25, 2017
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('check_in_ip')->nullable();
            $table->string('check_out_ip')->nullable();
            $table->string('check_in_device')->nullable();  // Web | ZKTeco | Mobile
            $table->string('check_out_device')->nullable();
            $table->text('check_in_note')->nullable();
            $table->text('check_out_note')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'holiday', 'leave'])
                  ->default('absent');
            $table->integer('late_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->integer('worked_minutes')->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
            $table->index(['date', 'status']);
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};