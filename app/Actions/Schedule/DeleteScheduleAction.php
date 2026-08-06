<?php

namespace App\Actions\Schedule;

use App\Models\Schedule;

class DeleteScheduleAction
{
    /**
     * Menghapus jadwal pelajaran dari basis data
     *
     * @param  Schedule  $schedule  Instance jadwal pelajaran yang akan dihapus
     */
    public function execute(Schedule $schedule): void
    {
        $schedule->delete();
    }
}
