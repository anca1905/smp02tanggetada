<?php

namespace App\Actions\Borrowing;

use App\Models\RoomBorrowing;
use Carbon\Carbon;

class CreateBorrowingAction
{
    /**
     * Mengeksekusi pengajuan peminjaman ruangan baru dengan penentuan status awal.
     *
     * @param  array  $data  Data yang sudah divalidasi
     */
    public function execute(array $data): RoomBorrowing
    {
        $status = 'upcoming';
        $borrowDate = Carbon::parse($data['borrow_date']);

        if ($borrowDate->isBefore(now()->startOfDay())) {
            $status = 'completed';
        }

        $data['status'] = $status;

        return RoomBorrowing::create($data);
    }
}
