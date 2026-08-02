<?php
$pageTitle = "Data Mata Pelajaran";
$pageHeader = "Kurikulum & Mata Pelajaran";
include 'includes/header.php';
?>

<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row justify-between gap-4 items-center">
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <select id="filterKelompok" onchange="renderTable()"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option value="">Semua Kelompok</option>
            <option value="A">Muatan Nasional (A)</option>
            <option value="B">Muatan Kewilayahan (B)</option>
            <option value="C1">Dasar Bidang (C1)</option>
            <option value="C2">Dasar Program (C2)</option>
            <option value="C3">Kompetensi Keahlian (C3)</option>
        </select>
        <select id="filterJurusan" onchange="renderTable()"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option value="">Semua Jurusan</option>
            <option value="Umum">Umum (Semua Jurusan)</option>
            <option value="TKJ">Teknik Komputer Jaringan</option>
            <option value="RPL">Rekayasa Perangkat Lunak</option>
            <option value="TBSM">Teknik Bisnis Sepeda Motor</option>
        </select>
    </div>

    <div class="flex gap-2 w-full md:w-auto">
        <div class="relative flex-grow md:flex-grow-0">
            <input type="text" id="searchInput" onkeyup="renderTable()" placeholder="Cari Mapel / Kode..."
                class="pl-9 pr-4 py-2 border border-gray-300 rounded text-sm w-full focus:outline-none focus:border-school-blue transition">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
        </div>
        <button onclick="openModal()"
            class="bg-school-blue text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-800 flex items-center gap-2 shadow-sm transition">
            <i class="fas fa-plus"></i> <span class="hidden sm:inline">Mapel Baru</span>
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Kode Mapel</th>
                    <th class="px-6 py-4">Nama Mata Pelajaran</th>
                    <th class="px-6 py-4">Kelompok</th>
                    <th class="px-6 py-4">Tingkat / Jurusan</th>
                    <th class="px-6 py-4">Guru Pengampu</th>
                    <th class="px-6 py-4 text-center">Beban (JP)</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="mapelTableBody" class="text-sm text-gray-700 divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-xs text-gray-500" id="tableInfo">
        Menampilkan data...
    </div>
</div>

<!-- Modal -->
<div id="mapelModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="font-bold text-lg text-school-blue" id="modalTitle"><i class="fas fa-book-open mr-2"></i> Tambah Mata Pelajaran</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50 modal-scroll">
            <form id="mapelForm" onsubmit="saveMapel(event)">
                <input type="hidden" id="mapelId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kode Mapel</label>
                        <input type="text" id="kode" required class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue uppercase" placeholder="Contoh: C3.RPL.12">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input type="text" id="nama" required class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Nama Mapel Lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kelompok Mapel</label>
                        <select id="kelompok" class="w-full border px-3 py-2 rounded text-sm bg-white">
                            <option value="A">Muatan Nasional (A)</option>
                            <option value="B">Muatan Kewilayahan (B)</option>
                            <option value="C1">C1. Dasar Bidang Keahlian</option>
                            <option value="C2">C2. Dasar Program Keahlian</option>
                            <option value="C3">C3. Kompetensi Keahlian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Peruntukan Jurusan</label>
                        <select id="jurusan" class="w-full border px-3 py-2 rounded text-sm bg-white">
                            <option value="Umum">Umum (Semua Jurusan)</option>
                            <option value="TKJ">Teknik Komputer Jaringan</option>
                            <option value="RPL">Rekayasa Perangkat Lunak</option>
                            <option value="TBSM">Teknik Bisnis Sepeda Motor</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Guru Pengampu</label>
                        <input type="text" id="guru" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Nama Guru">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Beban Jam (JP)</label>
                        <input type="number" id="jp" required class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Contoh: 4">
                    </div>
                </div>
                <div class="mt-6 border-t pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100 font-bold text-sm">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-school-blue text-white rounded hover:bg-blue-800 font-bold text-sm shadow-lg">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extraScripts = '<script src="../assets/js/admin/data-mapel.js"></script>';
include 'includes/footer.php';
?>
