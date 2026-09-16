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
            // Drop jurusan (karena ini SMP)
            $table->dropColumn('jurusan_pilihan');

            // Data Pribadi Tambahan
            $table->string('no_kk', 20)->nullable()->after('nik');
            $table->string('no_akta', 50)->nullable()->after('no_kk');
            $table->string('agama', 30)->nullable()->after('jenis_kelamin');
            $table->string('kewarganegaraan', 10)->default('WNI')->after('agama');
            $table->string('berkebutuhan_khusus', 100)->nullable()->after('kewarganegaraan');
            $table->string('tempat_tinggal', 50)->nullable()->after('alamat');
            $table->string('moda_transportasi', 50)->nullable()->after('tempat_tinggal');
            $table->integer('anak_ke')->nullable()->after('moda_transportasi');
            $table->boolean('punya_kip')->default(false)->after('anak_ke');

            // Alamat Rinci
            $table->string('rt', 5)->nullable()->after('alamat');
            $table->string('rw', 5)->nullable()->after('rt');
            $table->string('desa_kelurahan', 100)->nullable()->after('rw');
            $table->string('kecamatan', 100)->nullable()->after('desa_kelurahan');
            $table->string('kode_pos', 10)->nullable()->after('kecamatan');

            // Data Ayah
            $table->string('nik_ayah', 20)->nullable()->after('nama_ayah');
            $table->string('pendidikan_ayah', 50)->nullable()->after('nik_ayah');
            $table->string('pekerjaan_ayah', 50)->nullable()->after('pendidikan_ayah');
            $table->string('penghasilan_ayah', 50)->nullable()->after('pekerjaan_ayah');

            // Data Ibu
            $table->string('nik_ibu', 20)->nullable()->after('nama_ibu');
            $table->string('pendidikan_ibu', 50)->nullable()->after('nik_ibu');
            $table->string('pekerjaan_ibu', 50)->nullable()->after('pendidikan_ibu');
            $table->string('penghasilan_ibu', 50)->nullable()->after('pekerjaan_ibu');

            // Data Wali
            $table->string('nama_wali', 100)->nullable()->after('penghasilan_ibu');
            $table->string('nik_wali', 20)->nullable()->after('nama_wali');
            $table->string('pekerjaan_wali', 50)->nullable()->after('nik_wali');
            $table->string('penghasilan_wali', 50)->nullable()->after('pekerjaan_wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb', function (Blueprint $table) {
            $table->string('jurusan_pilihan', 20)->nullable();
            
            $table->dropColumn([
                'no_kk', 'no_akta', 'agama', 'kewarganegaraan', 'berkebutuhan_khusus',
                'tempat_tinggal', 'moda_transportasi', 'anak_ke', 'punya_kip',
                'rt', 'rw', 'desa_kelurahan', 'kecamatan', 'kode_pos',
                'nik_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah',
                'nik_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu',
                'nama_wali', 'nik_wali', 'pekerjaan_wali', 'penghasilan_wali'
            ]);
        });
    }
};
