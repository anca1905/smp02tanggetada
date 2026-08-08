<?php

namespace App\Actions\Dashboard;

use App\Actions\Activity\GetRecentActivitiesAction;
use App\Actions\Ppdb\GetPpdbStatsAction;
use App\Actions\Student\GetActiveStudentCountAction;
use App\Actions\Teacher\GetActiveTeacherCountAction;
use App\Actions\Teacher\GetTeacherAttendanceStatsAction;
use App\Models\RoomBorrowing;
use App\Models\Student;
use App\Models\TeacherAbsence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GetAdminTuDashboardDataAction
{
    public function __construct(
        protected GetActiveStudentCountAction $activeStudentAction,
        protected GetActiveTeacherCountAction $activeTeacherAction,
        protected GetTeacherAttendanceStatsAction $teacherAttendanceAction,
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
        $attendanceStats = $this->teacherAttendanceAction->execute();
        $ppdbStats = $this->ppdbStatsAction->execute();

        $stats = [
            'total_guru' => $this->activeTeacherAction->execute(),
            'total_siswa' => $this->activeStudentAction->execute(),
            'datang' => $attendanceStats['datang'],
            'pulang' => $attendanceStats['pulang'],
            'total_ruang' => RoomBorrowing::whereDate('borrow_date', $today)->count(),
        ];

        // 2. Room Chart (Spesifik Dashboard)
        $roomChartRaw = RoomBorrowing::selectRaw('MONTH(borrow_date) as month, COUNT(*) as count')
            ->whereYear('borrow_date', $today->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $roomChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $roomChartData[] = $roomChartRaw[$i] ?? 0;
        }

        // 3. Teacher Chart (Spesifik Dashboard)
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i));
        }

        $teacherChartLabels = $dates->map(fn ($d) => $d->format('D, d M'))->toArray();
        $teacherChartData = [];
        foreach ($dates as $date) {
            $teacherChartData[] = TeacherAbsence::whereDate('date', $date)
                ->whereNotNull('arrival_time')
                ->count();
        }

        // 4. Student Chart (Spesifik Dashboard)
        $studentGroups = Student::join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
            ->select('classrooms.name', DB::raw('count(students.id) as total'))
            ->groupBy('classrooms.name')
            ->pluck('total', 'classrooms.name');

        $studentChartLabels = $studentGroups->keys()->toArray();
        $studentChartData = $studentGroups->values()->toArray();

        return [
            'stats' => $stats,
            'aktivitas' => $this->recentActivitiesAction->execute(),
            'roomChartData' => $roomChartData,
            'teacherChartLabels' => $teacherChartLabels,
            'teacherChartData' => $teacherChartData,
            'studentChartLabels' => $studentChartLabels,
            'studentChartData' => $studentChartData,
            'ppdbPending' => $ppdbStats['pending'],
            'ppdbTotal' => $ppdbStats['total'],
            'ppdbAccepted' => $ppdbStats['accepted'],
        ];
    }
}
