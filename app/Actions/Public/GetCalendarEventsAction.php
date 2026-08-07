<?php

namespace App\Actions\Public;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GetCalendarEventsAction
{
    /**
     * Get all events grouped by month.
     */
    public function execute(): Collection
    {
        $events = Event::orderBy('start_date', 'asc')->get();

        return $events->groupBy(function ($date) {
            return Carbon::parse($date->start_date)->isoFormat('MMMM Y');
        });
    }
}
