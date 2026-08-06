<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Ppdb;
use App\Models\RoomBorrowing;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Teacher_absence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_guru' => Teacher::where('status', 'Active')->count(),
            'total_siswa' => Student::where(
                'student_status',
                'Active',
            )->count(),
            'datang' => Teacher_absence::whereDate('date', $today)
                ->whereNotNull('arrival_time')
                ->count(),
            'pulang' => Teacher_absence::whereDate('date', $today)
                ->whereNotNull('return_time')
                ->count(),
            'total_ruang' => RoomBorrowing::whereDate(
                'borrow_date',
                $today,
            )->count(),
        ];

        $roomChartRaw = RoomBorrowing::selectRaw(
            'MONTH(borrow_date) as month, COUNT(*) as count',
        )
            ->whereYear('borrow_date', $today->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $roomChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $roomChartData[] = $roomChartRaw[$i] ?? 0;
        }

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i));
        }

        $teacherChartLabels = $dates
            ->map(fn ($d) => $d->format('D, d M'))
            ->toArray();
        $teacherChartData = [];

        foreach ($dates as $date) {
            $teacherChartData[] = Teacher_absence::whereDate('date', $date)
                ->whereNotNull('arrival_time')
                ->count();
        }

        $studentGroups = Student::join(
            'classrooms',
            'students.classroom_id',
            '=',
            'classrooms.id',
        )
            ->select('classrooms.name', DB::raw('count(students.id) as total'))
            ->groupBy('classrooms.name')
            ->pluck('total', 'classrooms.name');

        $studentChartLabels = $studentGroups->keys()->toArray();
        $studentChartData = $studentGroups->values()->toArray();

        $aktivitas = Activity::latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'judul' => $item->description ??
                        ($item->title ?? 'Aktivitas Baru'),
                    'tipe' => $item->type ?? 'info',
                    'waktu' => $item->created_at,
                ];
            });

        $ppdbPending = Ppdb::where('status_pendaftaran', 'pending')->count();
        $ppdbTotal = Ppdb::count();
        $ppdbAccepted = Ppdb::where('status_pendaftaran', 'Accepted')->count();

        return view(
            'tu.index',
            compact(
                'stats',
                'aktivitas',
                'roomChartData',
                'teacherChartLabels',
                'teacherChartData',
                'studentChartLabels',
                'studentChartData',
                'ppdbPending',
                'ppdbTotal',
                'ppdbAccepted',
            ),
        );
    }
}
