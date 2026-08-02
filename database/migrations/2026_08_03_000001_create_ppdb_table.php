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
        Schema::create('ppdb', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi', 20)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('nisn', 20)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('asal_sekolah', 100)->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('nama_ayah', 100)->nullable();
            $table->string('nama_ibu', 100)->nullable();
            $table->string('no_hp', 20);
            $table->string('jurusan_pilihan', 20)->nullable();
            $table->enum('status_pendaftaran', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->enum('status_berkas', ['Belum Dicek', 'Lengkap', 'Tidak Lengkap'])->default('Belum Dicek');
            $table->string('doc_kk', 255)->nullable();
            $table->string('doc_ijazah', 255)->nullable();
            $table->string('doc_akta', 255)->nullable();
            $table->timestamp('tanggal_daftar')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb');
    }
};
