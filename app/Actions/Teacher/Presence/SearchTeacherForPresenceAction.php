<?php

namespace App\Actions\Teacher\Presence;

use App\Models\Teacher;

class SearchTeacherForPresenceAction
{
    /**
     * Search teacher for presence autocomplete
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function execute($query)
    {
        if (strlen($query) < 3) return [];

        return Teacher::where('name', 'like', "%{$query}%")
            ->orWhere('ID', 'like', "%{$query}%")
            ->where('status', 'Active')
            ->limit(5)
            ->get(['name', 'ID']);
    }
}
