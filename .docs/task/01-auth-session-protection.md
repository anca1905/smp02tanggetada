# Task 01: Proteksi Session Login & Tombol Landing Page

> **Ref Brief:** Poin #10  
> **Tingkat Urgensi:** Prioritas 1 (Keamanan & UX Session)

---

## 1. Tujuan
1. Mengubah tombol login di Landing Page dan Navbar Publik agar secara dinamis menampilkan tombol **"Dashboard"** (mengarahkan ke dashboard sesuai guard/peran) dan opsi **"Logout"** jika ada session pengguna yang aktif.
2. Mencegah user yang telah login mengakses kembali rute login (`/login`, `/login/admin-tu`, `/login/pegawai`, `/login/kepala-sekolah`) tanpa logout terlebih dahulu (redirect otomatis ke dashboard masing-masing).

---

## 2. Analisis & Berkas yang Terlibat
Aplikasi menggunakan sistem **multi-guard**: `operator`, `teacher`, `student`, dan `web`.

- **Middleware / Controller Auth:**
  - `app/Http/Middleware/RedirectIfAuthenticated.php`
  - `app/Http/Controllers/Auth/AuthController.php`
  - `routes/web.php`
- **Tampilan (Blade):**
  - `resources/views/layouts/public.blade.php`
  - `resources/views/landing.blade.php`
- **Pengujian:**
  - `tests/Feature/Auth/LoginProtectionTest.php` (atau `tests/Feature/Auth/AuthControllerTest.php`)

---

## 3. Langkah-Langkah Implementasi

### Langkah 1: Tulis Test Ekspektasi Proteksi Login (TDD)
Buat atau perbarui test di `tests/Feature/Auth/`:
- Test ketika guest mengakses `/login`, `/login/admin-tu`, dsb: Mengembalikan HTTP 200.
- Test ketika `operator` aktif mengakses `/login*`: Ter-redirect ke `/tu/dashboard` (atau `/principal/dashboard` jika Kepala Sekolah).
- Test ketika `teacher` aktif mengakses `/login*`: Ter-redirect ke `/teacher/dashboard`.
- Test ketika `student` aktif mengakses `/login*`: Ter-redirect ke `/student/dashboard`.

### Langkah 2: Konfigurasi Middleware `guest` / `RedirectIfAuthenticated`
Pastikan middleware `RedirectIfAuthenticated.php` memeriksa semua guard aktif:
```php
foreach ($guards as $guard) {
    if (Auth::guard($guard)->check()) {
        $user = Auth::guard($guard)->user();
        if ($guard === 'operator') {
            return in_array($user->role_operator, ['Kepala Sekolah', 'principal'])
                ? redirect()->route('principal.dashboard')
                : redirect()->route('tu.dashboard');
        }
        if ($guard === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        if ($guard === 'student') {
            return redirect()->route('student.dashboard');
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
```
Pasang middleware `guest:operator,teacher,student` pada rute auth di `routes/web.php`.

### Langkah 3: Update Tombol Navbar Landing Page
Di `resources/views/layouts/public.blade.php` dan `resources/views/landing.blade.php`:
- Cek auth multi-guard:
  - Jika `Auth::guard('operator')->check()`: link ke dashboard TU / Kepsek.
  - Jika `Auth::guard('teacher')->check()`: link ke dashboard guru.
  - Jika `Auth::guard('student')->check()`: link ke dashboard siswa.
  - Tambahkan tombol Logout (POST ke route `logout`).
  - Jika tidak ada session (`@guest` dari ketiga guard): tampilkan tombol "Login".

---

## 4. Exit Criteria (Definisi Selesai)
- [x] User yang telah login dengan guard apapun dilarang melihat halaman login dan langsung dialihkan ke dashboard yang valid.
- [x] Navbar publik menampilkan tombol ke Dashboard dan Logout jika user memiliki session aktif.
- [x] Tidak ada konflik guard saat logout.
- [x] Test otomatis `php artisan test --filter=Login` lulus 100%.
