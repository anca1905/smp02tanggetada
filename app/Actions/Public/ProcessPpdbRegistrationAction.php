<?php

namespace App\Actions\Public;

use App\Models\Ppdb;

class ProcessPpdbRegistrationAction
{
    /**
     * Process PPDB registration, generate registration number and save to database.
     *
     * @return string Registration Number
     */
    public function execute(array $data): string
    {
        $lastId = Ppdb::max('id') ?? 0;
        $noReg = 'REG-' . date('Y') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        Ppdb::create([
            'no_registrasi' => $noReg,
            'nama_lengkap' => strtoupper($data['nama_lengkap'] ?? ''),
            'nisn' => $data['nisn'] ?? '',
            'nik' => $data['nik'] ?? '',
            'jenis_kelamin' => ($data['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'L' : 'P',
            'tempat_lahir' => strtoupper($data['tempat_lahir'] ?? ''),
            'tanggal_lahir' => ($data['tanggal_lahir'] ?? '') ?: null,
            'alamat' => strtoupper($data['alamat'] ?? ''),
            'asal_sekolah' => strtoupper($data['asal_sekolah'] ?? ''),
            'tahun_lulus' => ($data['tahun_lulus'] ?? '') ?: date('Y'),
            'nama_ayah' => strtoupper($data['nama_ayah'] ?? ''),
            'nama_ibu' => strtoupper($data['nama_ibu'] ?? ''),
            'no_hp' => $data['no_hp'] ?? '',
            'jurusan_pilihan' => $data['jurusan'] ?? '',
            'status_pendaftaran' => 'Pending',
            'tanggal_daftar' => now(),
        ]);

        return $noReg;
    }
}
