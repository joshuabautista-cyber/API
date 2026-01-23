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
        Schema::create('tbl_department', function (Blueprint $table) {
            $table->id('dept_id');
            $table->foreignId('college_id')->constrained('tbl_college','college_id')->cascadeOnDelete();;
            $table->string('college_name');
            $table->string('dept_name');
            $table->string('dept_desc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
