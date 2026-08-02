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
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'asal_sekolah',
        'tahun_lulus',
        'nama_ayah',
        'nama_ibu',
        'no_hp',
        'jurusan_pilihan',
        'status_pendaftaran',
        'status_berkas',
        'doc_kk',
        'doc_ijazah',
        'doc_akta',
        'tanggal_daftar',
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
            default    => 'yellow',
        };
    }
}
