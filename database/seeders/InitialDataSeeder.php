<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teachers')->insert([
            'name' => 'Budi Santoso, S.Pd',
            'gender' => 'Male',
            'ID' => '19870101',
            'subject' => 'Matematika',
            'homeroom_class' => 'Wali Kelas 12',
            'status' => 'Active',
            'username' => 'guru',
            'password' => Hash::make('password'),
            'photo_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $teacherId = DB::getPdo()->lastInsertId();


        DB::table('operators')->insert([
            'name' => 'Main Admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'SMP',
            'photo_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($i = 1; $i <= 30; $i++) {
            DB::table('students')->insert([
                'nis' => '1200' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'student_name' => 'Student ' . $i,
                'gender' => $i % 2 == 0 ? 'F' : 'M',
                'class' => '12',
                'phone_number' => '08123456789' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'student_status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            DB::table('teacher_attendances')->insert([
                'teacher_id' => $teacherId,
                'date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                'arrival_time' => '07:00:00',
                'return_time' => '15:00:00',
                'arrival_photo_url' => null,
                'return_photo_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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
    }
}
