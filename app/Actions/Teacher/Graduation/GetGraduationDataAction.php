<?php

namespace App\Actions\Teacher\Graduation;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GetGraduationDataAction
{
    /**
     * Mengambil siswa aktif dari kelas yang diwalikan oleh guru.
     *
     * @return array{students: Collection, kelas: string}
     */
    public function execute(?Teacher $teacher = null): array
    {
        $teacher = $teacher ?: Auth::guard('teacher')->user();

        if (! $teacher) {
            return [
                'students' => collect(),
                'kelas' => '-',
            ];
        }

        // 1. Cek relasi langsung hasOne classroom (classrooms.teacher_id = teacher.id)
        $currentClassroom = $teacher->classroom;

        // 2. Jika belum ketemu, cari berdasarkan nama homeroom_class
        if (! $currentClassroom && ! empty($teacher->homeroom_class)) {
            $rawName = trim($teacher->homeroom_class);
            $currentClassroom = Classroom::where('name', $rawName)->first();

            // Coba pencarian fleksibel untuk roman vs angka arab (contoh 'IX B' vs '9 B' atau 'IX-B')
            if (! $currentClassroom) {
                $altName1 = preg_replace_callback('/\b(IX|VIII|VII)\b/i', function ($matches) {
                    return match (strtoupper($matches[1])) {
                        'IX' => '9',
                        'VIII' => '8',
                        'VII' => '7',
                        default => $matches[1],
                    };
                }, $rawName);

                $altName2 = preg_replace_callback('/\b(9|8|7)\b/', function ($matches) {
                    return match ($matches[1]) {
                        '9' => 'IX',
                        '8' => 'VIII',
                        '7' => 'VII',
                        default => $matches[1],
                    };
                }, $rawName);

                $currentClassroom = Classroom::where('name', $altName1)
                    ->orWhere('name', $altName2)
                    ->orWhere('name', str_replace('-', ' ', $rawName))
                    ->orWhere('name', str_replace(' ', '-', $rawName))
                    ->orWhere('name', 'like', "%{$rawName}%")
                    ->first();
            }
        }

        $kelas = $currentClassroom?->name ?? ($teacher->homeroom_class ?: '-');
        $students = collect();

        if ($currentClassroom) {
            $students = Student::where('classroom_id', $currentClassroom->id)
                ->where('student_status', 'Active')
                ->orderBy('student_name', 'asc')
                ->get();
        } elseif (! empty($teacher->homeroom_class)) {
            // Fallback: cari siswa yang terhubung dengan rombel bernama serupa
            $students = Student::whereHas('classroom', function ($q) use ($teacher) {
                $q->where('name', 'like', "%{$teacher->homeroom_class}%");
            })
                ->where('student_status', 'Active')
                ->orderBy('student_name', 'asc')
                ->get();
        }

        return [
            'students' => $students,
            'kelas' => $kelas,
        ];
    }
}
