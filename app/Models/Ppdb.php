<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppdb extends Model
{
    use HasFactory;

    protected $table = 'ppdb';

    /**
     * Disable default timestamps (created_at / updated_at)
     * karena tabel pakai kolom 'tanggal_daftar' secara manual.
     */
    public $timestamps = false;

    protected $fillable = [
        'no_registrasi',
        'nama_lengkap',
        'nisn',
        'nik',
        'no_kk',
        'no_akta',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'kewarganegaraan',
        'berkebutuhan_khusus',
        'alamat',
        'rt',
        'rw',
        'desa_kelurahan',
        'kecamatan',
        'kode_pos',
        'tempat_tinggal',
        'moda_transportasi',
        'anak_ke',
        'punya_kip',
        'asal_sekolah',
        'tahun_lulus',
        'nama_ayah',
        'nik_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'nama_ibu',
        'nik_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'nama_wali',
        'nik_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'no_hp',
        'status_pendaftaran',
        'status_berkas',
        'catatan_berkas',
        'doc_kk',
        'doc_ijazah',
        'doc_akta',
        'doc_pas_photo',
        'doc_transkrip',
        'doc_tka',
        'doc_ktp_ayah',
        'doc_ktp_ibu',
        'tanggal_daftar',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'datetime',
    ];

    /**
     * Label tampilan jenis kelamin.
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Label warna badge status pendaftaran.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status_pendaftaran) {
            'Accepted' => 'green',
            'Rejected' => 'red',
            default => 'yellow',
        };
    }
}
