<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir PPDB Online - SMK Negeri 1 Skillance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'school-blue': '#003366',
                        'school-yellow': '#FFCC00'
                    },
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- PHP Header -->
    <?php
    include 'helpers/get_settings.php';
    if (!isset($settings['buka_ppdb']) || $settings['buka_ppdb'] == 0) {
        // Redirect to maintenance or custom "Closed" page
        // For now, let's just show an alert and redirect to index, or handle it gracefully
        echo "<script>alert('Pendaftaran PPDB sedang ditutup.'); window.location.href='index.php';</script>";
        exit;
    }
    include 'layouts/header.php';
    ?>

    <div class="bg-school-blue py-8 text-white text-center">
        <h2 class="text-2xl md:text-3xl font-bold uppercase mb-2">Formulir Pendaftaran Siswa Baru</h2>
        <p class="text-gray-300 text-sm">Mohon isi data dengan benar sesuai Ijazah dan Kartu Keluarga (KK)</p>
    </div>

    <div class="container mx-auto px-4 py-10 flex-grow">
        <div class="flex flex-col lg:flex-row gap-8">

            <div class="lg:w-2/3">
                <form id="form-ppdb" class="bg-white p-6 md:p-8 rounded shadow-lg border-t-4 border-school-yellow">

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-school-blue border-b pb-2 mb-4"><i class="fas fa-graduation-cap mr-2"></i> Pilihan Kompetensi Keahlian</h3>
                        <div class="bg-blue-50 p-4 rounded border border-blue-100 mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Jurusan Utama <span class="text-red-500">*</span></label>
                            <select id="jurusan-select" name="jurusan" class="w-full border-gray-300 rounded px-3 py-2 border focus:outline-none focus:border-school-blue">
                                <option value="" disabled selected>-- Pilih Jurusan --</option>
                                <!-- Loaded by JS -->
                            </select>
                            <p class="text-xs text-gray-500 mt-1">*Pastikan pilihan sesuai minat dan bakat siswa.</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-school-blue border-b pb-2 mb-4"><i class="fas fa-user mr-2"></i> Data Pribadi Calon Siswa</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue uppercase" placeholder="Sesuai Ijazah" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">NISN <span class="text-red-500">*</span></label>
                                <input type="number" name="nisn" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue" placeholder="10 Digit NISN" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">NIK (No. KTP/KIA) <span class="text-red-500">*</span></label>
                                <input type="number" name="nik" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue" placeholder="16 Digit NIK" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue uppercase">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold mb-1">Alamat Lengkap (Sesuai KK)</label>
                            <textarea name="alamat" class="w-full border px-3 py-2 rounded h-20 focus:outline-none focus:border-school-blue uppercase" placeholder="Nama Jalan, RT/RW, Desa/Kelurahan"></textarea>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-school-blue border-b pb-2 mb-4"><i class="fas fa-school mr-2"></i> Data Sekolah Asal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Nama SMP/MTS Asal <span class="text-red-500">*</span></label>
                                <input type="text" name="asal_sekolah" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue uppercase" placeholder="SMP NEGERI ..." required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue" value="2026">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-school-blue border-b pb-2 mb-4"><i class="fas fa-users mr-2"></i> Data Orang Tua / Wali</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Nama Ayah Kandung</label>
                                <input type="text" name="nama_ayah" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue uppercase">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Nama Ibu Kandung</label>
                                <input type="text" name="nama_ibu" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue uppercase">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold mb-1">Nomor WA / Telepon Aktif <span class="text-red-500">*</span></label>
                            <input type="tel" name="no_hp" class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue" placeholder="Contoh: 0812xxxx" required>
                            <p class="text-xs text-gray-500 mt-1">Nomor ini akan digunakan untuk info kelulusan.</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" id="btn-submit" class="w-full bg-school-blue text-white font-bold py-3 rounded hover:bg-blue-800 transition shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i> SIMPAN DATA PENDAFTARAN
                        </button>
                    </div>

                </form>
            </div>

            <div class="lg:w-1/3 space-y-6">

                <div class="bg-white p-6 rounded shadow border border-gray-200">
                    <h4 class="font-bold text-school-blue mb-4 uppercase border-b pb-2">Butuh Bantuan?</h4>
                    <p class="text-sm text-gray-600 mb-4">Jika mengalami kesulitan mengisi formulir, silakan hubungi panitia PPDB:</p>
                    <ul class="space-y-3 text-sm font-semibold">
                        <li class="flex items-center gap-2"><i class="fab fa-whatsapp text-green-500 text-lg"></i> Pak Budi: 0812-3456-7890</li>
                        <li class="flex items-center gap-2"><i class="fab fa-whatsapp text-green-500 text-lg"></i> Bu Siti: 0852-9876-5432</li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded shadow border border-gray-200">
                    <h4 class="font-bold text-school-blue mb-4 uppercase border-b pb-2">Syarat Daftar Ulang</h4>
                    <ul class="text-sm text-gray-700 space-y-2 list-disc pl-4">
                        <li>Bukti Pendaftaran Online (Cetak)</li>
                        <li>Fotokopi Ijazah / SKL (Legalisir)</li>
                        <li>Fotokopi Kartu Keluarga (KK)</li>
                        <li>Fotokopi Akta Kelahiran</li>
                        <li>Fotokopi KIP/KPS (Jika ada)</li>
                        <li>Pas Foto 3x4 (2 Lembar)</li>
                        <li>Map Snelhecter (TKJ: Biru, RPL: Merah)</li>
                    </ul>
                </div>

                <div class="bg-school-yellow p-6 rounded shadow text-school-blue">
                    <h4 class="font-bold mb-4 uppercase border-b border-blue-900 pb-2">Jadwal PPDB</h4>
                    <ul class="text-sm space-y-3 font-semibold">
                        <li class="flex justify-between">
                            <span>Pendaftaran:</span>
                            <span>1 - 30 Juni</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Verifikasi:</span>
                            <span>1 - 5 Juli</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Pengumuman:</span>
                            <span>7 Juli</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Daftar Ulang:</span>
                            <span>8 - 10 Juli</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- PHP Footer -->
    <?php include 'layouts/footer.php'; ?>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>