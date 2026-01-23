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
        Schema::create('tbl_course', function (Blueprint $table) {
            $table->id('course_id');
            $table->foreignId('college_id')->constrained('tbl_college','college_id')->cascadeOnDelete();
            $table->string('course_name');
            $table->string('course_desc');
            $table->string('course_type');
            $table->string('course_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
