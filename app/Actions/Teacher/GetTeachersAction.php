<?php

namespace App\Actions\Teacher;

use App\Models\Teacher;
use Illuminate\Pagination\LengthAwarePaginator;

class GetTeachersAction
{
    /**
     * Mengambil daftar guru dengan pagination dan filter pencarian
     *
     * @param  string|null  $search  Kata kunci pencarian
     * @param  int  $perPage  Jumlah item per halaman
     */
    public function execute(
        ?string $search,
        int $perPage = 10,
    ): LengthAwarePaginator {
        $query = Teacher::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }
}
