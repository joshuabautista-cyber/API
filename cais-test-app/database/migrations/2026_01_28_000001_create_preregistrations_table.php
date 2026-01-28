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
        if (!Schema::hasTable('tbl_preregistration')) {
            Schema::create('tbl_preregistration', function (Blueprint $table) {
                $table->id('prereg_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('semester_id');
                $table->unsignedBigInteger('course_id')->nullable();
                $table->unsignedBigInteger('schedId')->nullable();
                $table->string('section')->nullable();
                $table->string('subject_code')->nullable();
                $table->string('subject_title')->nullable();
                $table->integer('units')->default(0);
                $table->string('status')->default('pending'); // pending, enrolled, cancelled
                $table->timestamps();

                // Indexes for faster queries
                $table->index('user_id');
                $table->index('semester_id');
                $table->index('status');
                $table->index(['user_id', 'semester_id', 'schedId']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_preregistration');
    }
};
