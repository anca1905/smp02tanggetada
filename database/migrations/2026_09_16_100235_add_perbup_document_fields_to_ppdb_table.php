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
        Schema::table('ppdb', function (Blueprint $table) {
            $table->string('doc_pas_photo')->nullable()->after('doc_akta');
            $table->string('doc_transkrip')->nullable()->after('doc_pas_photo');
            $table->string('doc_tka')->nullable()->after('doc_transkrip');
            $table->string('doc_ktp_ayah')->nullable()->after('doc_tka');
            $table->string('doc_ktp_ibu')->nullable()->after('doc_ktp_ayah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb', function (Blueprint $table) {
            $table->dropColumn([
                'doc_pas_photo',
                'doc_transkrip',
                'doc_tka',
                'doc_ktp_ayah',
                'doc_ktp_ibu'
            ]);
        });
    }
};
