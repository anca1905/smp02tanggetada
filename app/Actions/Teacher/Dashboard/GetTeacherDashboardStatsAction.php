<?php

namespace App\Actions\Teacher\Dashboard;

use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\Teacher_absence;
use Carbon\Carbon;

class GetTeacherDashboardStatsAction
{
    /**
     * Get statistics and charts for teacher dashboard
     *
     * @param  int  $bulan
     * @param  int  $tahun
     */
    public function execute(Teacher $teacher, $bulan, $tahun): array
    {
        $today = Carbon::today();

        $todayAtt = Teacher_absence::where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->first();

        $status_datang = $todayAtt && $todayAtt->arrival_time
            ? 'Sudah Absen ('.Carbon::parse($todayAtt->arrival_time)->format('H:i').')'
            : 'Belum Absen';

        $status_pulang = $todayAtt && $todayAtt->return_time
            ? 'Sudah Absen ('.Carbon::parse($todayAtt->return_time)->format('H:i').')'
            : 'Belum Absen';

        $totalHadir = Teacher_absence::where('teacher_id', $teacher->id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->whereNotNull('arrival_time')
            ->count();

        $totalHariKerja = 25;

        $chartDataDatang = [];
        $chartDataPulang = [];
        $daysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;

        $monthlyAtt = Teacher_absence::where('teacher_id', $teacher->id)
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

        $recentAtts = Teacher_absence::where('teacher_id', $teacher->id)
            ->orderBy('date', 'desc')->take(3)->get();

        foreach ($recentAtts as $att) {
            if ($att->arrival_time) {
                $logs->push((object) [
                    'title' => 'Absen Datang',
                    'tipe' => 'datang',
                    'time' => Carbon::parse($att->date.' '.$att->arrival_time),
                ]);
            }
            if ($att->return_time) {
                $logs->push((object) [
                    'title' => 'Absen Pulang',
                    'tipe' => 'pulang',
                    'time' => Carbon::parse($att->date.' '.$att->return_time),
                ]);
            }
        }

        $recentClassAtts = Attendance::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')->take(3)->get();

        foreach ($recentClassAtts as $classAtt) {
            $logs->push((object) [
                'title' => 'Input Presensi Kelas '.$classAtt->class,
                'tipe' => 'absensi',
                'time' => $classAtt->created_at,
            ]);
        }

        $aktivitas = $logs->sortByDesc('time')->take(5);

        $riwayat = $recentAtts->map(function ($item) {
            return [
                'tgl' => Carbon::parse($item->date)->isoFormat('dddd, D MMMM Y'),
                'datang' => $item->arrival_time ? Carbon::parse($item->arrival_time)->format('H:i') : '-',
                'pulang' => $item->return_time ? Carbon::parse($item->return_time)->format('H:i') : '-',
                'status' => 'Hadir',
            ];
        });

        $todayFormatted = $today->isoFormat('dddd, D MMMM Y');

        return compact(
            'todayFormatted',
            'status_datang',
            'status_pulang',
            'totalHadir',
            'totalHariKerja',
            'chartDataDatang',
            'chartDataPulang',
            'aktivitas',
            'riwayat'
        );
    }
}
