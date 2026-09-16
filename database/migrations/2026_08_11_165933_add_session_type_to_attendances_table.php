<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom session_type ke tabel attendances (apel, kelas, pulang)
     * dan jadikan teacher_id nullable agar kiosk tanpa login guru bisa bekerja.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('session_type', ['apel', 'kelas', 'pulang'])
                ->default('kelas')
                ->after('class');

            // Jadikan teacher_id nullable supaya kiosk bisa bekerja tanpa login guru
            $table->foreignId('teacher_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('session_type');
            $table->foreignId('teacher_id')->nullable(false)->change();
        });
    }
};
