<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\StudentAttendanceDetail;
use App\Models\Teacher_absence;
use App\Models\Post;
use App\Models\Event;
use App\Models\Bill;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $days = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
        $todayIndo = $days[$today->format('l')] ?? 'Senin';

        // ── KPI Cards ──────────────────────────────────────────────────
        $totalGuru    = Teacher::where('status', 'Active')->count();
        $totalSiswa   = Student::where('student_status', 'Active')->count();
        $totalKelas   = Classroom::count();
        $totalMapel   = Subject::count();
        $totalFasilitas = Facility::count();

        // Teacher attendance today
        $guruHadir    = Teacher_absence::whereDate('date', $today)->whereNotNull('arrival_time')->count();
        $guruTerlambat = Teacher_absence::whereDate('date', $today)
                            ->whereNotNull('arrival_time')
                            ->whereTime('arrival_time', '>', '07:30:00')
                            ->count();

        // Student attendance % today
        $totalSessionsToday = Attendance::whereDate('created_at', $today)->count();
        $hadirToday = StudentAttendanceDetail::whereHas('attendance', fn($q) => $q->whereDate('created_at', $today))
                        ->whereIn('status', ['Hadir', 'Late'])->count();
        $kehadiranSiswaHariIni = $totalSessionsToday > 0
            ? round(($hadirToday / ($totalSessionsToday * $totalSiswa)) * 100, 2)
            : 0;

        // ── Attendance Chart (6 months) ────────────────────────────────
        $attendanceChart = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->isoFormat('MMM YYYY');

            $totalInMonth = Attendance::whereYear('created_at', $month->year)
                                ->whereMonth('created_at', $month->month)->count();
            $hadirInMonth = StudentAttendanceDetail::whereHas('attendance', function($q) use ($month) {
                $q->whereYear('created_at', $month->year)
                  ->whereMonth('created_at', $month->month);
            })->whereIn('status', ['Hadir', 'Late'])->count();

            $denom = $totalInMonth * max($totalSiswa, 1);
            $attendanceChart[] = $denom > 0 ? round(($hadirInMonth / $denom) * 100, 2) : 0;
        }

        // ── Top 5 Active Teachers ──────────────────────────────────────
        $topTeachers = Teacher::withCount(['absences as hadir_count' => function ($q) {
                            $q->whereNotNull('arrival_time');
                        }])
                        ->orderByDesc('hadir_count')
                        ->take(5)->get();

        // ── Latest Announcements ───────────────────────────────────────
        $announcements = Post::where('is_published', true)->latest()->take(3)->get(['id','title','category','created_at']);

        // ── Upcoming Events ────────────────────────────────────────────
        $events = Event::where('start_date', '>=', $today)->orderBy('start_date')->take(4)->get();

        // ── Student distribution per class ────────────────────────────
        $studentPerClass = Classroom::withCount(['students' => fn($q) => $q->where('student_status','Active')])->get();

        // ── Financial summary ─────────────────────────────────────────
        $totalTagihan   = Bill::where('status', 'unpaid')->sum('amount');
        $totalTerbayar  = Bill::where('status', 'paid')->sum('amount');

        return view('principal.dashboard', compact(
            'totalGuru', 'totalSiswa', 'totalKelas', 'totalMapel', 'totalFasilitas',
            'guruHadir', 'guruTerlambat', 'kehadiranSiswaHariIni',
            'attendanceChart', 'monthLabels',
            'topTeachers',
            'announcements', 'events',
            'studentPerClass',
            'totalTagihan', 'totalTerbayar',
            'today'
        ));
    }
}
