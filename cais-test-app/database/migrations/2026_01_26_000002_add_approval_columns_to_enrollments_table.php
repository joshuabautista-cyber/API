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
        Schema::table('tbl_enrollments', function (Blueprint $table) {
            // Add prereg_id to link back to preregistration
            $table->unsignedBigInteger('prereg_id')->nullable()->after('enrollment_id');
            
            // Add schedId for class schedule reference
            $table->unsignedBigInteger('schedId')->nullable()->after('section');
            
            // Add approval status for RA workflow
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('schedId');
            
            // Add remarks for RA feedback
            $table->text('remarks')->nullable()->after('approval_status');
            
            // Add approved_by to track which RA approved
            $table->unsignedBigInteger('approved_by')->nullable()->after('remarks');
            
            // Add approved_at timestamp
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_enrollments', function (Blueprint $table) {
            $table->dropColumn(['prereg_id', 'schedId', 'approval_status', 'remarks', 'approved_by', 'approved_at']);
        });
    }
};
