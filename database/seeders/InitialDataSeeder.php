<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SETUP TAHUN AJARAN (Wajib ada biar bisa bikin kelas)
        $academicYearId = DB::table('academic_years')->insertGetId([
            'name' => '2025/2026',
            'semester' => 'Ganjil',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. SETUP MATA PELAJARAN
        $subjectId = DB::table('subjects')->insertGetId([
            'code' => 'MTK-12',
            'name' => 'Matematika Wajib',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. SETUP GURU (PAK BUDI)
        // Kita pakai insertGetId biar ID-nya bisa dipakai untuk bikin kelas
        $teacherId = DB::table('teachers')->insertGetId([
            'name' => 'Budi Santoso, S.Pd',
            'employee_id' => '19870101', // Asumsi kolomnya 'nip' (sesuai migrasi guru)
            'gender' => 'Male',
            'username' => 'guru',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. SETUP KELAS (HUBUNGKAN GURU SEBAGAI WALI KELAS)
        $classroomId = DB::table('classrooms')->insertGetId([
            'name' => 'XII IPA 1',
            'level' => '12',
            'academic_year_id' => $academicYearId, // Relasi ke Tahun Ajaran
            'teacher_id' => $teacherId, // Pak Budi jadi Wali Kelas
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. SETUP JADWAL (LMS REQUIREMENT)
        // Pak Budi mengajar MTK di XII IPA 1 hari Senin
        DB::table('schedules')->insert([
            'classroom_id' => $classroomId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId,
            'day' => 'Senin',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. SETUP ADMIN (OPERATOR)
        DB::table('operators')->insert([
            'name' => 'Main Admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'SMP', // Sesuaikan enum
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. SETUP SISWA (LOOPING)
        for ($i = 1; $i <= 30; $i++) {
            DB::table('students')->insert([
                'nis' => '1200' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'student_name' => 'Student ' . $i,
                'password' => Hash::make('password'), // Wajib ada password
                'gender' => $i % 2 == 0 ? 'F' : 'M',
                'classroom_id' => $classroomId, // Siswa masuk ke kelas XII IPA 1
                'phone_number' => '08123456789' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'student_status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 8. SETUP ABSENSI GURU (DUMMY DATA)
        for ($i = 0; $i < 5; $i++) {
            DB::table('teacher_attendances')->insert([
                'teacher_id' => $teacherId,
                'date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                'arrival_time' => '07:00:00',
                'return_time' => '15:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 9. SETUP ACTIVITY LOG
        // Pastikan tabel activities punya kolom yang sesuai
        // Jika kolomnya 'user_id', kita mungkin perlu sesuaikan karena guru punya ID sendiri
        // Asumsi tabel activities pakai morph (subject_id, subject_type) atau kolom teacher_id
        DB::table('activities')->insert([
            [

                'teacher_id' => $teacherId,

                'title' => 'Attendance Check-In',

                'type' => 'arrival',

                'time' => now()->subHours(4),

                'created_at' => now()->subHours(4),

                'updated_at' => now()->subHours(4),

            ],

            [

                'teacher_id' => $teacherId,

                'title' => 'Input Score/Grade',

                'type' => 'edit',

                'time' => now()->subHours(2),

                'created_at' => now()->subHours(2),

                'updated_at' => now()->subHours(2),

            ],
        ]);

        // 10. SETUP MOCK ASSIGNMENTS
        $assignmentId = DB::table('assignments')->insertGetId([
            'classroom_id' => $classroomId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId,
            'title' => 'Tugas Aljabar Linear',
            'description' => 'Kerjakan LKS halaman 12-15.',
            'due_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 11. SETUP MOCK STUDENT GRADES & ATTENDANCES (For Student 1 - NIS 120001)
        $student1Id = DB::table('students')->where('nis', '120001')->value('id');
        
        DB::table('student_grades')->insert([
            [
                'student_id' => $student1Id,
                'subject_id' => $subjectId,
                'type' => 'UTS',
                'score' => 85.50,
                'description' => 'Nilai UTS Semester Ganjil',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => $student1Id,
                'subject_id' => $subjectId,
                'type' => 'Tugas',
                'score' => 90.00,
                'description' => 'Tugas Harian 1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $attendanceId = DB::table('attendances')->insertGetId([
            'teacher_id' => $teacherId,
            'class' => '12',
            'date' => now()->format('Y-m-d'),
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('student_attendance_details')->insert([
            'attendance_id' => $attendanceId,
            'student_id' => $student1Id,
            'status' => 'present',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
