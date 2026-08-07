# Tahap 2 — Refactor PublicController & Terkait

> **File Sumber:**
> - `app/Http/Controllers/PublicController.php`
> **Status:** Belum direfactor

## Ringkasan

Controller ini menangani semua halaman yang dapat diakses oleh **pengunjung publik** (tanpa login): Landing page, berita, kontak, kalender akademik, jadwal, dan pendaftaran PPDB online. Logika query Eloquent, validasi inline, dan generator nomor registrasi PPDB masih bercampur di dalam controller.

---

## Daftar Method yang Akan Direfactor

| # | Method              | Deskripsi                                              | Aksi Refactor                  |
|---|---------------------|--------------------------------------------------------|--------------------------------|
| 1 | `home()`            | Query 3 post terbaru + hitung staf & siswa             | **Action**                     |
| 2 | `profil()`          | Query semua fasilitas                                  | **Action**                     |
| 3 | `berita()`          | Paginate semua post published                          | **Action**                     |
| 4 | `showBerita($slug)` | Query post by slug + 5 recent posts terkait            | **Action**                     |
| 5 | `kontak()`          | Render view saja                                       | Tetap di controller (no logic) |
| 6 | `jadwal()`          | Render view saja                                       | Tetap di controller (no logic) |
| 7 | `kalender()`        | Query events, group by bulan                           | **Action**                     |
| 8 | `storeContact()`    | Validasi + simpan Message                              | **Form Request + Action**      |
| 9 | `ppdb()`            | Cek setting `buka_ppdb`, render view                   | **Action**                     |
| 10| `ppdbForm()`        | Cek setting `buka_ppdb`, render form                   | **Action** (bisa shared)       |
| 11| `storePpdb()`       | Validasi + generate no_registrasi + simpan Ppdb        | **Form Request + Action**      |

---

## File yang Akan Dibuat

### Form Requests

| File                                                              | Deskripsi                                                                                   |
|-------------------------------------------------------------------|---------------------------------------------------------------------------------------------|
| `app/Http/Requests/Public/StoreContactMessageRequest.php`         | Validasi `name`, `email`, `subject`, `message` (semua required)                             |
| `app/Http/Requests/Public/StorePpdbRegistrationRequest.php`       | Validasi field PPDB: `nama_lengkap`, `nisn`, `nik`, `jenis_kelamin`, `jurusan`, `no_hp`, `asal_sekolah` |

### Actions

| File                                                              | Deskripsi                                                                                                         |
|-------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------|
| `app/Actions/Public/GetLandingPageDataAction.php`                 | Mengambil 3 post terbaru, menghitung jumlah guru aktif dan siswa aktif. Return array `compact()`.                 |
| `app/Actions/Public/GetProfileDataAction.php`                     | Mengambil semua data fasilitas dari tabel `facilities`.                                                           |
| `app/Actions/Public/GetNewsListAction.php`                        | Mengambil post published terbaru dengan paginate(9). Return `LengthAwarePaginator`.                               |
| `app/Actions/Public/GetNewsDetailAction.php`                      | Mengambil post berdasarkan slug + 5 post terkait terakhir. Throw `ModelNotFoundException` jika slug tidak valid.  |
| `app/Actions/Public/GetCalendarEventsAction.php`                  | Mengambil semua event, lalu mengelompokkannya berdasarkan bulan (`groupBy isoFormat('MMMM Y')`).                  |
| `app/Actions/Public/StoreContactMessageAction.php`                | Menyimpan pesan kontak ke tabel `messages` via `Message::create()`.                                               |
| `app/Actions/Public/CheckPpdbStatusAction.php`                    | Mengecek setting `buka_ppdb` dari tabel `settings`. Return boolean `true`/`false`.                                |
| `app/Actions/Public/ProcessPpdbRegistrationAction.php`            | Meng-generate nomor registrasi `REG-YYYY-XXXX`, menyimpan data pendaftaran ke tabel `ppdb`.                      |

### Tests

| File                                                              | Tipe    | Skenario                                                                                |
|-------------------------------------------------------------------|---------|-----------------------------------------------------------------------------------------|
| `tests/Unit/Public/Actions/GetLandingPageDataActionTest.php`      | Unit    | - Return array dengan key `latest_posts`, `staff`, `student` yang nilainya benar        |
| `tests/Unit/Public/Actions/ProcessPpdbRegistrationActionTest.php` | Unit    | - Nomor registrasi ter-generate dengan format `REG-YYYY-XXXX`                           |
|                                                                   |         | - Data tersimpan ke database                                                            |
| `tests/Unit/Public/Actions/StoreContactMessageActionTest.php`     | Unit    | - Pesan tersimpan ke tabel `messages`                                                   |
| `tests/Feature/Public/Controllers/PublicControllerTest.php`       | Feature | - Guest dapat akses `/` → 200 + view `landing`                                         |
|                                                                   |         | - Guest dapat akses `/berita` → 200 + view `public.news.news`                          |
|                                                                   |         | - Guest dapat akses `/kalender` → 200                                                  |
|                                                                   |         | - POST `/kontak` dengan data valid → redirect back + session `success`                  |
|                                                                   |         | - POST `/kontak` tanpa data → redirect back + session errors                            |
|                                                                   |         | - POST `/ppdb/daftar` dengan data valid → redirect + nomor registrasi di session        |
|                                                                   |         | - POST `/ppdb/daftar` saat PPDB ditutup → redirect back + session `error`               |

---

## Catatan Konsistensi

- Semua Action class menggunakan PHPDoc comment di atas method `execute()` dengan penjelasan parameter dan return type, sama seperti action di modul AdminTu dan Teacher.
- `CheckPpdbStatusAction` dipakai bersama oleh method `ppdb()` dan `ppdbForm()` untuk menghindari duplikasi logika pengecekan setting.
- Format nomor registrasi `REG-YYYY-XXXX` harus diuji secara terisolasi di Unit Test untuk memastikan auto-increment benar.
- Controller hasil refactor hanya berisi injeksi dependensi dan return view/redirect.
