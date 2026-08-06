<?php

namespace App\Actions\Bill;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Collection;

class GetBillsAction
{
    /**
     * Mengambil daftar tagihan siswa
     */
    public function execute(): Collection
    {
        return Bill::with('student')->latest()->get();
    }
}
