<?php

// Run this file from the command line to create the preregistration table and add enrollment columns

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Creating preregistration table and updating enrollments table...\n";

try {
    // Create tbl_preregistration table
    if (!Schema::hasTable('tbl_preregistration')) {
        DB::statement("
            CREATE TABLE tbl_preregistration (
                prereg_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                semester_id BIGINT UNSIGNED NOT NULL,
                course_id BIGINT UNSIGNED NOT NULL,
                schedId BIGINT UNSIGNED NULL,
                section VARCHAR(50) NOT NULL,
                subject_code VARCHAR(50) NULL,
                subject_title VARCHAR(255) NULL,
                units INT DEFAULT 0,
                status ENUM('pending', 'enrolled', 'cancelled') DEFAULT 'pending',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                UNIQUE KEY unique_prereg (user_id, semester_id, schedId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ Created tbl_preregistration table\n";
    } else {
        echo "ℹ️  tbl_preregistration table already exists\n";
    }

    // Add new columns to tbl_enrollments if they don't exist
    if (!Schema::hasColumn('tbl_enrollments', 'prereg_id')) {
        DB::statement("ALTER TABLE tbl_enrollments ADD COLUMN prereg_id BIGINT UNSIGNED NULL AFTER enrollment_id");
        echo "✅ Added prereg_id column to tbl_enrollments\n";
    }

    if (!Schema::hasColumn('tbl_enrollments', 'schedId')) {
        DB::statement("ALTER TABLE tbl_enrollments ADD COLUMN schedId BIGINT UNSIGNED NULL AFTER section");
        echo "✅ Added schedId column to tbl_enrollments\n";
    }

    if (!Schema::hasColumn('tbl_enrollments', 'approval_status')) {
        DB::statement("ALTER TABLE tbl_enrollments ADD COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER schedId");
        echo "✅ Added approval_status column to tbl_enrollments\n";
    }

    if (!Schema::hasColumn('tbl_enrollments', 'remarks')) {
        DB::statement("ALTER TABLE tbl_enrollments ADD COLUMN remarks TEXT NULL AFTER approval_status");
        echo "✅ Added remarks column to tbl_enrollments\n";
    }

    if (!Schema::hasColumn('tbl_enrollments', 'approved_by')) {
        DB::statement("ALTER TABLE tbl_enrollments ADD COLUMN approved_by BIGINT UNSIGNED NULL AFTER remarks");
        echo "✅ Added approved_by column to tbl_enrollments\n";
    }

    if (!Schema::hasColumn('tbl_enrollments', 'approved_at')) {
        DB::statement("ALTER TABLE tbl_enrollments ADD COLUMN approved_at TIMESTAMP NULL AFTER approved_by");
        echo "✅ Added approved_at column to tbl_enrollments\n";
    }

    echo "\n✅ Database migration completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
