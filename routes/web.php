<?php

use App\Http\Controllers\AdminTu\AcademicController;
// Auth & Public Controllers
use App\Http\Controllers\AdminTu\BillingController;
use App\Http\Controllers\AdminTu\BorrowingController;
use App\Http\Controllers\AdminTu\ClassroomController;
use App\Http\Controllers\AdminTu\DashboardController as AdminTuDashboardController;
// Admin TU Controllers
use App\Http\Controllers\AdminTu\EventController;
use App\Http\Controllers\AdminTu\FacilityController;
use App\Http\Controllers\AdminTu\InboxController;
use App\Http\Controllers\AdminTu\PostController;
use App\Http\Controllers\AdminTu\PpdbController;
use App\Http\Controllers\AdminTu\RecapController;
use App\Http\Controllers\AdminTu\RoomController;
use App\Http\Controllers\AdminTu\ScheduleController;
use App\Http\Controllers\AdminTu\SettingsController as AdminTuSettingsController;
use App\Http\Controllers\AdminTu\StudentController as AdminTuStudentController;
use App\Http\Controllers\AdminTu\SubjectController;
use App\Http\Controllers\AdminTu\TeacherController as AdminTuTeacherController;
use App\Http\Controllers\Auth\AuthController;
// Teacher Controllers
use App\Http\Controllers\GraduationController;
use App\Http\Controllers\Principal\DashboardController as PrincipalDashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Student\LearningController;
use App\Http\Controllers\StudentPresenceController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\PresenceController as TeacherPresenceController;
use App\Http\Controllers\Teacher\PromotionController;
use App\Http\Controllers\Teacher\SettingController as TeacherSettingsController;
use App\Http\Controllers\Teacher\TeachingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/profil', 'profil')->name('public.profil');
    Route::get('/berita', 'berita')->name('public.berita');
    Route::get('/berita/{slug}', 'showBerita')->name('public.berita.show');
    Route::get('/kontak', 'kontak')->name('public.kontak');
    Route::post('/kontak', 'storeContact')->name('public.kontak.store');
    Route::get('/jadwal', 'jadwal')->name('public.jadwal');
    Route::get('/kalender', 'kalender')->name('public.kalender');
    // PPDB Publik
    Route::get('/ppdb', 'ppdb')->name('public.ppdb');
    Route::get('/ppdb/daftar', 'ppdbForm')->name('public.ppdb.daftar');
    Route::post('/ppdb/daftar', 'storePpdb')->name('public.ppdb.store');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::get('/login/admin-tu', 'showLoginAdminTu')->name('login.admin-tu');
    Route::get('/login/pegawai', 'showLoginPegawai')->name('login.pegawai');
    Route::get('/login/kepala-sekolah', 'showLoginKepsek')->name(
        'login.kepala-sekolah',
    );
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Kiosk / Presence Machine Routes
|--------------------------------------------------------------------------
*/

Route::controller(TeacherPresenceController::class)
    ->prefix('presensi')
    ->name('presensi.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/search', 'search')->name('search');
    });

Route::get('/api/siswa/{kelas}', [
    StudentPresenceController::class,
    'getSiswa',
])->name('api.siswa.get');

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [
            TeacherDashboardController::class,
            'index',
        ])->name('dashboard');
        Route::get('/attendance-history', [
            TeacherDashboardController::class,
            'riwayat',
        ])->name('history');

        Route::controller(StudentPresenceController::class)->group(function () {
            Route::get('/student-attendance', 'index')->name(
                'student-attendance',
            );
            Route::post('/student-attendance/store', 'store')->name(
                'student-attendance-store',
            );
            Route::post('/student-attendance/generate-qr', 'generateQr')->name(
                'student-attendance-generate-qr',
            );
        });

        Route::controller(GraduationController::class)->group(function () {
            Route::get('/graduation', 'index')->name('graduation');
            Route::post('/graduation', 'store')->name('graduation.store');
        });

        Route::controller(PromotionController::class)->group(function () {
            Route::get('/promotion', 'index')->name('promotion');
            Route::post('/promotion', 'store')->name('promotion.store');
        });

        Route::controller(TeacherSettingsController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings');
            Route::post('/settings', 'update')->name('settings.update');
        });

        Route::get('/my-classes', [TeachingController::class, 'index'])->name(
            'lms.index',
        );
        Route::get('/course/{schedule_id}', [
            TeachingController::class,
            'show',
        ])->name('lms.show');
        Route::post('/lms/material', [
            TeachingController::class,
            'storeMaterial',
        ])->name('lms.material.store');
        Route::delete('/lms/material/{id}', [
            TeachingController::class,
            'destroyMaterial',
        ])->name('lms.material.destroy');

        Route::post('/lms/assignment', [
            TeachingController::class,
            'storeAssignment',
        ])->name('lms.assignment.store');
        Route::delete('/lms/assignment/{id}', [
            TeachingController::class,
            'destroyAssignment',
        ])->name('lms.assignment.destroy');
        Route::get('/lms/assignment/{assignment_id}/submissions', [
            TeachingController::class,
            'viewSubmissions',
        ])->name('lms.assignment.submissions');
        Route::post('/lms/submission/{submission_id}/grade', [
            TeachingController::class,
            'gradeSubmission',
        ])->name('lms.assignment.grade');
    });

