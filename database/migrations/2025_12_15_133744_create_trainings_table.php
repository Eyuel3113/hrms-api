<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('trainer_name')->nullable();
            $table->string('location')->nullable();
            $table->decimal('incentive_amount', 12, 2)->default(0.00); // incentive/payment
            $table->boolean('has_incentive')->default(false);
            $table->enum('type', ['internal', 'external', 'certification'])->default('internal');
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};