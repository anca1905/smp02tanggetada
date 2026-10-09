<?php

namespace App\Actions\Teacher\Promotion;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;

class GetPromotionDataAction
{
    /**
     * Get students and classrooms data for promotion
     *
     * @return array{
     *     students: \Illuminate\Support\Collection,
     *     currentClassroom: ?Classroom,
     *     kelas: ?string,
     *     targetClassroom: ?Classroom,
     *     nextLevelClassrooms: \Illuminate\Support\Collection,
     *     allClassrooms: \Illuminate\Support\Collection,
     *     isFinalGrade: bool,
     *     error: ?string
     * }
     */
    public function execute(Teacher $teacher): array
    {
        $currentClassroom = $teacher->classroom;
        if (! $currentClassroom && $teacher->homeroom_class) {
            $currentClassroom = Classroom::where('name', $teacher->homeroom_class)->first();
        }

        $kelas = $currentClassroom?->name;
        $students = collect();
        $targetClassroom = null;
        $nextLevelClassrooms = collect();
        $isFinalGrade = false;
        $error = null;

        if ($currentClassroom) {
            $students = Student::where('classroom_id', $currentClassroom->id)
                ->where('student_status', 'Active')
                ->orderBy('student_name', 'asc')
                ->get();

            $currentLevel = (int) ($currentClassroom->level ?? filter_var($currentClassroom->name, FILTER_SANITIZE_NUMBER_INT));

            // Di SMP, tingkat 9 adalah tingkat akhir (Kelulusan)
            if ($currentLevel >= 9) {
                $isFinalGrade = true;
            } else {
                $nextLevel = $currentLevel + 1;

                // Ambil kelas di tingkat berikutnya
                $nextLevelClassrooms = Classroom::where(function ($q) use ($nextLevel) {
                    $q->where('level', (string) $nextLevel)
                        ->orWhere('level', $nextLevel)
                        ->orWhere('name', 'like', "%{$nextLevel}%");
                })->orderBy('name')->get();

                if ($nextLevelClassrooms->isEmpty()) {
                    $error = "Kelas tujuan untuk Tingkat {$nextLevel} belum dibuat di Master Data Kelas. Silakan hubungi Admin TU.";
                } else {
                    // Cari rombel yang cocok (misal 'VIII A' -> 'IX A', atau 'VII B' -> 'VIII B')
                    $currentRombel = '';
                    if (preg_match('/[A-Z]$/i', trim($currentClassroom->name), $matches)) {
                        $currentRombel = strtoupper($matches[0]);
                    }

                    if ($currentRombel !== '') {
                        $targetClassroom = $nextLevelClassrooms->first(function ($c) use ($currentRombel) {
                            return preg_match('/'.preg_quote($currentRombel, '/').'$/i', trim($c->name))
                                || str_contains(strtoupper($c->name), $currentRombel);
                        });
                    }

                    if (! $targetClassroom) {
                        $targetClassroom = $nextLevelClassrooms->first();
                    }
                }
            }
        }

        $allClassrooms = $nextLevelClassrooms;

        return compact(
            'students',
            'currentClassroom',
            'kelas',
            'targetClassroom',
            'nextLevelClassrooms',
            'allClassrooms',
            'isFinalGrade',
            'error'
        );
    }
}
