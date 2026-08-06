<?php

namespace App\Actions\Borrowing;

use App\Models\RoomBorrowing;

class UpdateBorrowingAction
{
    /**
     * Memperbarui data peminjaman ruangan yang sudah ada.
     *
     * @param  array  $data  Data pemutakhiran
     * @param  RoomBorrowing  $borrowing  Instance peminjaman yang akan diperbarui
     */
    public function execute(array $data, RoomBorrowing $borrowing): RoomBorrowing
    {
        $borrowing->update($data);

        return $borrowing;
    }
}
