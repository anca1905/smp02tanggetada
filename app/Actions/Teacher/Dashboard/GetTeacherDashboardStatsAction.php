<?php

namespace App\Actions\Teacher\Dashboard;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Event;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use Carbon\Carbon;

class GetTeacherDashboardStatsAction
{
    /**
     * Mengompilasi seluruh data statistik dashboard guru:
     * KPI rombel & siswa yang diajar, jam mengajar, status wali kelas,
     * jadwal hari ini, grafik beban mengajar mingguan, dan pengumuman.
     */
    public function execute(Teacher $teacher, int|string $bulan, int|string $tahun): array
    {
        $today = Carbon::today();
        $daysMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $todayIndo = $daysMap[$today->format('l')] ?? 'Senin';

        // ── 1. KPI Cards Data ──────────────────────────────────────────────
        // Rombongan belajar yang diajar oleh guru ini
        $taughtClassrooms = Classroom::whereHas('schedules', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
            ->withCount(['students' => function ($q) {
                $q->where('student_status', 'Active');
            }])
            ->get();
        $totalKelasDiampu = $taughtClassrooms->count();

        // Siswa aktif di kelas yang diajar
        $taughtClassroomIds = $taughtClassrooms->pluck('id');
        $totalSiswaDiajar = Student::whereIn('classroom_id', $taughtClassroomIds)
            ->where('student_status', 'Active')
            ->count();

        // Total jam / jadwal mengajar per minggu
        $totalJamMengajar = Schedule::where('teacher_id', $teacher->id)->count();

        // Status dan data Wali Kelas
        $waliKelas = $teacher->classroom;
        $waliKelasName = $waliKelas ? $waliKelas->name : ($teacher->wali_kelas_name ?: null);
        $totalSiswaWaliKelas = $waliKelas ? $waliKelas->students()->where('student_status', 'Active')->count() : 0;

        // ── 2. Jadwal Mengajar Hari Ini ────────────────────────────────────
        $todaySchedules = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $todayIndo)
            ->orderBy('start_time')
            ->get();

        // ── 3. Distribusi Jadwal Mengajar Mingguan (Chart) ─────────────────
        $weeklyDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $weeklyScheduleData = [];
        foreach ($weeklyDays as $d) {
            $weeklyScheduleData[] = Schedule::where('teacher_id', $teacher->id)->where('day', $d)->count();
        }

        // ── 4. Pengumuman & Agenda Sekolah ─────────────────────────────────
        $announcements = Post::where('is_published', true)->latest()->take(3)->get(['id', 'title', 'category', 'created_at']);
        $events = Event::where('start_date', '>=', $today)->orderBy('start_date')->take(3)->get();

        // ── 5. Presensi Siswa & Backward-Compatibility ─────────────────────
        $todaySessions = Attendance::where('teacher_id', $teacher->id)
            ->whereDate('date', $today)
            ->get();

        $sessionsDone = $todaySessions->pluck('session_type')->unique()->values()->toArray();
        $sessionLabels = [
            'apel' => 'Apel Pagi',
            'kelas' => 'Di Kelas',
            'pulang' => 'Pulang',
        ];

        $sessionStatus = [];
        foreach (['apel', 'kelas', 'pulang'] as $s) {
            $sessionStatus[$s] = [
                'label' => $sessionLabels[$s],
                'done' => in_array($s, $sessionsDone),
            ];
        }

        $totalSesiKelas = Attendance::where('teacher_id', $teacher->id)
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->count();

        $recentAtts = Attendance::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $aktivitas = $recentAtts->map(function ($att) use ($sessionLabels) {
            return (object) [
                'title' => 'Input Absensi Sesi '.($sessionLabels[$att->session_type] ?? $att->session_type),
                'tipe' => 'absensi',
                'time' => $att->created_at,
            ];
        });

        $riwayat = $recentAtts->map(function ($att) use ($sessionLabels) {
            $scanned = StudentAttendance::where('attendance_id', $att->id)
                ->where('status', 'present')
                ->count();
            $total = StudentAttendance::where('attendance_id', $att->id)->count();

            return [
                'tgl' => Carbon::parse($att->date)->isoFormat('dddd, D MMMM Y'),
                'sesi' => $sessionLabels[$att->session_type] ?? $att->session_type,
                'hadir' => $scanned,
                'total' => $total,
            ];
        });

        $todayFormatted = $today->isoFormat('dddd, D MMMM Y');

        return compact(
            'today',
            'todayFormatted',
            'todayIndo',
            'taughtClassrooms',
            'totalKelasDiampu',
            'totalSiswaDiajar',
            'totalJamMengajar',
            'waliKelas',
            'waliKelasName',
            'totalSiswaWaliKelas',
            'todaySchedules',
            'weeklyDays',
            'weeklyScheduleData',
            'announcements',
            'events',
            'sessionStatus',
            'totalSesiKelas',
            'aktivitas',
            'riwayat',
            'bulan',
        );
    }
}
