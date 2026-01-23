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
        Schema::create('tbl_semester', function (Blueprint $table) {
            $table->id('semester_id');
            $table->string('semester_name');
            $table->enum('semester_no',['1','2','3',]);
            $table->string('semester_year');
            $table->string('semester_status')->default('pending');
            $table->string('grades_dl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
