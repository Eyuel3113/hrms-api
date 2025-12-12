<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('cv_path'); // Cloudinary URL
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['new', 'reviewed', 'interview', 'shortlisted', 'rejected', 'hired'])->default('new');
            $table->timestamp('hired_at')->nullable();
            $table->uuid('hired_as_employee_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};