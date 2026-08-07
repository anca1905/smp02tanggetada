# Tahap 3 — Refactor Api Controllers (Mobile Flutter / Sanctum)

> **File Sumber:**
> - `app/Http/Controllers/Api/StudentAuthController.php`
> - `app/Http/Controllers/Api/StudentApiController.php`
> - `app/Http/Controllers/Api/BillingApiController.php`
> - `app/Http/Controllers/Api/AttendanceCheckinController.php`
> **Status:** Belum direfactor

## Ringkasan

Keempat controller ini menyediakan **endpoint JSON** untuk aplikasi mobile Flutter. Mereka berinteraksi melalui **Laravel Sanctum** (bearer token) dan mengembalikan response `application/json`. Saat ini logika autentikasi, query Eloquent, penghitungan statistik, validasi, dan manipulasi file masih bercampur langsung di dalam controller.

---

## 3A. Refactor `StudentAuthController`

### Daftar Method

| # | Method          | Deskripsi                                              | Aksi Refactor             |
|---|-----------------|--------------------------------------------------------|---------------------------|
| 1 | `login()`       | Validasi NIS+password, cek hash, generate Sanctum token | **Form Request + Action** |
| 2 | `parentLogin()` | Validasi NIS+password orang tua, generate token        | **Form Request + Action** |
| 3 | `logout()`      | Hapus current access token                             | **Action**                |

### File yang Akan Dibuat

| File                                                              | Tipe         | Deskripsi                                                                                                      |
|-------------------------------------------------------------------|--------------|----------------------------------------------------------------------------------------------------------------|
| `app/Http/Requests/Api/StudentLoginRequest.php`                   | FormRequest  | Validasi `nis` (required\|string), `password` (required\|string)                                               |
| `app/Actions/Api/Auth/StudentLoginAction.php`                     | Action       | Mencari student by NIS, `Hash::check()` password, `createToken('student_mobile_token')`. Return array `[student, token]` atau throw exception/return error array. |
| `app/Actions/Api/Auth/ParentLoginAction.php`                      | Action       | Sama seperti di atas tapi menggunakan field `parent_password`. Token name: `parent_mobile_token`. Return termasuk flag `is_parent => true`. |
| `app/Actions/Api/Auth/ApiLogoutAction.php`                        | Action       | `$user->currentAccessToken()->delete()`. Menerima user dari `$request->user()`.                                |

### Tests

| File                                                              | Tipe    | Skenario                                                                                 |
|-------------------------------------------------------------------|---------|------------------------------------------------------------------------------------------|
| `tests/Unit/Api/Actions/Auth/StudentLoginActionTest.php`          | Unit    | - NIS valid + password benar → return student + token string                             |
|                                                                   |         | - NIS tidak ditemukan → return error                                                     |
|                                                                   |         | - NIS valid + password salah → return error                                              |
| `tests/Unit/Api/Actions/Auth/ParentLoginActionTest.php`           | Unit    | - Parent password benar → return student + token + `is_parent`                           |
|                                                                   |         | - Student tanpa `parent_password` → return error                                         |
| `tests/Feature/Api/Controllers/StudentAuthControllerTest.php`     | Feature | - `POST /api/student/login` valid → 200 + JSON `success:true, data.token`               |
|                                                                   |         | - `POST /api/student/login` invalid → 401 + JSON `success:false`                        |
|                                                                   |         | - `POST /api/student/parent-login` valid → 200 + JSON `is_parent:true`                  |
|                                                                   |         | - `POST /api/student/logout` (authenticated) → 200 + token dihapus                      |

---

## 3B. Refactor `StudentApiController`

### Daftar Method

