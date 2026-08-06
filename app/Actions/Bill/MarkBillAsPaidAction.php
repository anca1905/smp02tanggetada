<?php

namespace App\Actions\Bill;

use App\Models\Bill;
use Carbon\Carbon;

class MarkBillAsPaidAction
{
    /**
     * Menyetel status tagihan siswa menjadi Lunas (paid).
     */
    public function execute(Bill $bill): Bill
    {
        // Pemuatan relasi siswa jika belum dimuat demi menyokong pengambilan student_name yang dibutuhkan pesan notifikasi
        $bill->loadMissing('student');

        $bill->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return $bill;
    }
}
