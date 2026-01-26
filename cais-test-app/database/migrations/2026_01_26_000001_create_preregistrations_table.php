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
        Schema::create('tbl_preregistration', function (Blueprint $table) {
            $table->id('prereg_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('schedId')->nullable();
            $table->string('section', 50);
            $table->string('subject_code', 50)->nullable();
            $table->string('subject_title', 255)->nullable();
            $table->integer('units')->default(0);
            $table->enum('status', ['pending', 'enrolled', 'cancelled'])->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('user_id')->on('tbl_users')->onDelete('cascade');
            $table->foreign('semester_id')->references('semester_id')->on('tbl_semester')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('tbl_course')->onDelete('cascade');
            
            // Prevent duplicate pre-registrations
            $table->unique(['user_id', 'semester_id', 'schedId'], 'unique_prereg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_preregistration');
    }
};
