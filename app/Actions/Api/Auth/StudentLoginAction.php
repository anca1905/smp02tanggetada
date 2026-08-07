<?php

namespace App\Actions\Api\Auth;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentLoginAction
{
    /**
     * Authenticate student and create token.
     *
     * @throws ValidationException
     */
    public function execute(string $nis, string $password): array
    {
        $student = Student::where('nis', $nis)->first();

        if (! $student || ! Hash::check($password, $student->password)) {
            return [
                'success' => false,
                'message' => 'NIS atau Password salah',
                'status_code' => 401,
            ];
        }

        // Create token
        $token = $student->createToken('student_mobile_token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'student' => $student,
                'token' => $token,
            ],
            'status_code' => 200,
        ];
    }
}
