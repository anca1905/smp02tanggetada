<?php

namespace App\Console\Commands;

use App\Models\RoomBorrowing;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBorrowingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrowing:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Cari peminjaman yang masih 'upcoming' tapi waktu selesainya sudah lewat
        $borrowings = RoomBorrowing::all();

        foreach ($borrowings as $borrowing) {
            $start = Carbon::parse($borrowing->borrow_date.' '.$borrowing->start_time);
            $end = Carbon::parse($borrowing->borrow_date.' '.$borrowing->end_time);

            $newStatus = $borrowing->status;

            if ($now->lt($start)) {
                $newStatus = 'upcoming';
            } elseif ($now->between($start, $end)) {
                $newStatus = 'ongoing';
            } elseif ($now->gt($end)) {
                $newStatus = 'completed';
            }

            // Hanya update jika statusnya berubah untuk menghemat database
            if ($newStatus !== $borrowing->status) {
                $borrowing->update(['status' => $newStatus]);
            }
        }
    }
}
