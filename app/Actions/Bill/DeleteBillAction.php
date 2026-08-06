<?php

namespace App\Actions\Bill;

use App\Models\Bill;

class DeleteBillAction
{
    /**
     * Menghapus tagihan dari sistem
     */
    public function execute(Bill $bill): void
    {
        $bill->delete();
    }
}
