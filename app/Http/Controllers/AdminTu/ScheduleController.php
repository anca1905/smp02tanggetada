<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Schedule\CreateScheduleAction;
use App\Actions\Schedule\DeleteScheduleAction;
use App\Actions\Schedule\GetScheduleIndexDataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal berdasarkan kelas
     */
    public function index(
        Request $request,
        GetScheduleIndexDataAction $action,
    ): View {
        $data = $action->execute($request->input('classroom_id'));

        return view('tu.schedules.index', $data);
    }

    /**
     * Menyimpan jadwal baru
     */
    public function store(
        StoreScheduleRequest $request,
        CreateScheduleAction $action,
    ): RedirectResponse {
        $action->execute($request->validated());

        return back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Menghapus sebuah jadwal
     */
    public function destroy(
        Schedule $schedule,
        DeleteScheduleAction $action,
    ): RedirectResponse {
        $action->execute($schedule);

        return back()->with('success', 'Jadwal dihapus');
    }
}
