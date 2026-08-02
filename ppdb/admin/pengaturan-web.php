<?php
$pageTitle = "Pengaturan Website";
$pageHeader = "Konfigurasi Website";
include 'includes/header.php';
?>

<style>
    /* Custom Toggle Switch */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #003366;
    }

    .toggle-checkbox:checked+.toggle-label {
        background-color: #003366;
    }
</style>

<form id="settingsForm" onsubmit="saveSettings(event)">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-school-blue mb-4 border-l-4 border-school-blue pl-3">
                    Identitas Sekolah</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Website /
                            Sekolah</label>
                        <input type="text" id="nama_sekolah" value="SMK Negeri 1 Skillance"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue transition bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Slogan /
                            Tagline</label>
                        <input type="text" id="slogan" value="Berprestasi, Kompeten, dan Berkarakter"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue transition bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Singkat
                            (SEO)</label>
                        <textarea id="deskripsi"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue transition bg-gray-50 h-24">SMK Negeri 1 Skillance adalah sekolah kejuruan unggulan yang berfokus pada teknologi informasi dan bisnis manajemen.</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-school-blue mb-4 border-l-4 border-school-blue pl-3">
                    Kontak & Alamat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Resmi</label>
                        <input type="email" id="email" value="admin@smkskillance.sch.id"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" id="telepon" value="(021) 789-1234"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                        <input type="text" id="alamat" value="Jl. Pendidikan No. 1, Kec. Coding, Kab. Server"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Google Maps Embed
                            URL</label>
                        <input type="text" id="maps_embed" value="https://www.google.com/maps/embed?..."
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:border-school-blue text-xs font-mono text-gray-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-school-blue mb-4 border-l-4 border-school-blue pl-3">
                    Sosial Media</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <i class="fab fa-facebook absolute left-3 top-3 text-blue-600"></i>
                        <input type="text" id="facebook" value="smkskillance"
                            class="w-full border pl-9 pr-3 py-2 rounded focus:outline-none focus:border-school-blue">
                    </div>
                    <div class="relative">
                        <i class="fab fa-instagram absolute left-3 top-3 text-pink-600"></i>
                        <input type="text" id="instagram" value="@smk.skillance"
                            class="w-full border pl-9 pr-3 py-2 rounded focus:outline-none focus:border-school-blue">
                    </div>
                    <div class="relative">
                        <i class="fab fa-youtube absolute left-3 top-3 text-red-600"></i>
                        <input type="text" id="youtube" value="Skillance TV"
                            class="w-full border pl-9 pr-3 py-2 rounded focus:outline-none focus:border-school-blue">
                    </div>
                </div>
            </div>

        </div>

        <div class="lg:col-span-1 space-y-6">

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-school-blue mb-4">Logo Website</h3>
                <div class="text-center mb-4">
                    <div onclick="uploadFile('Logo')"
                        class="w-32 h-32 mx-auto bg-gray-100 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden relative group cursor-pointer hover:border-school-blue transition">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Logo_Tut_Wuri_Handayani.svg/1200px-Logo_Tut_Wuri_Handayani.svg.png"
                            alt="Logo" class="w-24 h-auto">
                        <div
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-xs font-bold"><i class="fas fa-camera"></i>
                                Ganti</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Klik gambar untuk mengganti logo.<br>Format: PNG
                        (Transparan)</p>
                </div>

                <h3 class="font-bold text-lg text-school-blue mb-4 mt-6">Favicon</h3>
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gray-100 border rounded flex items-center justify-center p-1">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Logo_Tut_Wuri_Handayani.svg/1200px-Logo_Tut_Wuri_Handayani.svg.png"
                            class="w-full h-full object-contain">
                    </div>
                    <button type="button" onclick="uploadFile('Favicon')"
                        class="text-sm text-school-blue hover:underline font-bold">Upload
                        Favicon</button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-school-blue mb-4">Brosur PPDB</h3>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload File Brosur (PDF/Image)</label>
                    <input type="file" id="file_brosur" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-school-blue hover:file:bg-blue-100 transition">
                </div>
                <div id="current_brosur" class="text-xs text-green-600 font-semibold hidden">
                    <i class="fas fa-check-circle mr-1"></i> Brosur saat ini: <a href="#" target="_blank" id="link_brosur" class="underline hover:text-school-blue">Lihat File</a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-school-blue mb-4">Status Sistem</h3>

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-bold text-sm">Mode Maintenance</p>
                        <p class="text-xs text-gray-500">Tutup web sementara</p>
                    </div>
                    <div
                        class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" name="maintenance" id="mode_maintenance"
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300" />
                        <label for="mode_maintenance"
                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-bold text-sm">Buka PPDB Online</p>
                        <p class="text-xs text-gray-500">Tampilkan menu pendaftaran</p>
                    </div>
                    <div
                        class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" name="ppdb" id="buka_ppdb"
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300"
                            checked />
                        <label for="buka_ppdb"
                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </div>

                <div class="border-t pt-4 mt-4">
                    <input type="hidden" id="settingId" value="1">
                    <button type="submit"
                        class="w-full bg-school-blue text-white py-3 rounded-lg font-bold hover:bg-blue-800 shadow-lg transition transform hover:-translate-y-1">
                        <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                    </button>
                </div>

            </div>

        </div>
    </div>

</form>

<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/js/admin/pengaturan-web.js"></script>

<?php
$extraScripts = ob_get_clean();
include 'includes/footer.php';
?>