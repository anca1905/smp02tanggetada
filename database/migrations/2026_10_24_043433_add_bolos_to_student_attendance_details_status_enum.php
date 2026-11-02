<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE student_attendance_details MODIFY COLUMN status ENUM('present', 'sick', 'permission', 'absent', 'late', 'bolos') NOT NULL DEFAULT 'present'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE student_attendance_details MODIFY COLUMN status ENUM('present', 'sick', 'permission', 'absent', 'late') NOT NULL DEFAULT 'present'");
        }
    }
};
