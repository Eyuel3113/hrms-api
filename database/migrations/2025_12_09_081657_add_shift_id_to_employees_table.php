<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
Schema::table('employees', function (Blueprint $table) {
    $table->foreignUuid('shift_id')
          ->nullable()
          ->constrained('shifts')
          ->nullOnDelete();
});

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
};