| # | Method               | Deskripsi                                                       | Aksi Refactor |
|---|----------------------|-----------------------------------------------------------------|---------------|
| 1 | `dashboard()`        | Ambil jadwal hari ini, tugas, % kehadiran, pengumuman           | **Action**    |
| 2 | `schedules()`        | Ambil semua jadwal kelas siswa, group by hari                   | **Action**    |
| 3 | `assignments()`      | Ambil semua tugas + submission milik siswa                      | **Action**    |
| 4 | `materials()`        | Ambil materi berdasarkan schedule kelas siswa                   | **Action**    |
| 5 | `submitAssignment()` | Upload file tugas siswa                                         | **FormRequest + Action** |
| 6 | `attendances()`      | Riwayat kehadiran siswa                                         | **Action**    |
| 7 | `grades()`           | Ambil nilai + summary (avg, highest, lowest) + distribusi A-D   | **Action**    |
| 8 | `announcements()`    | Ambil 20 post terbaru                                           | **Action**    |

### File yang Akan Dibuat

| File                                                              | Tipe         | Deskripsi                                                                     |
|-------------------------------------------------------------------|--------------|-------------------------------------------------------------------------------|
| `app/Http/Requests/Api/SubmitAssignmentRequest.php`               | FormRequest  | Validasi `file` (required\|file\|max:10240), `student_note` (nullable)        |
| `app/Actions/Api/Student/GetStudentDashboardAction.php`           | Action       | Mengkompilasi jadwal hari ini, tugas mendatang, % kehadiran, pengumuman       |
| `app/Actions/Api/Student/GetStudentSchedulesAction.php`           | Action       | Query schedules group by hari                                                 |
| `app/Actions/Api/Student/GetStudentAssignmentsAction.php`         | Action       | Query assignments + submissions milik student                                 |
| `app/Actions/Api/Student/GetStudentMaterialsAction.php`           | Action       | Query materials via schedule IDs                                              |
| `app/Actions/Api/Student/SubmitAssignmentAction.php`              | Action       | Upload file, `updateOrCreate` submission                                      |
| `app/Actions/Api/Student/GetStudentAttendancesAction.php`         | Action       | Query attendance details milik student                                        |
| `app/Actions/Api/Student/GetStudentGradesAction.php`              | Action       | Query grades + hitung summary + distribusi A/B/C/D                            |
| `app/Actions/Api/Student/GetAnnouncementsAction.php`              | Action       | Ambil 20 post terbaru                                                         |

### Tests

| File                                                              | Tipe    | Skenario                                                                                  |
|-------------------------------------------------------------------|---------|-------------------------------------------------------------------------------------------|
| `tests/Unit/Api/Actions/Student/GetStudentDashboardActionTest.php`| Unit    | - Return array dengan key `today_schedules`, `upcoming_assignments`, `attendance_percentage`, `announcements` |
| `tests/Unit/Api/Actions/Student/GetStudentGradesActionTest.php`   | Unit    | - Summary average dihitung dengan benar                                                   |
|                                                                   |         | - Distribusi A/B/C/D dihitung berdasarkan batas skor yang tepat                           |
| `tests/Feature/Api/Controllers/StudentApiControllerTest.php`      | Feature | - `GET /api/student/dashboard` (authenticated) → 200 + JSON `success:true`               |
|                                                                   |         | - `GET /api/student/dashboard` (unauthenticated) → 401                                   |
|                                                                   |         | - `POST /api/student/assignments/{id}/submit` dengan file → 200 + file tersimpan         |
|                                                                   |         | - `POST /api/student/assignments/{id}/submit` ke assignment kelas lain → 403              |

---

## 3C. Refactor `BillingApiController`

### Daftar Method

| # | Method               | Deskripsi                                               | Aksi Refactor |
|---|----------------------|---------------------------------------------------------|---------------|
| 1 | `getStudentBills()`  | Ambil tagihan siswa, hitung summary, pisahkan active/history | **Action**  |

### File yang Akan Dibuat

| File                                                              | Tipe    | Deskripsi                                                                                           |
|-------------------------------------------------------------------|---------|-----------------------------------------------------------------------------------------------------|
| `app/Actions/Api/Billing/GetStudentBillsAction.php`               | Action  | Menerima `student_id`, query bills, memisahkan `unpaid` vs `paid`, menghitung total + format Rupiah  |

