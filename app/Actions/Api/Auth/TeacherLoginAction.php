<?php

namespace App\Actions\Api\Auth;

use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class TeacherLoginAction
{
    /**
     * Authenticate teacher and create Sanctum token.
     */
    public function execute(string $username, string $password): array
    {
        $teacher = Teacher::where('username', $username)->first();

        if (! $teacher || ! Hash::check($password, $teacher->password)) {
            return [
                'success' => false,
                'message' => 'Username atau Password salah',
                'status_code' => 401,
            ];
        }

        // Generate Sanctum token
        $token = $teacher->createToken('teacher_mobile_token')->plainTextToken;

        // Load classroom relation if homeroom teacher
        $classroom = $teacher->classroom;

        return [
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'username' => $teacher->username,
                    'employee_id' => $teacher->employee_id,
                    'subject' => $teacher->subject,
                    'homeroom_class' => $teacher->homeroom_class,
                    'photo_url' => $teacher->photo_url,
                    'classroom' => $classroom ? [
                        'id' => $classroom->id,
                        'name' => $classroom->name,
                        'level' => $classroom->level,
                    ] : null,
                ],
                'token' => $token,
            ],
            'status_code' => 200,
        ];
    }
}
