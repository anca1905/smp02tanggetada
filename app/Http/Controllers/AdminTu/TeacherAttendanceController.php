<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherAttendanceController extends Controller
{
    /**
     * Menampilkan rekap presensi harian Guru & Tenaga Kependidikan (Fitur PRO).
     */
    public function index(Request $request): View
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $teachers = Teacher::with(['teacherAttendances' => function ($query) use ($date) {
            $query->whereDate('date', $date);
        }])->orderBy('name')->paginate(15);

        return view('tu.teacher_attendance.index', compact('teachers', 'date'));
    }
}
