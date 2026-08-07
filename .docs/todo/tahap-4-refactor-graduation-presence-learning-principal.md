# Tahap 4 — Refactor Graduation, StudentPresence, Learning, & Principal

> **File Sumber:**
> - `app/Http/Controllers/GraduationController.php`
> - `app/Http/Controllers/StudentPresenceController.php`
> - `app/Http/Controllers/Student/LearningController.php`
> - `app/Http/Controllers/Principal/DashboardController.php`
> **Status:** Belum direfactor

## Ringkasan

Tahap ini mencakup empat controller sisa yang belum tersentuh oleh refactor Action-Pattern. Masing-masing memiliki konteks berbeda (guru, siswa, kepala sekolah) namun pola refactor yang diterapkan tetap identik dengan modul AdminTu dan Teacher yang sudah dikerjakan.

---

## 4A. Refactor `GraduationController`

> **Guard:** `auth:teacher`
> **Prefix URL:** `/teacher/graduation`

### Daftar Method

| # | Method    | Deskripsi                                                       | Aksi Refactor             |
|---|-----------|-----------------------------------------------------------------|---------------------------|
| 1 | `index()` | Ambil siswa aktif di kelas homeroom guru, render view           | **Action**                |
| 2 | `store()` | Loop array status per NIS, update `student_status` (Graduated)  | **Form Request + Action** |

### File yang Akan Dibuat

| File                                                              | Tipe         | Deskripsi                                                                                                             |
|-------------------------------------------------------------------|--------------|-----------------------------------------------------------------------------------------------------------------------|
| `app/Http/Requests/Teacher/ProcessGraduationRequest.php`          | FormRequest  | Validasi `status` (required\|array). Setiap elemen berisi `Lulus` atau `Tidak Lulus`.                                 |
| `app/Actions/Teacher/Graduation/GetGraduationDataAction.php`      | Action       | Mengambil siswa aktif dari kelas homeroom guru. Parse `homeroom_class` untuk mendapatkan level kelas. Return `students` + `kelas`. |
| `app/Actions/Teacher/Graduation/ProcessGraduationAction.php`      | Action       | Loop array `status` dari request. Jika `Lulus` → update `student_status` menjadi `Graduated`. Jika tidak → tetap `Active`. |

### Tests

| File                                                                  | Tipe    | Skenario                                                                         |
|-----------------------------------------------------------------------|---------|----------------------------------------------------------------------------------|
| `tests/Unit/Teacher/Actions/Graduation/ProcessGraduationActionTest.php`| Unit   | - Siswa dengan status `Lulus` → `student_status` berubah menjadi `Graduated`    |
|                                                                       |         | - Siswa dengan status `Tidak Lulus` → `student_status` tetap `Active`           |
| `tests/Feature/Teacher/Controllers/GraduationControllerTest.php`      | Feature | - Teacher dapat akses halaman graduation → 200                                  |
|                                                                       |         | - POST graduation berhasil → redirect + session `success`                       |

---

## 4B. Refactor `StudentPresenceController`

> **Guard:** `auth:teacher`
> **Prefix URL:** `/teacher/student-attendance`

### Daftar Method

| # | Method          | Deskripsi                                                          | Aksi Refactor             |
|---|-----------------|--------------------------------------------------------------------|---------------------------|
| 1 | `index()`       | Ambil daftar kelas, siswa per kelas, dan data attendance header    | **Action**                |
| 2 | `store()`       | Simpan/update header attendance + detail per siswa                 | **Form Request + Action** |
| 3 | `generateQr()`  | Buat/update sesi attendance + generate QR token (expire 5 menit)   | **Form Request + Action** |
| 4 | `getSiswa()`    | AJAX helper: ambil daftar siswa per kelas                          | **Action**                |

### File yang Akan Dibuat

