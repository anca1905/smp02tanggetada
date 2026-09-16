<?php

namespace App\Actions\Principal;

use App\Models\Attendance;
use App\Models\Bill;
use App\Models\Classroom;
use App\Models\Event;
use App\Models\Facility;
use App\Models\Post;
use App\Models\Student;
use App\Models\StudentAttendanceDetail;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GetPrincipalDashboardAction
{
    /**
     * Mengompilasi seluruh statistik dashboard kepala sekolah: KPI cards,
     * attendance chart 6 bulan, top 5 guru teraktif, pengumuman terbaru,
     * upcoming events, distribusi siswa per kelas, summary keuangan.
     */
    public function execute(): array
    {
        $today = Carbon::today();
        $days = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
        $todayIndo = $days[$today->format('l')] ?? 'Senin';

        // ── KPI Cards ──────────────────────────────────────────────────
        $totalGuru = Teacher::where('status', 'Active')->count();
        $totalSiswa = Student::where('student_status', 'Active')->count();
        $totalKelas = Classroom::count();
        $totalMapel = Subject::count();
        $totalFasilitas = Facility::count();

        // Teacher attendance today (fitur presensi guru sudah dihapus)
        $guruHadir = 0;
        $guruTerlambat = 0;

        // Student attendance % today
        $totalSessionsToday = Attendance::whereDate('created_at', $today)->count();
        $hadirToday = StudentAttendanceDetail::whereHas('attendance', fn ($q) => $q->whereDate('created_at', $today))
            ->whereIn('status', ['Hadir', 'Late'])->count();
        $kehadiranSiswaHariIni = $totalSessionsToday > 0
            ? round(($hadirToday / ($totalSessionsToday * max($totalSiswa, 1))) * 100, 2)
            : 0;

        // ── Attendance Chart (6 months) ────────────────────────────────
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        // Single query for Attendance headers
        $attendanceGroups = Attendance::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_sessions')
        )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($item) => $item->year.'-'.str_pad($item->month, 2, '0', STR_PAD_LEFT));

        // Single query for Attendance details
        $detailGroups = StudentAttendanceDetail::join('attendances', 'student_attendance_details.attendance_id', '=', 'attendances.id')
            ->select(
                DB::raw('YEAR(attendances.created_at) as year'),
                DB::raw('MONTH(attendances.created_at) as month'),
                DB::raw('COUNT(*) as total_hadir')
            )
            ->where('attendances.created_at', '>=', $sixMonthsAgo)
            ->whereIn('student_attendance_details.status', ['Hadir', 'Late'])
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($item) => $item->year.'-'.str_pad($item->month, 2, '0', STR_PAD_LEFT));

        $attendanceChart = [];
        $monthLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthLabels[] = $month->isoFormat('MMM YYYY');

            $totalInMonth = $attendanceGroups->has($monthKey) ? $attendanceGroups[$monthKey]->total_sessions : 0;
            $hadirInMonth = $detailGroups->has($monthKey) ? $detailGroups[$monthKey]->total_hadir : 0;

            $denom = $totalInMonth * max($totalSiswa, 1);
            $attendanceChart[] = $denom > 0 ? round(($hadirInMonth / $denom) * 100, 2) : 0;
        }

        // ── Top 5 Active Teachers (Berdasarkan jumlah absensi yang dibuat) ─────────
        $topTeachers = Teacher::withCount('schedules')
            ->orderByDesc('schedules_count')
            ->take(5)->get();

        // ── Latest Announcements ───────────────────────────────────────
        $announcements = Post::where('is_published', true)->latest()->take(3)->get(['id', 'title', 'category', 'created_at']);

        // ── Upcoming Events ────────────────────────────────────────────
        $events = Event::where('start_date', '>=', $today)->orderBy('start_date')->take(4)->get();

        // ── Student distribution per class ────────────────────────────
        $studentPerClass = Classroom::withCount(['students' => fn ($q) => $q->where('student_status', 'Active')])->get();

        // ── Financial summary ─────────────────────────────────────────
        $totalTagihan = Bill::where('status', 'unpaid')->sum('amount');
        $totalTerbayar = Bill::where('status', 'paid')->sum('amount');

        return compact(
            'totalGuru', 'totalSiswa', 'totalKelas', 'totalMapel', 'totalFasilitas',
            'guruHadir', 'guruTerlambat', 'kehadiranSiswaHariIni',
            'attendanceChart', 'monthLabels',
            'topTeachers',
            'announcements', 'events',
            'studentPerClass',
            'totalTagihan', 'totalTerbayar',
            'today', 'todayIndo'
        );
    }
}
