# Panduan & Roadmap Pelaksanaan Task SIMS

Dokumen ini adalah ringkasan induk dan peta jalan pelaksanaan 13 kebutuhan dari `.docs/brief.md`. Setiap task telah dibagi ke dalam berkas panduan mandiri di direktori `.docs/task/`, lengkap dengan langkah teknis, berkas yang disentuh, dan **Exit Criteria** (kriteria tuntas) yang ketat sebelum lanjut ke task berikutnya.

---

## 1. Urutan Prioritas Pengerjaan

| Status | Fase | File Panduan | Poin Brief | Keterangan & Tingkat Urgensi |
|:---:|---|---|---|---|
| [x] | **Fase 1** | `01-auth-session-protection.md` | #10 | **Kritis / Auth**: Proteksi multi-guard session, redirect `/login*`, update tombol navbar Landing Page. |
| [x] | **Fase 1** | `02-ppdb-pdf-access.md` | #8 | **Kritis / Data**: Fix error akses berkas privat PDF peserta PPDB di panel Admin TU. |
| [x] | **Fase 2** | `03-student-attendance-subject-link.md` | #13 | **Core DB**: Hubungkan sesi absensi guru dengan mata pelajaran (`subject_id`). |
| [x] | **Fase 2** | `04-ppdb-integration-testing.md` | #1 | **Core Flow**: Pengujian integrasi end-to-end form PPDB publik ke database & dashboard. |
| [x] | **Fase 2** | `05-student-id-card-attendance.md` | #2 | **Core Feature**: Validasi scan ID card/barcode siswa pada sistem presensi & kiosk. |
| [x] | **Fase 3** | `06-tu-student-photo-upload.md` | #7 | **CRUD**: Tambah fungsionalitas upload/update foto siswa di panel Admin TU. |
| [x] | **Fase 3** | `07-disable-menus-kiosk.md` | #3, #4, #9 | **UI Cleanup**: Nonaktifkan menu fasilitas, sarana prasarana, dan tombol kiosk di landing page. |
| [ ] | **Fase 4** | `08-export-pdf.md` | #6 | **Reporting**: Migrasi dari `window.print` ke export PDF berbasis server. *(Perlu konfirmasi library)* |
| [ ] | **Fase 4** | `09-mobile-layout-improvements.md` | #11 | **Mobile**: Perbaikan layout, overflow, dan konsistensi UI pada Flutter client. |
| [ ] | **Fase 4** | `10-replace-emoji-icons.md` | #5, #12 | **Polish Visual**: Ganti emoji status & badge absensi menjadi icon SVG/FontAwesome. |

---

## 2. Kebijakan Pengembangan (No Over-engineering & Library Checklist)

1. **Prinsip YAGNI & Tanpa Over-engineering:**
   - Gunakan fitur native Laravel 10 dan Tailwind CSS 4 yang sudah terpasang.
   - Jangan membuat layer abstraksi berlebihan (cukup Controller / FormRequest / Action standar proyek).
2. **Kebutuhan Library Tambahan (Perlu Konfirmasi User):**
   - **Task 08 (Export PDF):** Saat ini Laravel belum memiliki library generator PDF backend. Opsi standar: `barryvdh/laravel-dompdf`. *(Tanyakan ke user sebelum memasang).*
   - **Task 10 (Icons):** Proyek sudah memuat FontAwesome 6 via CDN dan inline SVG Tailwind. Sebaiknya hindari memasang package icon baru jika icon yang ada sudah mencukupi.

---

## 3. Aturan Exit Plan

Setiap agen atau programmer wajib memastikan **seluruh butir pada blok "Exit Criteria"** di masing-masing file bertanda `[x]` dan test terkait sukses dijalankan sebelum menandai task selesai dan berpindah ke task berikutnya.