| File                                                                   | Tipe         | Deskripsi                                                                                                           |
|------------------------------------------------------------------------|--------------|---------------------------------------------------------------------------------------------------------------------|
| `app/Http/Requests/Teacher/StoreStudentAttendanceRequest.php`          | FormRequest  | Validasi `class` (required), `date` (required\|date), `attendance` (required\|array)                                |
| `app/Http/Requests/Teacher/GenerateQrRequest.php`                      | FormRequest  | Validasi `class` (required), `date` (required\|date)                                                                |
| `app/Actions/Teacher/StudentPresence/GetStudentPresenceDataAction.php` | Action       | Mengambil daftar kelas, mengambil siswa + status kehadiran yang sudah tersimpan berdasarkan kelas dan tanggal terpilih. |
| `app/Actions/Teacher/StudentPresence/StoreStudentAttendanceAction.php` | Action       | `updateOrCreate` header `Attendance`, lalu loop tiap siswa untuk `updateOrCreate` detail `StudentAttendance`.        |
| `app/Actions/Teacher/StudentPresence/GenerateQrSessionAction.php`      | Action       | `updateOrCreate` header `Attendance`, generate random token 32 char, set `qr_expires_at` ke +5 menit.               |
| `app/Actions/Teacher/StudentPresence/GetStudentsByClassAction.php`     | Action       | Query siswa berdasarkan `classroom_id`, return JSON-ready collection.                                                |

### Tests

| File                                                                           | Tipe    | Skenario                                                                               |
|--------------------------------------------------------------------------------|---------|----------------------------------------------------------------------------------------|
| `tests/Unit/Teacher/Actions/StudentPresence/StoreStudentAttendanceActionTest.php`| Unit  | - Header attendance ter-create + detail per siswa tersimpan                             |
|                                                                                |         | - Update data attendance yang sudah ada (tidak duplikat)                               |
| `tests/Unit/Teacher/Actions/StudentPresence/GenerateQrSessionActionTest.php`   | Unit    | - Token QR 32 karakter ter-generate                                                    |
|                                                                                |         | - `qr_expires_at` diset ke 5 menit dari sekarang                                      |
| `tests/Feature/Teacher/Controllers/StudentPresenceControllerTest.php`          | Feature | - Teacher dapat akses halaman presensi siswa → 200                                     |
|                                                                                |         | - POST simpan presensi → redirect + session `success`                                  |
|                                                                                |         | - POST generate QR → JSON response `qr_token`                                         |

---

## 4C. Refactor `LearningController` (Student Web LMS)

> **Guard:** `auth:student`
> **Prefix URL:** `/student/learning`

### Daftar Method

| # | Method               | Deskripsi                                                     | Aksi Refactor             |
|---|----------------------|---------------------------------------------------------------|---------------------------|
| 1 | `index()`            | Ambil jadwal kelas siswa, group by mapel                      | **Action**                |
| 2 | `show($schedule_id)` | Ambil materi + tugas + submission siswa per mapel             | **Action**                |
| 3 | `submitAssignment()` | Upload file tugas siswa (updateOrCreate submission)           | **Form Request + Action** |

### File yang Akan Dibuat

| File                                                              | Tipe         | Deskripsi                                                                                                         |
|-------------------------------------------------------------------|--------------|-------------------------------------------------------------------------------------------------------------------|
| `app/Http/Requests/Student/SubmitAssignmentRequest.php`           | FormRequest  | Validasi `assignment_id` (required), `file` (required\|file\|max:10240)                                           |
| `app/Actions/Student/Learning/GetStudentCoursesAction.php`        | Action       | Mengambil schedules berdasarkan `classroom_id` siswa, group by subject name. Return `myCourses`.                  |
| `app/Actions/Student/Learning/GetCourseDetailAction.php`          | Action       | Mengambil schedule + materi + tugas beserta submission milik siswa. Validasi bahwa siswa milik kelas yang benar (abort 403 jika tidak). |
| `app/Actions/Student/Learning/SubmitStudentAssignmentAction.php`  | Action       | Upload file ke `submissions` disk `public`, `updateOrCreate` record `AssignmentSubmission`.                       |

### Tests

