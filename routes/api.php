<?php

use App\Http\Controllers\Teacher\PresenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('student/login', [App\Http\Controllers\Api\StudentAuthController::class, 'login']);
Route::post('student/parent-login', [App\Http\Controllers\Api\StudentAuthController::class, 'parentLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Student API Routes
    Route::post('student/logout', [App\Http\Controllers\Api\StudentAuthController::class, 'logout']);
    Route::get('student/dashboard', [App\Http\Controllers\Api\StudentApiController::class, 'dashboard']);
    Route::get('student/schedules', [App\Http\Controllers\Api\StudentApiController::class, 'schedules']);
    Route::get('student/assignments', [App\Http\Controllers\Api\StudentApiController::class, 'assignments']);
    Route::get('student/attendances', [App\Http\Controllers\Api\StudentApiController::class, 'attendances']);
    Route::get('student/grades', [App\Http\Controllers\Api\StudentApiController::class, 'grades']);
    Route::get('student/materials', [App\Http\Controllers\Api\StudentApiController::class, 'materials']);
    Route::post('student/assignments/{id}/submit', [App\Http\Controllers\Api\StudentApiController::class, 'submitAssignment']);

    // QR Attendance Check-in
    Route::post('student/attendance/checkin', [App\Http\Controllers\Api\AttendanceCheckinController::class, 'checkin']);
    Route::get('student/attendance/session', [App\Http\Controllers\Api\AttendanceCheckinController::class, 'sessionInfo']);
});

// Public - untuk polling web guru (tidak perlu auth siswa)
Route::get('student/attendance/checkin-list', [App\Http\Controllers\Api\AttendanceCheckinController::class, 'checkinList']);

Route::get('teacher', [PresenceController::class, 'search']);