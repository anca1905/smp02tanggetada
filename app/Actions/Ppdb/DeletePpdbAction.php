<?php

namespace App\Actions\Ppdb;

use App\Models\Ppdb;

class DeletePpdbAction
{
    /**
     * Menghapus data pendaftar PPDB.
     *
     * @return string Nama pendaftar yang dihapus
     */
    public function execute(Ppdb $ppdb): string
    {
        $nama = $ppdb->nama_lengkap;
        $ppdb->delete();

        return $nama;
    }
}