| File                                                                       | Tipe    | Skenario                                                                             |
|----------------------------------------------------------------------------|---------|--------------------------------------------------------------------------------------|
| `tests/Unit/Student/Actions/Learning/GetStudentCoursesActionTest.php`      | Unit    | - Siswa tanpa `classroom_id` → return empty                                         |
|                                                                            |         | - Siswa dengan kelas → return courses grouped by subject                             |
| `tests/Unit/Student/Actions/Learning/SubmitStudentAssignmentActionTest.php` | Unit   | - File tersimpan ke storage                                                          |
|                                                                            |         | - Record `AssignmentSubmission` ter-create/update                                    |
| `tests/Feature/Student/Controllers/LearningControllerTest.php`             | Feature | - Student dapat akses halaman LMS → 200                                              |
|                                                                            |         | - Student tanpa kelas → render view `student.lms.no-class`                           |
|                                                                            |         | - Student tidak bisa akses course kelas lain → 403                                   |
|                                                                            |         | - Submit tugas berhasil → redirect + session `success`                               |

---

## 4D. Refactor `Principal/DashboardController`

> **Guard:** `auth:operator` (role: `Kepala Sekolah`)
> **Prefix URL:** `/principal/dashboard`

### Daftar Method

| # | Method    | Deskripsi                                                                    | Aksi Refactor |
|---|-----------|------------------------------------------------------------------------------|---------------|
| 1 | `index()` | Menghitung KPI (guru, siswa, kelas, mapel, fasilitas, kehadiran, keuangan), chart 6 bulan, top teachers, pengumuman, events, distribusi siswa per kelas | **Action** |

### File yang Akan Dibuat

| File                                                              | Tipe    | Deskripsi                                                                                                                                                         |
|-------------------------------------------------------------------|---------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `app/Actions/Principal/GetPrincipalDashboardAction.php`           | Action  | Mengompilasi seluruh statistik dashboard kepala sekolah: KPI cards, attendance chart 6 bulan, top 5 guru teraktif, pengumuman terbaru, upcoming events, distribusi siswa per kelas, summary keuangan. Return array `compact()`. |

### Tests

| File                                                                       | Tipe    | Skenario                                                                              |
|----------------------------------------------------------------------------|---------|----------------------------------------------------------------------------------------|
| `tests/Unit/Principal/Actions/GetPrincipalDashboardActionTest.php`         | Unit    | - Return array dengan semua key KPI yang dibutuhkan                                   |
|                                                                            |         | - `attendanceChart` berisi tepat 6 elemen (6 bulan terakhir)                          |
|                                                                            |         | - `topTeachers` berisi maksimal 5 item                                                |
| `tests/Feature/Principal/Controllers/DashboardControllerTest.php`          | Feature | - Guest → redirect ke `/login`                                                        |
|                                                                            |         | - Operator biasa (Admin TU) → redirect/forbidden (bukan kepala sekolah)               |
|                                                                            |         | - Operator Kepala Sekolah → 200 + view `principal.dashboard`                          |

---

## Catatan Konsistensi Global

1. **PHPDoc Comments**: Setiap Action class wajib memiliki PHPDoc comment di atas method `execute()` yang menjelaskan:
   - Deskripsi singkat fungsi action (dalam bahasa Indonesia)
   - `@param` untuk setiap parameter
   - `@return` dengan tipe data yang jelas

2. **Namespace Convention**:
   - Actions: `App\Actions\{Module}\{SubModule}\{ActionName}Action`
   - Form Requests: `App\Http\Requests\{Module}\{RequestName}Request`
   - Tests Unit: `Tests\Unit\{Module}\Actions\{SubModule}\{ActionName}Test`
   - Tests Feature: `Tests\Feature\{Module}\Controllers\{ControllerName}Test`

3. **Controller Pattern**: Setelah refactor, setiap method controller hanya berisi:
   - Dependency injection (Action/FormRequest di parameter method)
   - Memanggil `$action->execute(...)` dengan data dari request
   - Return `view()`, `redirect()`, atau `response()->json()`

4. **Guard Consistency**:
   - `auth:operator` → Admin TU dan Kepala Sekolah
   - `auth:teacher` → Guru
   - `auth:student` → Siswa (web portal)
   - `sanctum` → Siswa (mobile API)

5. **Storage**: Semua file upload menggunakan `Storage::disk('public')` dan `UploadedFile::fake()` di unit test.
