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
        Schema::create('tbl_enrollments', function (Blueprint $table) {
            $table->id('enrollment_id');
            $table->foreignId('user_id')->constrained( 'tbl_users','user_id')->cascadeOnDelete();;
            $table->foreignId('semester_id')->constrained( 'tbl_semester','semester_id')->cascadeOnDelete();;
            $table->foreignId('course_id')->constrained( 'tbl_course','course_id')->cascadeOnDelete();;
            $table->string('section');
            $table->boolean('registration_only_tag')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
