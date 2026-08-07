<?php

namespace App\Http\Controllers\Teacher;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Teacher\Dashboard\GetTeacherDashboardStatsAction;
use App\Actions\Teacher\Dashboard\GetTeacherAbsenceHistoryAction;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard teacher.
     */
    public function index(
        Request $request,
        GetTeacherDashboardStatsAction $action,
    ) {
        $teacher = Auth::user();
        $bulan = $request->get("bulan", Carbon::today()->month);
        $tahun = Carbon::today()->year;

        $stats = $action->execute($teacher, $bulan, $tahun);
        $stats["teacher"] = $teacher;
        $stats["bulan"] = $bulan;

        return view("teacher.index", $stats);
    }

    /**
     * Menampilkan riwayat absensi teacher.
     */
    public function riwayat(
        Request $request,
        GetTeacherAbsenceHistoryAction $action,
    ) {
        $user = Auth::user();
        $bulan = $request->get("month", Carbon::now()->month);
        $tahun = Carbon::now()->year;

        $history = $action->execute($user, $bulan, $tahun);

        return view("teacher.history", compact("user", "history", "bulan"));
    }
}
