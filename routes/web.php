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
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\PromotionController;
use App\Http\Controllers\Teacher\SettingController as TeacherSettingsController;
use App\Http\Controllers\Teacher\StudentPresenceController;
use App\Http\Controllers\Teacher\TeachingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    // Profil — sub-menu
    Route::get('/profil', 'profil')->name('public.profil');
    Route::get('/profil/sejarah', 'profilSejarah')->name('public.profil.sejarah');
    Route::get('/profil/visi-misi', 'profilVisiMisi')->name('public.profil.visimisi');
    Route::get('/profil/struktur-organisasi', 'profilStruktur')->name('public.profil.struktur');
    Route::get('/profil/gtk', 'profilGtk')->name('public.profil.gtk');
    Route::get('/profil/sarana-prasarana', 'profilSarana')->name('public.profil.sarana');

    // Berita — sub-menu
    Route::get('/berita', 'berita')->name('public.berita');
    Route::get('/berita/kegiatan-sekolah', 'beritaKegiatan')->name('public.berita.kegiatan');
    Route::get('/berita/galeri', 'galeri')->name('public.galeri');
    Route::get('/berita/info-penting', 'infoNews')->name('public.info');
    Route::get('/berita/{slug}', 'showBerita')->name('public.berita.show');

    // E-Learning
    Route::get('/elearning/e-dokumen', 'eDokumen')->name('public.edokumen');
    Route::get('/elearning/web-guru', 'webGuru')->name('public.webguru');

    // Halaman lain
    Route::get('/perpustakaan', 'perpustakaan')->name('public.perpustakaan');
    Route::get('/kontak', 'kontak')->name('public.kontak');
    Route::post('/kontak', 'storeContact')->name('public.kontak.store');
    Route::get('/jadwal', 'jadwal')->name('public.jadwal');
    Route::get('/kalender', 'kalender')->name('public.kalender');

    // PPDB / SPMB Publik
    Route::get('/ppdb', 'ppdb')->name('public.ppdb');
    Route::get('/ppdb/daftar', 'ppdbForm')->name('public.ppdb.daftar');
    Route::post('/ppdb/daftar', 'storePpdb')->name('public.ppdb.store');
    Route::get('/ppdb/bukti/{no_registrasi}', 'downloadPpdbReceipt')->name('public.ppdb.receipt');
    Route::get('/spmb', 'ppdb')->name('public.spmb'); // alias ke ppdb
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest:operator,teacher,student')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::get('/login/admin-tu', 'showLoginAdminTu')->name('login.admin-tu');
    Route::get('/login/pegawai', 'showLoginPegawai')->name('login.pegawai');
    Route::get('/login/kepala-sekolah', 'showLoginKepsek')->name(
        'login.kepala-sekolah',
    );
    Route::post('/login', 'login')->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    if (auth()->guard('operator')->check()) {
        $user = auth()->guard('operator')->user();

        return in_array($user->role_operator, ['Kepala Sekolah', 'principal'])
            ? redirect()->route('principal.dashboard')
            : redirect()->route('tu.dashboard');
    }
    if (auth()->guard('teacher')->check()) {
        return redirect()->route('teacher.dashboard');
    }
    if (auth()->guard('student')->check()) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('login');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Kiosk Absensi Siswa — Scan Barcode Kartu Siswa
| (Halaman publik — tidak perlu login, dioperasikan oleh guru di terminal)
|--------------------------------------------------------------------------
*/

Route::controller(StudentPresenceController::class)
    ->prefix('presensi')
    ->name('presensi.')
    ->group(function () {
        Route::get('/', 'kiosk')->name('index');
        Route::post('/scan', 'scan')->name('scan');
        Route::get('/scan-list', 'scanList')->name('scan-list');
        Route::post('/close', 'closeSession')->name('close');
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
        Route::get('/absenteeism-recap/pdf', [
            RecapController::class,
            'exportPdf',
        ])->name('rekap.pdf');

        Route::get('student/{student}/card', [AdminTuStudentController::class, 'printCard'])->name('student.card');
        Route::get('student/{student}/card/pdf', [AdminTuStudentController::class, 'exportCardPdf'])->name('student.card.pdf');
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
                Route::get('/card', 'index_card')->name('card');
                Route::put('/card', 'update_card')->name('update.card');
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
                Route::get('/', [PpdbController::class, 'index'])->name('index');
                Route::get('/{ppdb}', [PpdbController::class, 'show'])->name('show');
                Route::get('/{ppdb}/pdf', [PpdbController::class, 'exportPdf'])->name('pdf');
                Route::get('/{ppdb}/document/{field}', [PpdbController::class, 'showDocument'])->name('document');
                Route::post('/{ppdb}/status', [PpdbController::class, 'updateStatus'])->name('updateStatus');
                Route::delete('/{ppdb}', [
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
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [LearningController::class, 'index'])->name('dashboard');
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
