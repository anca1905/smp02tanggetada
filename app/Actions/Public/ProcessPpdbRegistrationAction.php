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
        $noReg = 'REG-'.date('Y').'-'.str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        Ppdb::create([
            'no_registrasi' => $noReg,
            'nama_lengkap' => strtoupper($data['nama_lengkap'] ?? ''),
            'nisn' => $data['nisn'] ?? '',
            'nik' => $data['nik'] ?? '',
            'no_kk' => $data['no_kk'] ?? '',
            'jenis_kelamin' => ($data['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'L' : 'P',
            'agama' => $data['agama'] ?? '',
            'tempat_tinggal' => $data['tempat_tinggal'] ?? '',
            'moda_transportasi' => $data['moda_transportasi'] ?? '',
            'tempat_lahir' => strtoupper($data['tempat_lahir'] ?? ''),
            'tanggal_lahir' => ($data['tanggal_lahir'] ?? '') ?: null,
            'alamat' => strtoupper($data['alamat'] ?? ''),
            'asal_sekolah' => strtoupper($data['asal_sekolah'] ?? ''),
            'nama_ayah' => strtoupper($data['nama_ayah'] ?? ''),
            'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? '',
            'penghasilan_ayah' => $data['penghasilan_ayah'] ?? '',
            'nama_ibu' => strtoupper($data['nama_ibu'] ?? ''),
            'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? '',
            'penghasilan_ibu' => $data['penghasilan_ibu'] ?? '',
            'nama_wali' => strtoupper($data['nama_wali'] ?? ''),
            'pekerjaan_wali' => $data['pekerjaan_wali'] ?? '',
            'penghasilan_wali' => $data['penghasilan_wali'] ?? '',
            'no_hp' => $data['no_hp'] ?? '',
            'status_pendaftaran' => 'Pending',
            'tanggal_daftar' => now(),
            
            // Simpan Dokumen
            'doc_pas_photo' => isset($data['doc_pas_photo']) ? $data['doc_pas_photo']->store('ppdb_documents', 'public') : null,
            'doc_ijazah' => isset($data['doc_ijazah']) ? $data['doc_ijazah']->store('ppdb_documents', 'public') : null,
            'doc_transkrip' => isset($data['doc_transkrip']) ? $data['doc_transkrip']->store('ppdb_documents', 'public') : null,
            'doc_tka' => isset($data['doc_tka']) ? $data['doc_tka']->store('ppdb_documents', 'public') : null,
            'doc_akta' => isset($data['doc_akta']) ? $data['doc_akta']->store('ppdb_documents', 'public') : null,
            'doc_kk' => isset($data['doc_kk']) ? $data['doc_kk']->store('ppdb_documents', 'public') : null,
            'doc_ktp_ayah' => isset($data['doc_ktp_ayah']) ? $data['doc_ktp_ayah']->store('ppdb_documents', 'public') : null,
            'doc_ktp_ibu' => isset($data['doc_ktp_ibu']) ? $data['doc_ktp_ibu']->store('ppdb_documents', 'public') : null,
        ]);

        return $noReg;
    }
}
