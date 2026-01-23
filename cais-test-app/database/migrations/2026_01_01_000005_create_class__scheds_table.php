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
        Schema::create('tbl_class_schedules', function (Blueprint $table) {
            $table->id('schedId');
            $table->foreignId('semester_id')->constrained('tbl_semester','semester_id')->cascadeOnDelete();
            $table->string('subject_code');
            $table->foreignId('course_id')->constrained('tbl_course','course_id')->cascadeOnDelete();
            $table->string('cat_no');
            $table->string('subject_title');
            $table->integer('units')->default('0');
            $table->string('atl');
            $table->time('time');
            $table->string('section');
            $table->string('slot_no');
            $table->boolean('class_type',)->default('1');
            $table->boolean('lab_type')->default('0');
            $table->foreignId('dept_id')->constrained('tbl_department', 'dept_id')->cascadeOnDelete();
            $table->boolean('weight')->default('0');
            $table->enum('bulk_upload',['Yes','No']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class__scheds');
    }
};
