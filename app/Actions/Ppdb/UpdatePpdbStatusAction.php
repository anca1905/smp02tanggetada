<?php

namespace App\Actions\Ppdb;

use App\Models\Ppdb;

class UpdatePpdbStatusAction
{
    /**
     * Memperbarui status pendaftaran PPDB (Accepted, Rejected, atau Pending).
     */
    public function execute(Ppdb $ppdb, string $status): Ppdb
    {
        $ppdb->update(['status_pendaftaran' => $status]);

        return $ppdb;
    }
}
