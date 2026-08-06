<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Collection;

class GetClassroomsAction
{
    /**
     * Mengambil daftar semua kelas.
     */
    public function execute(): Collection
    {
        return Classroom::all();
    }
}
