<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');                    // Meskel, Timket, Eid al-Fitr
            $table->date('date');                      // 2025-09-27
            $table->string('ethiopian_date');          // መስከረም ፳፯, ፳፲፯ ዓ.ም
            $table->enum('type', ['national', 'religious', 'company'])->default('national');
            $table->boolean('is_recurring')->default(true);  // every year
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};