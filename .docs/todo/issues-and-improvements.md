# Isu dan Area Perbaikan Refactoring

Dokumen ini berisi daftar temuan dari hasil review menyeluruh terhadap 4 tahap refactoring (Auth, Public, API, dan Controller sisa). Isu-isu ini tidak menyebabkan error kritis (semua test pass), namun perlu diperbaiki di iterasi berikutnya untuk memperkuat keamanan, integritas data, dan konsistensi kode.

---

## 1. Medium Severity

| Isu | Lokasi / File | Deskripsi & Solusi |
|-----|---------------|--------------------|
| **Ketiadaan DB Transactions pada Mass Update** | `ProcessGraduationAction`, `StoreStudentAttendanceAction` | Proses update berjalan di dalam `foreach` loop. Jika terjadi *fatal error* di tengah loop, sebagian data tersimpan dan sebagian gagal (partial update). **Solusi:** Bungkus loop dengan `DB::transaction(function() { ... })`. |
| **Penggunaan Guard Implisit pada Multi-Guard** | `GetGraduationDataAction`, `GenerateQrSessionAction`, `StoreStudentAttendanceAction` | Menggunakan `Auth::user()` secara langsung. Karena aplikasi menggunakan 4 guard (`web`, `operator`, `teacher`, `student`), ini rentan salah konteks jika default guard bukan `teacher`. **Solusi:** Ubah menjadi `Auth::guard('teacher')->user()`. |
| **Missing Role Authorization Middleware** | `Principal/DashboardController` | Pada `DashboardControllerTest`, operator biasa (Admin TU) terdeteksi bisa mengakses dashboard Kepala Sekolah (mengembalikan HTTP 200). **Solusi:** Tambahkan middleware khusus atau policy untuk memblokir `role_operator != 'Kepala Sekolah'`. |

## 2. Low Severity

| Isu | Lokasi / File | Deskripsi & Solusi |
|-----|---------------|--------------------|
| **Action Menerima Request Object Penuh** | `SubmitStudentAssignmentAction`, `GetStudentPresenceDataAction` | Action seharusnya menerima `array validated data` atau skalar untuk *testability* dan *decoupling* dari HTTP layer. **Solusi:** Ubah signature parameter `execute(Request $request)` menjadi tipe data terstruktur (array/variabel eksplisit). |
| **Inkonsistensi Namespace Controller** | `StudentPresenceController` | Berada di root `App\Http\Controllers` padahal merupakan modul level guru. **Solusi:** Pindahkan ke `App\Http\Controllers\Teacher\StudentPresenceController` dan perbarui routes. |
| **Validasi Foreign Key Kurang Ketat** | `StoreStudentAttendanceRequest`, `GenerateQrRequest`, `SubmitAssignmentRequest` | Input `class` dan `assignment_id` hanya di-validate tipe datanya (`required`), tidak dicek eksistensinya. **Solusi:** Tambahkan rule `exists:classrooms,id` dan `exists:assignments,id`. |
| **Pengecekan Redundan di Controller** | `LearningController::index()` | Mengecek `if (!$student->classroom_id)` padahal `GetStudentCoursesAction` sudah melakukan pengecekan yang sama. **Solusi:** Pindahkan seluruh logika ini sepenuhnya ke dalam Action atau FormRequest. |
| **Parameter Tak Terpakai** | `StudentPresenceController::getSiswa()` | Mengambil parameter `Request $request` namun tidak digunakan di dalam blok fungsi. **Solusi:** Hapus `$request` dari argument. |
| **Potensi Undefined Index** | `UpdateStudentAction` | Mengecek `empty($student->parent_password)` lalu menset `$data['parent_password'] = bcrypt('ortu'.$data['nis'])`. Jika `$data['nis']` tidak disertakan (karena request `sometimes`), akan error. **Solusi:** Gunakan `$student->nis` sebagai fallback. |

## 3. Info / Optimasi

| Isu | Lokasi / File | Deskripsi |
|-----|---------------|-----------|
| **N+1 / Query Banyak di Dashboard Loop** | `GetPrincipalDashboardAction` | Perhitungan *attendance chart* selama 6 bulan terakhir melakukan >12 query di dalam loop. Sebaiknya gunakan *aggregate* `DB::select` / query builder tunggal dengan `GROUP BY`. |
| **Inkonsistensi PascalCase Model** | `Teacher_absence` | Nama model menggunakan *snake_case*. Konvensi standar Laravel adalah *PascalCase* (`TeacherAbsence`). |
| **Dead Code pada Validation Messages** | `StoreStudentRequest` | Terdapat custom message `parent_phone.required` namun rule untuk `parent_phone` diatur sebagai `nullable`. |
| **Edge Cases pada Unit Testing** | Berbagai Action Test | Unit tests berjalan baik untuk *happy path*, namun masih minim pengujian *edge case* (seperti input array kosong, NIS tidak valid/hilang, concurrent API requests). |
