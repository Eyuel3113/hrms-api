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
        Schema::create('leaves', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign Keys — UUID
            $table->foreignUuid('employee_id')
                  ->constrained('employees')
                  ->cascadeOnDelete();

            $table->foreignUuid('leave_type_id')
                  ->constrained('leave_types')
                  ->cascadeOnDelete();

            // Leave Details
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->nullable(); // Standard column for cross-DB compatibility

            $table->text('reason')->nullable();

            // Status & Approval
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            $table->uuid('approved_by')->nullable();
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('employees')
                  ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};