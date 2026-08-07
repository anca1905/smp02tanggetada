<?php

namespace App\Actions\Room;

use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;

class GetRoomsAction
{
    /**
     * Mengeksekusi query untuk mengambil daftar ruangan dengan
     * pagination dan filter berdasarkan query parameter.
     */
    public function execute(?string $search, int $perPage): LengthAwarePaginator
    {
        $query = Room::query();
        if ($search) {
            $query
                ->where('room_name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }
}
