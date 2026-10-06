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
        $defaults = [
            ['key' => 'popup_active', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_badge', 'value' => 'Peringatan Bulan Bahasa 2026', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_title', 'value' => 'Lomba Literasi Antar Kelas', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_subtitle', 'value' => 'Utamakan Bahasa Indonesia, Lestarikan Bahasa Daerah, Kuasai Bahasa Asing', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_deadline', 'value' => 'Batas Pendaftaran: 10 - 24 Oktober 2026', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_image', 'value' => 'settings/lomba-literasi-antar-kelas-2026.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_btn_text', 'value' => 'Daftar Sekarang (Google Form)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_btn_url', 'value' => 'https://forms.gle/frfZEwZ9x2xwuiTM9', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_wa_number', 'value' => '0853465489992', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'popup_news_url', 'value' => '/berita/lomba-literasi-antar-kelas-bulan-bahasa-2026', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('settings')->insertOrIgnore($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'popup_active',
            'popup_badge',
            'popup_title',
            'popup_subtitle',
            'popup_deadline',
            'popup_image',
            'popup_btn_text',
            'popup_btn_url',
            'popup_wa_number',
            'popup_news_url',
        ])->delete();
    }
};
