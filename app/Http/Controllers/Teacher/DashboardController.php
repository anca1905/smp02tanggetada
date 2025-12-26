<?php

namespace App\Http\Controllers\Teacher;

use Carbon\Carbon;
use App\Models\Teacher;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Teacher_absence;
use App\Models\StudentAttendance;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $today = Carbon::today();
        $bulan = $request->get('bulan', $today->month);
        $tahun = $today->year;

        $todayAtt = Teacher_absence::where('teacher_id', $teacher->teacher_id)
            ->whereDate('date', $today)
            ->first();

        $status_datang = $todayAtt && $todayAtt->arrival_time
            ? 'Sudah Absen (' . Carbon::parse($todayAtt->arrival_time)->format('H:i') . ')'
            : 'Belum Absen';

        $status_pulang = $todayAtt && $todayAtt->return_time
            ? 'Sudah Absen (' . Carbon::parse($todayAtt->return_time)->format('H:i') . ')'
            : 'Belum Absen';

        $totalHadir = Teacher_absence::where('teacher_id', $teacher->teacher_id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->whereNotNull('arrival_time')
            ->count();

        $totalHariKerja = 25;

        $chartDataDatang = [];
        $chartDataPulang = [];
        $daysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;

        $monthlyAtt = Teacher_absence::where('teacher_id', $teacher->teacher_id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->get()
            ->keyBy('date');

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateKey = Carbon::create($tahun, $bulan, $i)->format('Y-m-d');

            $chartDataDatang[] = isset($monthlyAtt[$dateKey]) && $monthlyAtt[$dateKey]->arrival_time ? 1 : 0;
            $chartDataPulang[] = isset($monthlyAtt[$dateKey]) && $monthlyAtt[$dateKey]->return_time ? 1 : 0;
        }

        $logs = collect();

        $recentAtts = Teacher_absence::where('teacher_id', $teacher->teacher_id)
            ->orderBy('date', 'desc')->take(3)->get();

        foreach ($recentAtts as $att) {
            if ($att->arrival_time) {
                $logs->push((object)[
                    'title' => 'Absen Datang',
                    'tipe' => 'datang',
                    'time' => Carbon::parse($att->date . ' ' . $att->arrival_time)
                ]);
            }
            if ($att->return_time) {
                $logs->push((object)[
                    'title' => 'Absen Pulang',
                    'tipe' => 'pulang',
                    'time' => Carbon::parse($att->date . ' ' . $att->return_time)
                ]);
            }
        }

        $recentClassAtts = Attendance::where('teacher_id', $teacher->teacher_id)
            ->orderBy('created_at', 'desc')->take(3)->get();

        foreach ($recentClassAtts as $classAtt) {
            $logs->push((object)[
                'title' => 'Input Presensi Kelas ' . $classAtt->class,
                'tipe' => 'absensi',
                'time' => $classAtt->created_at
            ]);
        }

        $aktivitas = $logs->sortByDesc('time')->take(5);

        $riwayat = $recentAtts->map(function ($item) {
            return [
                'tgl' => Carbon::parse($item->date)->isoFormat('dddd, D MMMM Y'),
                'datang' => $item->arrival_time ? Carbon::parse($item->arrival_time)->format('H:i') : '-',
                'pulang' => $item->return_time ? Carbon::parse($item->return_time)->format('H:i') : '-',
                'status' => 'Hadir'
            ];
        });

        $todayFormatted = $today->isoFormat('dddd, D MMMM Y');

        return view('teacher.index', compact(
            'teacher',
            'todayFormatted',
            'status_datang',
            'status_pulang',
            'totalHadir',
            'totalHariKerja',
            'chartDataDatang',
            'chartDataPulang',
            'bulan',
            'aktivitas',
            'riwayat'
        ));
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $bulan = $request->get('month', Carbon::now()->month);

        $history = Teacher_absence::where('teacher_id', $user->teacher_id)
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', $bulan)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('teacher.history', compact('user', 'history', 'bulan'));
    }
}