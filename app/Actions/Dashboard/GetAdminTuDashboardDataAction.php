<?php

namespace App\Actions\Dashboard;

use App\Actions\Activity\GetRecentActivitiesAction;
use App\Actions\Ppdb\GetPpdbStatsAction;
use App\Actions\Student\GetActiveStudentCountAction;
use App\Actions\Teacher\GetActiveTeacherCountAction;
use App\Models\Classroom;
use App\Models\Event;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GetAdminTuDashboardDataAction
{
    public function __construct(
        protected GetActiveStudentCountAction $activeStudentAction,
        protected GetActiveTeacherCountAction $activeTeacherAction,
        protected GetRecentActivitiesAction $recentActivitiesAction,
        protected GetPpdbStatsAction $ppdbStatsAction
    ) {}

    /**
     * Merangkum semua data yang dibutuhkan untuk Dashboard Admin TU.
     */
    public function execute(): array
    {
        $today = Carbon::today();

        // 1. Ambil dari Reusable Actions
        $ppdbStats = $this->ppdbStatsAction->execute();

        $stats = [
            'total_guru' => $this->activeTeacherAction->execute(),
            'total_siswa' => $this->activeStudentAction->execute(),
            'total_kelas' => Classroom::count(),
            'total_agenda' => Event::where('start_date', '>=', $today)->count(),
            'total_ppdb' => $ppdbStats['total'],
            'total_ruang' => 0, // Fitur peminjaman ruang dinonaktifkan
        ];

        // 2. Agenda Mendatang untuk Widget Dashboard
        $upcomingEvents = Event::where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // 3. Distribusi Siswa per Kelas
        $studentGroups = Student::join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
            ->select('classrooms.name', DB::raw('count(students.id) as total'))
            ->groupBy('classrooms.name')
            ->pluck('total', 'classrooms.name');

        $studentChartLabels = $studentGroups->keys()->toArray();
        $studentChartData = $studentGroups->values()->toArray();

        return [
            'stats' => $stats,
            'aktivitas' => $this->recentActivitiesAction->execute(),
            'upcomingEvents' => $upcomingEvents,
            'roomChartData' => array_fill(0, 12, 0),
            'teacherChartLabels' => [],
            'teacherChartData' => [],
            'studentChartLabels' => $studentChartLabels,
            'studentChartData' => $studentChartData,
            'ppdbPending' => $ppdbStats['pending'],
            'ppdbTotal' => $ppdbStats['total'],
            'ppdbAccepted' => $ppdbStats['accepted'],
        ];
    }
}
