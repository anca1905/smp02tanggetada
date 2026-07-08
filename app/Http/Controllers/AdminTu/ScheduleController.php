<?php

namespace App\Http\Controllers\AdminTu;

use App\Models\Setting;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil list kelas untuk Dropdown Filter
        $classrooms = Classroom::orderBy('level')->orderBy('name')->get();

        // 2. Cek apakah user sudah memilih kelas?
        $selectedClassId = $request->input('classroom_id');
        $schedules = collect(); // Default kosong

        if ($selectedClassId) {
            // Ambil jadwal kelas tsb, urutkan berdasarkan Hari & Jam
            $schedules = Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $selectedClassId)
                ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                ->orderBy('start_time')
                ->get()
                ->groupBy('day'); // Kelompokkan per hari biar rapi
        }

        // 3. Ambil data untuk Modal Tambah Jadwal
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        return view('tu.schedules.index', compact('classrooms', 'selectedClassId', 'schedules', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Cek bentrok jadwal (Opsional, fitur advanced)
        // Logika sederhana: guru yang sama tidak bisa mengajar di dua kelas di jam yang sama
        $bentrok = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors(['teacher_id' => 'Guru ini sudah ada jadwal mengajar di kelas lain pada jam tersebut!']);
        }

        Schedule::create($request->all());

        return back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Schedule::destroy($id);
        return back()->with('success', 'Jadwal dihapus');
    }
}
