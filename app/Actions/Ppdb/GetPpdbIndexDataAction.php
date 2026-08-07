<?php

namespace App\Actions\Ppdb;

use App\Models\Ppdb;
use App\Models\Setting;

class GetPpdbIndexDataAction
{
    /**
     * Mengambil daftar pendaftar PPDB beserta filter pencarian dan statistik.
     *
     * @param  string|null  $jurusan  Filter jurusan pendaftar
     * @param  string|null  $status  Filter status pendaftar
     * @param  string|null  $search  Kata kunci pencarian
     */
    public function execute(
        ?string $jurusan,
        ?string $status,
        ?string $search,
    ): array {
        $query = Ppdb::orderBy('tanggal_daftar', 'desc');

        if (! empty($jurusan)) {
            $query->where('jurusan_pilihan', $jurusan);
        }

        if (! empty($status)) {
            $query->where('status_pendaftaran', $status);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                    ->orWhere('no_registrasi', 'like', '%'.$search.'%')
                    ->orWhere('nisn', 'like', '%'.$search.'%');
            });
        }

        $pendaftars = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Ppdb::count(),
            'pending' => Ppdb::where('status_pendaftaran', 'Pending')->count(),
            'accepted' => Ppdb::where(
                'status_pendaftaran',
                'Accepted',
            )->count(),
            'rejected' => Ppdb::where(
                'status_pendaftaran',
                'Rejected',
            )->count(),
        ];

        $bukaPpdb = Setting::where('key', 'buka_ppdb')->value('value') ?? '1';

        return compact('pendaftars', 'stats', 'bukaPpdb');
    }
}
