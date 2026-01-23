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
        Schema::create('tbl_profile', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('course_id')->nullable()->constrained('tbl_course','course_id')->cascadeOnDelete();
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('contact_email')->unique();
            $table->string('contact');
            $table->string('address');
            $table->date('birthdate');
            $table->string('sex');
            $table->foreignId('dept_id')->constrained('tbl_department','dept_id')->cascadeOnDelete();
            $table->foreignId('college_id')->constrained('tbl_college','college_id')->cascadeOnDelete();
            $table->string('p_email')->unique();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
