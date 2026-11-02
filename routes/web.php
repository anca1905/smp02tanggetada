<?php

use App\Http\Controllers\AdminTu\AcademicController;
use App\Http\Controllers\AdminTu\AchievementController;
// Auth & Public Controllers
use App\Http\Controllers\AdminTu\BillingController;
use App\Http\Controllers\AdminTu\BorrowingController;
use App\Http\Controllers\AdminTu\ClassroomController;
use App\Http\Controllers\AdminTu\CounselingController;
use App\Http\Controllers\AdminTu\DashboardController as AdminTuDashboardController;
use App\Http\Controllers\AdminTu\EventController;
use App\Http\Controllers\AdminTu\ExtracurricularController;
use App\Http\Controllers\AdminTu\FacilityController;
use App\Http\Controllers\AdminTu\GraduationManagementController;
use App\Http\Controllers\AdminTu\GuestBookController;
use App\Http\Controllers\AdminTu\InboxController;
use App\Http\Controllers\AdminTu\LibraryController;
use App\Http\Controllers\AdminTu\PostController;
use App\Http\Controllers\AdminTu\PpdbController;
use App\Http\Controllers\AdminTu\RecapController;
use App\Http\Controllers\AdminTu\RoomController;
use App\Http\Controllers\AdminTu\ScheduleController;
use App\Http\Controllers\AdminTu\SettingsController as AdminTuSettingsController;
use App\Http\Controllers\AdminTu\StudentController as AdminTuStudentController;
use App\Http\Controllers\AdminTu\SubjectController;
use App\Http\Controllers\AdminTu\TeacherAttendanceController;
use App\Http\Controllers\AdminTu\TeacherController as AdminTuTeacherController;
use App\Http\Controllers\Auth\AuthController;
// Teacher Controllers
use App\Http\Controllers\Developer\PortalController as DeveloperPortalController;
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
    Route::get('/profil/sejarah', 'profilSejarah')->name(
        'public.profil.sejarah',
    );
    Route::get('/profil/visi-misi', 'profilVisiMisi')->name(
        'public.profil.visimisi',
    );
    Route::get('/profil/struktur-organisasi', 'profilStruktur')->name(
        'public.profil.struktur',
    );
    Route::get('/profil/gtk', 'profilGtk')->name('public.profil.gtk');
    Route::get('/profil/sarana-prasarana', 'profilSarana')->name(
        'public.profil.sarana',
    );

    // Berita — sub-menu
    Route::get('/berita', 'berita')->name('public.berita');
    Route::get('/berita/kegiatan-sekolah', 'beritaKegiatan')->name(
        'public.berita.kegiatan',
    );
    Route::get('/berita/galeri', 'galeri')->name('public.galeri');
    Route::get('/berita/info-penting', 'infoNews')->name('public.info');
    Route::get('/berita/{slug}', 'showBerita')->name('public.berita.show');

    // E-Learning
    Route::get('/elearning/e-dokumen', 'eDokumen')->name('public.edokumen');
    Route::get('/elearning/web-guru', 'webGuru')->name('public.webguru');

    // Halaman lain
    Route::get('/prestasi', 'prestasi')->name('public.prestasi');
    Route::get('/kelulusan', 'kelulusan')->name('public.kelulusan');
    Route::post('/kelulusan/cek', 'cekKelulusan')->name('public.kelulusan.cek');
    Route::get('/kelulusan/skl/{graduation}/download', 'downloadSkl')->name('public.kelulusan.skl.download');
    Route::get('/kelulusan/verify/{code}', 'verifySkl')->name('public.kelulusan.verify');
    Route::get('/buku-tamu', 'bukuTamu')->name('public.buku-tamu');
    Route::post('/buku-tamu', 'storeBukuTamu')->name('public.buku-tamu.store');
    Route::get('/perpustakaan', 'perpustakaan')->name('public.perpustakaan');
    Route::get('/ekstrakurikuler', 'ekstrakurikuler')->name('public.ekstrakurikuler');
    Route::post('/ekstrakurikuler/daftar', 'storeEkstrakurikuler')->name('public.ekstrakurikuler.store');
    Route::get('/kontak', 'kontak')->name('public.kontak');
    Route::post('/kontak', 'storeContact')->name('public.kontak.store');
    Route::get('/jadwal', 'jadwal')->name('public.jadwal');
    Route::get('/kalender', 'kalender')->name('public.kalender');

    // PPDB / SPMB Publik
    Route::get('/ppdb', 'ppdb')->name('public.ppdb');
    Route::get('/ppdb/daftar', 'ppdbForm')->name('public.ppdb.daftar');
    Route::post('/ppdb/daftar', 'storePpdb')->name('public.ppdb.store');
    Route::get('/ppdb/bukti/{no_registrasi}', 'downloadPpdbReceipt')->name(
        'public.ppdb.receipt',
    );
    Route::get('/spmb', 'ppdb')->name('public.spmb'); // alias ke ppdb
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest:operator,teacher,student')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::get('/login/admin-tu', 'showLoginAdminTu')->name(
            'login.admin-tu',
        );
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
        Route::patch('/absenteeism-recap/{studentAttendance}', [
            RecapController::class,
            'update',
        ])->name('rekap.update');

        Route::get('student/{student}/card', [
            AdminTuStudentController::class,
            'printCard',
        ])->name('student.card');
        Route::get('student/{student}/card/pdf', [
            AdminTuStudentController::class,
            'exportCardPdf',
        ])->name('student.card.pdf');
        Route::resource('teacher', AdminTuTeacherController::class)->except([
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
        Route::post('posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');
        Route::resource('posts', PostController::class);
        Route::resource('events', EventController::class);
        Route::resource('edokumen', \App\Http\Controllers\AdminTu\EDokumenController::class)->except(['show', 'edit', 'update']);
        Route::resource('inbox', InboxController::class)->only([
            'index',
            'destroy',
        ]);

        Route::controller(BillingController::class)
            ->middleware('feature.enabled:feature_spp_billing')
            ->prefix('billing')
            ->name('billing.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/generate', 'generate')->name('generate');
                Route::post('/{bill}/pay', 'markAsPaid')->name('pay');
                Route::delete('/{bill}', 'destroy')->name('destroy');
            });

        Route::controller(TeacherAttendanceController::class)
            ->middleware('feature.enabled:feature_absensi_guru')
            ->prefix('teacher-attendance')
            ->name('teacher-attendance.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
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
                Route::get('/', [PpdbController::class, 'index'])->name(
                    'index',
                );
                Route::get('/{ppdb}', [PpdbController::class, 'show'])->name(
                    'show',
                );
                Route::get('/{ppdb}/pdf', [
                    PpdbController::class,
                    'exportPdf',
                ])->name('pdf');
                Route::get('/{ppdb}/document/{field}', [
                    PpdbController::class,
                    'showDocument',
                ])->name('document');
                Route::post('/{ppdb}/status', [
                    PpdbController::class,
                    'updateStatus',
                ])->name('updateStatus');
                Route::delete('/{ppdb}', [
                    PpdbController::class,
                    'destroy',
                ])->name('destroy');
                Route::post('/toggle-status', [
                    PpdbController::class,
                    'toggleStatus',
                ])->name('toggleStatus');
            });

        // Bimbingan Konseling (BK) & Kedisiplinan Siswa
        Route::controller(CounselingController::class)
            ->prefix('counseling')
            ->name('counseling.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/record', 'storeRecord')->name('record.store');
                Route::put('/record/{record}', 'updateRecord')->name('record.update');
                Route::delete('/record/{record}', 'destroyRecord')->name('record.destroy');
                Route::post('/category', 'storeCategory')->name('category.store');
                Route::delete('/category/{category}', 'destroyCategory')->name('category.destroy');
            });

        // Galeri Prestasi Siswa
        Route::post('achievements/{achievement}/toggle-featured', [
            AchievementController::class,
            'toggleFeatured',
        ])->name('achievements.toggle-featured');
        Route::resource('achievements', AchievementController::class)->except(['create', 'edit', 'show']);

        // Kelulusan & SKL Siswa (Admin TU)
        Route::post('graduations/import-class-nine', [
            GraduationManagementController::class,
            'importClassNine',
        ])->name('graduations.import');
        Route::post('graduations/settings', [
            GraduationManagementController::class,
            'updateSettings',
        ])->name('graduations.settings');
        Route::resource('graduations', GraduationManagementController::class)->except(['create', 'edit', 'show']);

        // Buku Tamu Digital (Admin TU)
        Route::get('guest-books/export-pdf', [
            GuestBookController::class,
            'exportPdf',
        ])->name('guest-books.export-pdf');
        Route::patch('guest-books/{guestBook}/status', [
            GuestBookController::class,
            'updateStatus',
        ])->name('guest-books.status');
        Route::resource('guest-books', GuestBookController::class)->only(['index', 'destroy']);

        // Sirkulasi Buku Paket BOS & Perpustakaan (Admin TU)
        Route::controller(LibraryController::class)
            ->prefix('library')
            ->name('library.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/books', 'storeBook')->name('books.store');
                Route::put('/books/{book}', 'updateBook')->name('books.update');
                Route::delete('/books/{book}', 'destroyBook')->name('books.destroy');
                Route::post('/loans', 'storeLoan')->name('loans.store');
                Route::post('/bulk-loan', 'bulkLoan')->name('loans.bulk');
                Route::patch('/loans/{loan}/return', 'returnLoan')->name('loans.return');
                Route::delete('/loans/{loan}', 'destroyLoan')->name('loans.destroy');
            });

        // Ekstrakurikuler & OSIS (Admin TU)
        Route::controller(ExtracurricularController::class)
            ->prefix('extracurriculars')
            ->name('extracurriculars.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{extracurricular}', 'update')->name('update');
                Route::delete('/{extracurricular}', 'destroy')->name('destroy');
                Route::post('/registrations', 'storeRegistration')->name('registrations.store');
                Route::patch('/registrations/{registration}/status', 'updateRegistrationStatus')->name('registrations.status');
                Route::delete('/registrations/{registration}', 'destroyRegistration')->name('registrations.destroy');
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
        Route::get('/dashboard', [LearningController::class, 'index'])->name(
            'dashboard',
        );
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

/*
|--------------------------------------------------------------------------
| Skillance Developer & PRO Module Control Gateway
|--------------------------------------------------------------------------
| Jalur rahasia pengembang sistem untuk aktivasi fitur PRO & audit keamanan.
| Tidak ditampilkan di menu publik / web manapun.
*/
Route::prefix('skillance-control')->name('dev.portal.')->group(function () {
    Route::get('/', [DeveloperPortalController::class, 'showLogin'])->name('login');
    Route::post('/auth', [DeveloperPortalController::class, 'authenticate'])
        ->middleware('throttle:5,1')
        ->name('auth');
    Route::get('/dashboard', [DeveloperPortalController::class, 'index'])->name('index');
    Route::post('/features', [DeveloperPortalController::class, 'update'])->name('update');
    Route::post('/logout', [DeveloperPortalController::class, 'logout'])->name('logout');
});
