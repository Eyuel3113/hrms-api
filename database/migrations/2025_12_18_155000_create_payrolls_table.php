<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            
            $table->integer('month');
            $table->integer('year');
            
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('transport_allowance', 12, 2)->default(0);
            
            // Earnings
            $table->decimal('overtime_pay', 12, 2)->default(0);
            $table->decimal('holiday_pay', 12, 2)->default(0);
            $table->decimal('incentives', 12, 2)->default(0); -- Training incentives
            $table->decimal('performance_bonus', 12, 2)->default(0);
            
            // Deductions
            $table->decimal('late_deduction', 12, 2)->default(0);
            $table->decimal('absent_deduction', 12, 2)->default(0);
            $table->decimal('unpaid_leave_deduction', 12, 2)->default(0);
            $table->decimal('pension_contribution', 12, 2)->default(0); -- 7% usually
            $table->decimal('income_tax', 15, 2)->default(0);
            
            $table->decimal('gross_earnings', 15, 2);
            $table->decimal('total_deductions', 15, 2);
            $table->decimal('net_pay', 15, 2);
            
            $table->enum('status', ['draft', 'locked', 'paid'])->default('draft');
            $table->json('calculation_details')->nullable(); -- Store breakdown
            
            $table->uuid('generated_by')->nullable(); -- Employee ID of HR
            $table->timestamp('locked_at')->nullable();
            $table->uuid('locked_by')->nullable();
            
            $table->timestamps();
            
            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