/*
|--------------------------------------------------------------------------
| Admin TU Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:operator'])
    ->prefix('tu')
    ->name('tu.')
    ->group(function () {
        Route::get('/dashboard', [
            AdminTuDashboardController::class,
            'index',
        ])->name('dashboard');
        Route::get('/absenteeism-recap', [
            RecapController::class,
            'index',
        ])->name('rekap');

        Route::resource('teacher', AdminTuTeacherController::class)->except([
            'create',
            'edit',
            'show',
        ]);
        Route::resource('student', AdminTuStudentController::class)->except([
            'create',
            'edit',
            'show',
        ]);
        Route::resource('room', RoomController::class)->except([
            'create',
            'edit',
            'show',
        ]);
        Route::resource('borrowing', BorrowingController::class)->except([
            'create',
            'edit',
            'show',
        ]);
        Route::resource('facility', FacilityController::class)->except([
            'create',
            'edit',
            'show',
        ]);
        Route::resource('posts', PostController::class);
        Route::resource('events', EventController::class);
        Route::resource('inbox', InboxController::class)->only([
            'index',
            'destroy',
        ]);

        Route::controller(BillingController::class)
            ->prefix('billing')
            ->name('billing.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/generate', 'generate')->name('generate');
                Route::post('/{bill}/pay', 'markAsPaid')->name('pay');
                Route::delete('/{bill}', 'destroy')->name('destroy');
            });

        Route::controller(AdminTuSettingsController::class)
            ->prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/{id}', 'update')->name('update');
                Route::get('/landing', 'index_landing')->name('website');
                Route::put('/update', 'update_landing')->name('update.website');
            });
        Route::post('academic-years/{academic_year}/set-active', [
            AcademicController::class,
            'setActive',
        ])->name('academic-years.set-active');
        Route::resource('academic-years', AcademicController::class);
        Route::resource('classrooms', ClassroomController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('schedules', ScheduleController::class);

        // PPDB — Manajemen Pendaftar
        Route::prefix('ppdb')
            ->name('ppdb.')
            ->group(function () {
                Route::get('/', [PpdbController::class, 'index'])->name(
                    'index',
                );
                Route::post('/{id}/status', [
                    PpdbController::class,
                    'updateStatus',
                ])->name('updateStatus');
                Route::delete('/{id}', [
                    PpdbController::class,
                    'destroy',
                ])->name('destroy');
                Route::post('/toggle-status', [
                    PpdbController::class,
                    'toggleStatus',
                ])->name('toggleStatus');
            });
    });

/*
|--------------------------------------------------------------------------
| Principal (Kepala Sekolah) Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:operator'])
    ->prefix('principal')
    ->name('principal.')
    ->group(function () {
        Route::get('/dashboard', [
            PrincipalDashboardController::class,
            'index',
        ])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Admin TU Routes (continued)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/lms', [LearningController::class, 'index'])->name(
            'lms.index',
        );
        Route::get('/lms/{schedule_id}', [
            LearningController::class,
            'show',
        ])->name('lms.show');
        Route::post('/lms/assignment/submit', [
            LearningController::class,
            'submitAssignment',
        ])->name('lms.assignment.submit');
    });

/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard-admin', function () {
    return 'Halo Admin TU! (Route Test)';
})->middleware('auth');
