CREATE DATABASE IF NOT EXISTS ppdb_skillance;
USE ppdb_skillance;

-- 1. TABEL PENGGUNA (Untuk Login Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Password akan di-hash (bcrypt)
    role ENUM('admin', 'operator') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert User Default (Password: admin123)
INSERT INTO users (nama, email, password) VALUES 
('Administrator', 'admin@smkskillance.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');


-- 2. TABEL BERITA (Sesuai data-berita.js)
CREATE TABLE IF NOT EXISTS berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL, -- Untuk URL SEO Friendly
    ringkasan TEXT,
    isi LONGTEXT,
    kategori VARCHAR(50), -- Kegiatan Sekolah, Prestasi, Pengumuman
    penulis VARCHAR(50),
    tanggal DATE,
    status ENUM('Published', 'Draft') DEFAULT 'Draft',
    gambar VARCHAR(255), -- Path gambar
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. TABEL GURU (Sesuai data-guru.js)
CREATE TABLE IF NOT EXISTS guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nip VARCHAR(30),
    jabatan VARCHAR(50), -- Kepala Sekolah, Waka, Kaprodi
    mapel VARCHAR(100), -- Tugas/Mapel yang diampu
    kategori ENUM('Manajerial', 'Umum', 'Produktif', 'Staff') DEFAULT 'Umum',
    status_kepegawaian ENUM('PNS', 'GTY', 'Honorer') DEFAULT 'Honorer',
    golongan VARCHAR(10), -- III/a, IV/b, dll
    kontak VARCHAR(20),
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. TABEL MATA PELAJARAN (Sesuai data-mapel.js)
CREATE TABLE IF NOT EXISTS mapel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mapel VARCHAR(20) NOT NULL,
    nama_mapel VARCHAR(100) NOT NULL,
    kelompok ENUM('A', 'B', 'C1', 'C2', 'C3'),
    jurusan VARCHAR(50) DEFAULT 'Umum', -- Umum, TKJ, RPL, TBSM
    guru_id INT, -- Relasi ke tabel guru (opsional, bisa string nama dulu)
    nama_guru VARCHAR(100), -- Cadangan jika tidak pakai relasi ID
    beban_jp INT DEFAULT 2,
    initial_color VARCHAR(20) DEFAULT 'blue', -- Untuk UI styling
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. TABEL PPDB (Gabungan data-ppdb.js & verifikasi-berkas.js)
CREATE TABLE IF NOT EXISTS ppdb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_registrasi VARCHAR(20) NOT NULL UNIQUE, -- Contoh: REG-2026-001
    nama_lengkap VARCHAR(100) NOT NULL,
    nisn VARCHAR(20),
    nik VARCHAR(20),
    tempat_lahir VARCHAR(50),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L', 'P'),
    alamat TEXT,
    asal_sekolah VARCHAR(100),
    tahun_lulus YEAR,
    nama_ayah VARCHAR(100),
    nama_ibu VARCHAR(100),
    no_hp VARCHAR(20),
    jurusan_pilihan VARCHAR(10), -- TKJ, RPL, TBSM, AKL
    
    -- Status & Berkas
    status_pendaftaran ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    status_berkas ENUM('Belum Dicek', 'Lengkap', 'Tidak Lengkap') DEFAULT 'Belum Dicek',
    
    -- File Uploads (Path File)
    doc_kk VARCHAR(255),
    doc_ijazah VARCHAR(255),
    doc_akta VARCHAR(255),
    
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 6. TABEL PENGATURAN WEB (Sesuai pengaturan-web.js)
CREATE TABLE IF NOT EXISTS pengaturan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(100) DEFAULT 'SMK Negeri 1 Skillance',
    slogan VARCHAR(255),
    deskripsi TEXT,
    email VARCHAR(100),
    telepon VARCHAR(20),
    alamat TEXT,
    maps_embed TEXT,
    
    -- Sosmed
    facebook VARCHAR(100),
    instagram VARCHAR(100),
    youtube VARCHAR(100),
    
    -- Aset
    logo VARCHAR(255),
    favicon VARCHAR(255),
    
    -- System Config
    mode_maintenance TINYINT(1) DEFAULT 0, -- 0: Off, 1: On
    buka_ppdb TINYINT(1) DEFAULT 1 -- 0: Tutup, 1: Buka
);

-- Insert Pengaturan Default
INSERT INTO pengaturan (nama_sekolah, slogan, email) VALUES 
('SMK Negeri 1 Skillance', 'Berprestasi, Kompeten, dan Berkarakter', 'admin@smkskillance.sch.id');

-- [ADDED BY DEVELOPER] Table for Majors (Jurusan) Content for Website Display
-- This is needed for dynamic content on jurusan.html which requires images and descriptions
CREATE TABLE IF NOT EXISTS majors_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL UNIQUE, -- TKJ, RPL
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Dummy Data for Majors Content
INSERT INTO majors_content (code, name, description, image_url) VALUES
('TKJ', 'Teknik Komputer & Jaringan', 'Mempelajari jaringan, server, dan mikrotik.', 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=1000&auto=format&fit=crop'),
('RPL', 'Rekayasa Perangkat Lunak', 'Fokus pada coding, pembuatan website dan aplikasi.', 'https://images.unsplash.com/photo-1571171637578-41bc2dd41cd2?q=80&w=1000&auto=format&fit=crop'),
('TBSM', 'Teknik & Bisnis Sepeda Motor', 'Keahlian mesin dan kelistrikan sepeda motor.', 'https://images.unsplash.com/photo-1552656967-7a0990a02302?q=80&w=1000&auto=format&fit=crop'),
('AKL', 'Akuntansi Keuangan Lembaga', 'Mempelajari siklus akuntansi dan keuangan.', 'https://images.unsplash.com/photo-1554224155-984067586967?q=80&w=1000&auto=format&fit=crop');

-- Insert Dummy Data for News (Berita)
INSERT INTO berita (judul, slug, ringkasan, isi, kategori, penulis, tanggal, status, gambar) VALUES
('Pendaftaran PPDB Gelombang 1 Dibuka', 'pendaftaran-gelombang-1', 'Pendaftaran Peserta Didik Baru (PPDB) Gelombang 1 resmi dibuka mulai tanggal 1 Juni 2026.', 'Pendaftaran Peserta Didik Baru (PPDB) Gelombang 1 resmi dibuka mulai tanggal 1 Juni 2026. Segera daftarkan diri anda!', 'Pengumuman', 'Admin', CURDATE(), 'Published', 'assets/img/news1.jpg'),
('Prestasi Siswa RPL Juara 1 LKS Tingkat Provinsi', 'juara-lks-rpl', 'Selamat kepada tim RPL yang berhasil menyabet juara 1 dalam LKS Web Technologies.', 'Selamat kepada tim RPL yang berhasil menyabet juara 1 dalam LKS Web Technologies tingkat provinsi.', 'Prestasi', 'Admin', CURDATE(), 'Published', 'assets/img/news2.jpg');
