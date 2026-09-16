<?php

namespace App\Actions\Ppdb;

use App\Models\Ppdb;
use Illuminate\Support\Facades\Storage;

class DeletePpdbAction
{
    /**
     * Menghapus data pendaftar PPDB beserta berkas lampirannya dari storage.
     *
     * @return string Nama pendaftar yang dihapus
     */
    public function execute(Ppdb $ppdb): string
    {
        $nama = $ppdb->nama_lengkap;

        $documentFields = [
            'doc_ijazah',
            'doc_transkrip',
            'doc_tka',
            'doc_akta',
            'doc_kk',
            'doc_ktp_ayah',
            'doc_ktp_ibu',
            'doc_pas_photo',
        ];

        foreach ($documentFields as $field) {
            $filePath = $ppdb->{$field};
            if (! empty($filePath)) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                } elseif (Storage::disk('local')->exists($filePath)) {
                    Storage::disk('local')->delete($filePath);
                }
            }
        }

        $ppdb->delete();

        return $nama;
    }
}
