<?php

namespace App\Actions\Activity;

use App\Models\Activity;
use Illuminate\Support\Collection;

class GetRecentActivitiesAction
{
    /**
     * Mengambil 5 aktivitas terbaru yang diformat.
     */
    public function execute(): Collection
    {
        return Activity::latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'judul' => $item->description ?? ($item->title ?? 'Aktivitas Baru'),
                    'tipe' => $item->type ?? 'info',
                    'waktu' => $item->created_at,
                ];
            });
    }
}
