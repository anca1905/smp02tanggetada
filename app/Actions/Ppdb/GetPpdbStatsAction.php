<?php

namespace App\Actions\Ppdb;

use App\Models\Ppdb;

class GetPpdbStatsAction
{
    /**
     * Mengambil rangkuman statistik data PPDB.
     */
    public function execute(): array
    {
        return [
            'pending' => Ppdb::where('status_pendaftaran', 'pending')->count(),
            'total' => Ppdb::count(),
            'accepted' => Ppdb::where('status_pendaftaran', 'Accepted')->count(),
        ];
    }
}
