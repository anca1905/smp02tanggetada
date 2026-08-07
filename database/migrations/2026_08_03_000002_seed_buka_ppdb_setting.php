<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations — Tambah setting buka_ppdb ke tabel settings.
     */
    public function up(): void
    {
        // Tabel settings menggunakan key-value pairs, jadi cukup insert row baru
        DB::table('settings')->insertOrIgnore([
            ['key' => 'buka_ppdb', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'buka_ppdb')->delete();
    }
};
