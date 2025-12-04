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
    Schema::create('employee_personal_infos', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('phone')->nullable();
        $table->string('photo')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->enum('gender', ['male', 'female', 'other'])->nullable();
        $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
        $table->string('nationality')->nullable();
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('zip_code')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_personal_infos');
    }
};
