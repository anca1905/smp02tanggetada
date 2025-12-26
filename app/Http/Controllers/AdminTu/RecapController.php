<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\Teacher_absence;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'teacher');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = Carbon::now()->year;
        $search = $request->get('search');

        $data = [];

        if ($kategori == 'teacher') {
            $query = Teacher_absence::with('teacher')
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun);

            if ($search) {
                $query->whereHas('teacher', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            $data = $query->orderBy('date', 'desc')->paginate(10);
        } else {
            $query = StudentAttendance::with(['siswa', 'attendance'])
                ->whereHas('attendance', function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('date', $bulan)
                        ->whereYear('date', $tahun);
                });

            if ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                        ->orWhere('class', 'like', "%{$search}%");
                });
            }

            if ($request->has('class') && $request->class != '') {
                $query->whereHas('siswa', function ($q) use ($request) {
                    $q->where('class', $request->class);
                });
            }

            $data = $query->paginate(10);
        }

        return view('tu.absenteeism_recap', compact('data', 'kategori', 'bulan'));
    }
}