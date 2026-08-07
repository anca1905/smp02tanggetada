<?php
$pageTitle = 'Data Pendaftar PPDB';
$pageHeader = 'Data Pendaftar PPDB Online';
include 'includes/header.php';
?>

<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row justify-between gap-4 items-center">
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <select id="filterJurusan" onchange="renderTable()"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option value="">Semua Jurusan</option>
            <option value="TKJ">Teknik Komputer Jaringan</option>
            <option value="RPL">Rekayasa Perangkat Lunak</option>
            <option value="TBSM">Teknik Bisnis Sepeda Motor</option>
        </select>
        <select id="filterStatus" onchange="renderTable()"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option value="">Semua Status</option>
            <option value="Pending">Menunggu Verifikasi</option>
            <option value="Accepted">Diterima</option>
            <option value="Rejected">Ditolak</option>
        </select>
    </div>

    <div class="flex gap-2 w-full md:w-auto">
        <div class="relative flex-grow md:flex-grow-0">
            <input type="text" id="searchInput" onkeyup="renderTable()" placeholder="Cari Nama / No. Reg..."
                class="pl-9 pr-4 py-2 border border-gray-300 rounded text-sm w-full focus:outline-none focus:border-school-blue transition">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
        </div>
        <button onclick="exportData()"
            class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-green-700 flex items-center gap-2 shadow-sm transition">
            <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Export Excel</span>
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">No. Registrasi</th>
                    <th class="px-6 py-4">Nama Siswa</th>
                    <th class="px-6 py-4">Jurusan Pilihan</th>
                    <th class="px-6 py-4">Asal Sekolah</th>
                    <th class="px-6 py-4">No. HP</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="ppdbTableBody" class="text-sm text-gray-700 divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-xs text-gray-500" id="tableInfo">
        Menampilkan data...
    </div>
</div>

<?php
$extraScripts = '<script src="../assets/js/admin/data-ppdb.js"></script>';
include 'includes/footer.php';
?>