### Tests

| File                                                              | Tipe    | Skenario                                                                                |
|-------------------------------------------------------------------|---------|-----------------------------------------------------------------------------------------|
| `tests/Unit/Api/Actions/Billing/GetStudentBillsActionTest.php`    | Unit    | - Summary `total_terbayar` dan `sisa_tagihan` dihitung dengan benar                    |
|                                                                   |         | - Format Rupiah (`Rp1.000.000`) dihasilkan dengan benar                                |
| `tests/Feature/Api/Controllers/BillingApiControllerTest.php`      | Feature | - Authenticated student → 200 + JSON dengan `active_bills` dan `history_bills`          |

---

## 3D. Refactor `AttendanceCheckinController`

### Daftar Method

| # | Method           | Deskripsi                                                     | Aksi Refactor             |
|---|------------------|---------------------------------------------------------------|---------------------------|
| 1 | `checkin()`      | Validasi QR token, cek expire, cek kelas, catat kehadiran     | **Form Request + Action** |
| 2 | `checkinList()`  | Ambil daftar siswa yang sudah check-in per sesi               | **Action**                |
| 3 | `sessionInfo()`  | Ambil detail sesi dari token (preview sebelum confirm)         | **Action**                |

### File yang Akan Dibuat

| File                                                              | Tipe         | Deskripsi                                                                                                                 |
|-------------------------------------------------------------------|--------------|---------------------------------------------------------------------------------------------------------------------------|
| `app/Http/Requests/Api/QrCheckinRequest.php`                      | FormRequest  | Validasi `qr_token` (required\|string)                                                                                    |
| `app/Actions/Api/Attendance/ProcessQrCheckinAction.php`           | Action       | Validasi token, cek expire, cek kelas siswa, cek duplikat check-in, tentukan status `present`/`late`, simpan detail       |
| `app/Actions/Api/Attendance/GetCheckinListAction.php`             | Action       | Query siswa yang sudah check-in di sesi tertentu (berdasarkan class + date)                                               |
| `app/Actions/Api/Attendance/GetSessionInfoAction.php`             | Action       | Query sesi attendance berdasarkan QR token, return info sesi + status expired                                             |

### Tests

| File                                                              | Tipe    | Skenario                                                                                 |
|-------------------------------------------------------------------|---------|------------------------------------------------------------------------------------------|
| `tests/Unit/Api/Actions/Attendance/ProcessQrCheckinActionTest.php`| Unit    | - QR token valid + siswa belum check-in → status `present` tersimpan                    |
|                                                                   |         | - QR token valid + terlambat > 15 menit → status `late`                                 |
|                                                                   |         | - QR token expired → return error                                                        |
|                                                                   |         | - QR token kelas lain → return error                                                     |
|                                                                   |         | - Siswa sudah check-in → return `already_checked: true`                                  |
| `tests/Feature/Api/Controllers/AttendanceCheckinControllerTest.php`| Feature | - `POST /api/student/attendance/checkin` valid → 200 + JSON `success:true`              |
|                                                                   |         | - `POST /api/student/attendance/checkin` token invalid → 422                             |

---

## Catatan Konsistensi

- Semua **API Controller** mengembalikan response JSON (bukan redirect/view), sehingga pattern controller menjadi: `return response()->json($action->execute(...))`.
- Setiap Action class yang berhubungan dengan API memiliki **PHPDoc `@return array`** yang menjelaskan struktur JSON response.
- Form Request di namespace `Api` menggunakan `$this->expectsJson()` atau flag `wantsJson()` agar Laravel otomatis mengembalikan error validasi dalam format JSON (bukan redirect HTML).
- Feature Test untuk API menggunakan `$this->postJson()` / `$this->getJson()` dan assert HTTP status code + JSON structure.
- Semua endpoint yang membutuhkan autentikasi diuji dengan skenario **authenticated** dan **unauthenticated** (401).
