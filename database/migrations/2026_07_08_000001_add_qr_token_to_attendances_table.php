<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->unique()->after('end_time');
            $table->timestamp('qr_expires_at')->nullable()->after('qr_token');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'qr_expires_at']);
        });
    }
};
