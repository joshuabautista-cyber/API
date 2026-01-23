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
        Schema::create('tbl_grades', function (Blueprint $table) {
            $table->id('grade_id');
            $table->foreignId('semester_id')->constrained( 'tbl_semester','semester_id')->cascadeOnDelete();;
            $table->foreignId('user_id')->constrained( 'tbl_users','user_id')->cascadeOnDelete();;
            $table->foreignId('faculty_id')->constrained( 'tbl_users','user_id')->cascadeOnDelete();;
            $table->foreignId('subject_id')->constrained( 'tbl_class_schedules','schedId')->cascadeOnDelete();;
            $table->foreignId('schedId')->constrained( 'tbl_class_schedules','schedId')->cascadeOnDelete();;
            $table->string('units');
            $table->string('grades')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('weight')->default('0');
            $table->string('reexam')->nullable();
            $table->string('pending_status')->nullable();
            $table->string('day');
            $table->time('time');
            $table->string('room');
            $table->date('date_uploaded');
            $table->date('date_faculty_approved')->nullable();
            $table->date('date_dept_approved')->nullable();
            $table->date('date_dean_approved')->nullable();
            $table->foreignId('dept_id')->constrained( 'tbl_department','dept_id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
