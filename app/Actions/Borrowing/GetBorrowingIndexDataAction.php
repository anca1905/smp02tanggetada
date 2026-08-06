<?php

namespace App\Actions\Borrowing;

use App\Models\Room;
use App\Models\RoomBorrowing;

class GetBorrowingIndexDataAction
{
    /**
     * Mengambil daftar riwayat peminjaman beserta filter pencarian dan status peminjaman.
     *
     * @param  string|null  $search  Kata kunci pencarian nama/kegiatan
     * @param  string|null  $status  Filter status realtime: upcoming, ongoing, atau completed
     */
    public function execute(?string $search, ?string $status): array
    {
        $ruanganList = Room::all();
        $query = RoomBorrowing::query();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")->orWhere(
                    'activity_description',
                    'like',
                    "%{$search}%",
                );
            });
        }

        if (! empty($status)) {
            match ($status) {
                'upcoming' => $query->upcoming(),
                'ongoing' => $query->ongoing(),
                'completed' => $query->completed(),
                default => null,
            };
        }

        $bookings = $query
            ->orderBy('borrow_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(10)
            ->withQueryString();

        return compact('bookings', 'ruanganList');
    }
}
