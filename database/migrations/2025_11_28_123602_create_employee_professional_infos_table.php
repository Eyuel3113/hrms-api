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
    Schema::create('employee_professional_infos', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
        $table->foreignUuid('department_id')->nullable()->constrained('departments');
        $table->foreignUuid('designation_id')->nullable()->constrained('designations');
        $table->date('joining_date');
        $table->date('ending_date')->nullable();
        $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'freelance', 'intern']);
        $table->decimal('basic_salary', 12, 2);
        $table->string('salary_currency', 3)->default('USD');
        $table->string('bank_name')->nullable();
        $table->string('bank_account_number')->nullable();
        $table->string('tax_id')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_professional_infos');
    }
};
