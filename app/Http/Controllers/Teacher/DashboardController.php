<?php

namespace App\Http\Controllers\Teacher;

use App\Actions\Teacher\Dashboard\GetTeacherDashboardStatsAction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard guru.
     */
    public function index(
        Request $request,
        GetTeacherDashboardStatsAction $action,
    ) {
        $teacher = Auth::user();
        $bulan = $request->get('bulan', Carbon::today()->month);
        $tahun = Carbon::today()->year;

        $stats = $action->execute($teacher, $bulan, $tahun);
        $stats['teacher'] = $teacher;
        $stats['bulan']   = $bulan;

        return view('teacher.index', $stats);
    }
}
