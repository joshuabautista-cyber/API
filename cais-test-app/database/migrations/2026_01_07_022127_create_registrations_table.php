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
        Schema::create('tbl_registration', function (Blueprint $table) {
            $table->id('registration_id');
            $table->foreignId('user_id')->constrained( 'tbl_users','user_id')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained( 'tbl_semester','semester_id')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained( 'tbl_course','course_id')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained( 'tbl_enrollments','enrollment_id')->cascadeOnDelete();
            $table->string('enroll_type')->nullable();;
            $table->string('ra_status')->default('Pending');
            $table->string('status')->default('Pending');
            $table->date('ra_date_approved');
            $table->boolean('forced_drop')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
