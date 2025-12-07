<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropForeign(['employee_id']);
        $table->uuid('employee_id')->change();
        $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uuid', function (Blueprint $table) {
            //
        });
    }
};
