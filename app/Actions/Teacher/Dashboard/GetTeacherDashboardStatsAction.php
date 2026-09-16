<?php

namespace App\Actions\Teacher\Dashboard;

use App\Models\Attendance;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use Carbon\Carbon;

class GetTeacherDashboardStatsAction
{
    /**
     * Statistik dashboard guru — difokuskan ke aktivitas mengajar & absensi siswa.
     */
    public function execute(Teacher $teacher, $bulan, $tahun): array
    {
        $today = Carbon::today();

        // Ambil sesi absensi siswa yang sudah guru ini buat hari ini
        $todaySessions = Attendance::where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->get();

        $sessionsDone = $todaySessions->pluck('session_type')->unique()->values()->toArray();

        $sessionLabels = [
            'apel'   => 'Apel Pagi',
            'kelas'  => 'Di Kelas',
            'pulang' => 'Pulang',
        ];

        $sessionStatus = [];
        foreach (['apel', 'kelas', 'pulang'] as $s) {
            $sessionStatus[$s] = [
                'label' => $sessionLabels[$s],
                'done'  => in_array($s, $sessionsDone),
            ];
        }

        // Total sesi kelas yang sudah dibuat bulan ini
        $totalSesiKelas = Attendance::where('teacher_id', $teacher->id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->count();

        // Aktivitas terbaru dari absensi yang pernah diisi guru
        $recentAtts = Attendance::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $aktivitas = $recentAtts->map(function ($att) use ($sessionLabels) {
            return (object) [
                'title' => 'Input Absensi Sesi ' . ($sessionLabels[$att->session_type] ?? $att->session_type),
                'tipe'  => 'absensi',
                'time'  => $att->created_at,
            ];
        });

        // Riwayat 5 sesi terakhir
        $riwayat = $recentAtts->map(function ($att) use ($sessionLabels) {
            $scanned = StudentAttendance::where('attendance_id', $att->id)
                ->where('status', 'present')
                ->count();
            $total = StudentAttendance::where('attendance_id', $att->id)->count();

            return [
                'tgl'       => Carbon::parse($att->date)->isoFormat('dddd, D MMMM Y'),
                'sesi'      => $sessionLabels[$att->session_type] ?? $att->session_type,
                'hadir'     => $scanned,
                'total'     => $total,
            ];
        });

        $todayFormatted = $today->isoFormat('dddd, D MMMM Y');

        return compact(
            'todayFormatted',
            'sessionStatus',
            'totalSesiKelas',
            'aktivitas',
            'riwayat',
            'bulan',
        );
    }
}
