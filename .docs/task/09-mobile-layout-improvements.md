# Task 09: Perbaikan Responsivitas Layout Mobile Web Dashboard

> **Ref Brief:** Poin #11  
> **Tingkat Urgensi:** Prioritas 4 (UI/UX Web Dashboard Responsiveness)

---

## 1. Masalah & Tujuan
Dashboard web SIMS diperuntukkan bagi Admin TU, Guru, dan Kepala Sekolah. Tampilan antarmuka saat ini memiliki beberapa kendala responsivitas saat dibuka melalui layar perangkat mobile / smartphone (viewport `< 768px`):
1. **Master Shell & Navigasi**: Header mobile, backdrop overlay sidebar, serta padding konten utama (`p-6`) menyita ruang layar kecil dan berpotensi memicu horizontal scrollbar jika ada konten lebar.
2. **Dashboard Admin TU (`tu/index.blade.php`)**: Banner sambutan, grid metrik statistik, serta pembagian 3 kolom data pendaftar PPDB mengalami teks terjepit atau clipping pada layar sempit.
3. **Dashboard Guru (`teacher/index.blade.php` & `student_presence.blade.php`)**: Kartu status sesi absensi dan tabel riwayat presensi perlu penyesuaian padding sel dan horizontal scroll yang rapi.
4. **Dashboard Kepala Sekolah (`principal/dashboard.blade.php`)**: Baris 5 kartu KPI berpotensi asimetris/terjepit pada grid 2 kolom di mobile, serta penyelarasan donut chart kehadiran guru dan legend-nya.

> **Catatan Penting:** Aplikasi mobile client Flutter (`mobile/`) tidak disentuh pada task ini sesuai klarifikasi kebutuhan. Fokus perbaikan murni pada antarmuka web dashboard berbasis Laravel Blade & Tailwind CSS.

---

## 2. Berkas yang Terlibat
- `resources/views/layouts/app.blade.php` (Master layout dashboard, navigasi drawer mobile, header bar, padding content)
- `resources/views/tu/index.blade.php` (Dashboard Admin TU)
- `resources/views/teacher/index.blade.php` (Dashboard Guru)
- `resources/views/principal/dashboard.blade.php` (Dashboard Kepala Sekolah)
- `resources/views/student_presence.blade.php` (Halaman Presensi Siswa Guru)

---

## 3. Langkah-Langkah Perbaikan

### Langkah 1: Optimasi Master Shell & Navigasi Mobile (`layouts/app.blade.php`)
- Tambahkan `min-w-0 w-full overflow-x-hidden` pada pembungkus `#main-content` untuk mencegah konten tabel/lebar merusak batas lebar layar perangkat mobile.
- Sesuaikan padding header `px-4 sm:px-6` dan ukuran judul `@yield('title')` menggunakan `text-lg sm:text-xl font-bold text-gray-800 truncate` agar judul panjang tidak bertabrakan dengan tombol hamburger di viewport `< 400px`.
- Ubah padding area konten `<main>` dari `p-6` menjadi `p-4 sm:p-6` untuk memberikan ruang pandang yang lebih leluasa bagi kartu dan form di HP.
- Perbarui backdrop overlay sidebar menggunakan utility Tailwind CSS modern `bg-black/50` dengan transisi opacity yang mulus.

### Langkah 2: Responsivitas Dashboard Admin TU (`tu/index.blade.php`)
- Sesuaikan banner sambutan dengan padding `p-4 sm:p-6` dan tipografi adaptif `text-xl sm:text-2xl`.
- Sesuaikan grid 4 kartu metrik kehadiran guru dan siswa (`gap-3 sm:gap-4`).
- Ubah kartu counter PPDB Online: ganti `grid-cols-3 divide-x divide-gray-100` menjadi `grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100` agar angka dan label status tetap terbaca jelas tanpa terpotong di layar HP.

### Langkah 3: Responsivitas Dashboard Guru & Presensi Siswa (`teacher/index.blade.php` & `student_presence.blade.php`)
- Rapikan grid 3 kartu status sesi harian (Apel, Di Kelas, Pulang) pada `teacher/index.blade.php` dengan `grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4`.
- Sesuaikan padding tabel riwayat sesi agar lebih ringkas pada mobile (`px-3 py-3 sm:px-6 sm:py-4`) di dalam wrapper `overflow-x-auto`.
- Pada `student_presence.blade.php`, jadikan form filter dan tombol aksi Buka QR tersusun rapi secara responsif (`flex flex-col sm:flex-row`).

### Langkah 4: Responsivitas Dashboard Kepala Sekolah (`principal/dashboard.blade.php`)
- Sesuaikan grid 5 KPI card menjadi `grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4`, dengan kartu ke-5 menggunakan `col-span-2 sm:col-span-1` agar seimbang dan rapi pada layout mobile 2 kolom.
- Pastikan chart kehadiran guru (donut) dan keterangannya tersusun fleksibel (`flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6`) sehingga tidak berhimpitan di layar smartphone kecil.
- Pastikan container grafik canvas memiliki aspect ratio atau batas tinggi yang pas di mobile.

### Langkah 5: Pengujian & Kompilasi Aset
- Jalankan test PHPUnit untuk controller dashboard dan presensi siswa:
  ```bash
  php artisan test --filter="DashboardControllerTest"
  php artisan test --filter="StudentPresenceControllerTest"
  ```
- Kompilasi ulang stylesheet Tailwind CSS:
  ```bash
  npm run build
  ```
- Jalankan linter kode:
  ```bash
  ./vendor/bin/pint --dirty
  ```

---

## 4. Exit Criteria (Definisi Selesai)
- [x] Tampilan dashboard Admin TU, Guru, dan Kepala Sekolah responsif di viewport smartphone (360px - 414px) tanpa terjadi horizontal layout breaking / overflow.
- [x] Tombol toggle sidebar mobile dan overlay berfungsi menutup dan membuka menu dengan mulus.
- [x] Angka statistik dan counter PPDB di dashboard TU terbaca jelas tanpa teks terpotong.
- [x] 5 KPI cards di dashboard Kepala Sekolah tersusun rapi dan simetris di layar mobile.
- [x] Semua pengujian PHPUnit terkait dashboard lulus tanpa error.
- [x] Kompilasi `npm run build` berhasil tanpa warning/error Tailwind CSS.
