<?php
$pageTitle = "Berita & Artikel";
$pageHeader = "Manajemen Berita Sekolah";
include 'includes/header.php';
?>

<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row justify-between gap-4 items-center">
    <div class="flex gap-2">
        <select class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option>Semua Kategori</option>
            <option>Berita Sekolah</option>
            <option>Prestasi Siswa</option>
            <option>Pengumuman</option>
        </select>
        <select class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option>Status: Publish</option>
            <option>Status: Draft</option>
        </select>
    </div>

    <div class="flex gap-2 w-full md:w-auto">
        <div class="relative flex-grow md:flex-grow-0">
            <input type="text" id="searchInput" onkeyup="loadBerita()" placeholder="Cari Judul Berita..."
                class="pl-9 pr-4 py-2 border border-gray-300 rounded text-sm w-full focus:outline-none focus:border-school-blue transition">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
        </div>
        <button onclick="openModal()"
            class="bg-school-blue text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-800 flex items-center gap-2 shadow-sm transition">
            <i class="fas fa-plus"></i> <span class="hidden sm:inline">Tulis Berita</span>
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Judul Berita</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Penulis</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Dilihat</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="beritaTableBody" class="text-sm text-gray-700 divide-y divide-gray-100">
                <!-- Loaded via JS -->
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-xs text-gray-500">
        Menampilkan data berita...
    </div>
</div>

<!-- Modal -->
<div id="beritaModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="font-bold text-lg text-school-blue" id="modalTitle"><i class="fas fa-edit mr-2"></i> Tulis Berita Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50 modal-scroll">
            <form id="beritaForm" onsubmit="saveBerita(event)">
                <input type="hidden" id="beritaId">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Judul Berita</label>
                            <input type="text" id="judul" required class="w-full border px-3 py-2 rounded text-base focus:outline-none focus:border-school-blue" placeholder="Masukkan judul yang menarik...">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Ringkasan Singkat (Excerpt)</label>
                            <textarea id="ringkasan" rows="2" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Ringkasan untuk ditampilkan di daftar berita..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Konten Berita</label>
                            <textarea id="isi" rows="10" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Tulis isi berita di sini..."></textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Gambar Utama</label>
                            <div onclick="document.getElementById('file_gambar').click()" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 cursor-pointer transition relative">
                                <i id="preview_icon" class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                <img id="preview_image" class="hidden h-32 w-full object-cover rounded mb-2 mx-auto">
                                <p id="file_name" class="text-xs text-gray-500">Klik untuk upload gambar</p>
                                <input type="file" id="file_gambar" class="hidden" accept="image/*" onchange="previewFile()">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                            <select id="kategori" class="w-full border px-3 py-2 rounded text-sm bg-white">
                                <option>Berita Sekolah</option>
                                <option>Prestasi Siswa</option>
                                <option>Pengumuman</option>
                                <option>Agenda Kegiatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Status Publikasi</label>
                            <select id="status" class="w-full border px-3 py-2 rounded text-sm bg-white">
                                <option value="Published">Terbitkan Sekarang</option>
                                <option value="Draft">Simpan sebagai Draft</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 border-t pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100 font-bold text-sm">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-school-blue text-white rounded hover:bg-blue-800 font-bold text-sm shadow-lg">Simpan Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extraScripts = '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $("#isi").summernote({
            placeholder: "Tulis isi berita di sini...",
            tabsize: 2,
            height: 300,
            toolbar: [
                ["style", ["style"]],
                ["font", ["bold", "underline", "clear"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["insert", ["link", "picture", "video"]],
                ["view", ["fullscreen", "codeview", "help"]]
            ]
        });
    });
</script>
<script src="../assets/js/admin/data-berita.js"></script>';
include 'includes/footer.php';
?>