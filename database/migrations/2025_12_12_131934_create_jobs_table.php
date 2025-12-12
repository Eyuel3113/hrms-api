<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignUuid('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->integer('vacancy')->default(1);
            $table->date('deadline');
            $table->enum('status', ['open', 'closed', 'on_hold'])->default('open');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};