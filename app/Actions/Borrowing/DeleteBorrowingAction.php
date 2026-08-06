<?php

namespace App\Actions\Borrowing;

use App\Models\RoomBorrowing;

class DeleteBorrowingAction
{
    /**
     * Menghapus entri data peminjaman dari sistem.
     *
     * @param  RoomBorrowing  $borrowing  Instance peminjaman yang akan dihapus
     */
    public function execute(RoomBorrowing $borrowing): void
    {
        $borrowing->delete();
    }
}
