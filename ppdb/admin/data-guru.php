<?php
$pageTitle = "Data Guru & GTK";
$pageHeader = "Manajemen Guru & Tenaga Kependidikan";
include 'includes/header.php';
?>

<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row justify-between gap-4 items-center">
    <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <select id="filterStatus" onchange="renderTable()"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option value="">Semua Status</option>
            <option value="PNS">PNS / ASN</option>
            <option value="GTY">Guru Tetap Yayasan (GTY)</option>
            <option value="Honorer">Honorer</option>
        </select>
        <select id="filterCategory" onchange="renderTable()"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-school-blue bg-white">
            <option value="">Semua Kategori</option>
            <option value="Manajerial">Manajerial</option>
            <option value="Umum">Guru Mapel Umum</option>
            <option value="Produktif">Guru Produktif</option>
        </select>
    </div>

    <div class="flex gap-2 w-full md:w-auto">
        <div class="relative flex-grow md:flex-grow-0">
            <input type="text" id="searchInput" onkeyup="renderTable()" placeholder="Cari Nama / NIP..."
                class="pl-9 pr-4 py-2 border border-gray-300 rounded text-sm w-full focus:outline-none focus:border-school-blue focus:ring-1 focus:ring-school-blue transition">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
        </div>
        <button onclick="exportData()"
            class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-green-700 flex items-center gap-2 shadow-sm transition">
            <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Export</span>
        </button>
        <button onclick="openModal()"
            class="bg-school-blue text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-800 flex items-center gap-2 shadow-sm transition">
            <i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Tambah Guru</span>
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Nama Guru & NIP</th>
                    <th class="px-6 py-4">Jabatan / Tugas</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Golongan</th>
                    <th class="px-6 py-4">Kontak</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="guruTableBody" class="text-sm text-gray-700 divide-y divide-gray-100">
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50">
        <span class="text-xs text-gray-500" id="tableInfo">Menampilkan data...</span>
        <div class="flex gap-1">
            <button class="px-3 py-1 border rounded text-xs hover:bg-gray-200 text-gray-600 transition">Prev</button>
            <button class="px-3 py-1 bg-school-blue text-white rounded text-xs shadow-sm font-bold">1</button>
            <button class="px-3 py-1 border rounded text-xs hover:bg-gray-200 text-gray-600 transition">Next</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="guruModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="font-bold text-lg text-school-blue" id="modalTitle"><i class="fas fa-user-plus mr-2"></i> Tambah Guru Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50 modal-scroll">
            <form id="guruForm" onsubmit="saveGuru(event)">
                <input type="hidden" id="guruId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap & Gelar</label>
                        <input type="text" id="nama" required class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Contoh: Drs. H. Budi Santoso, M.Pd">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">NIP / NIY / NUPTK</label>
                        <input type="text" id="nip" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Nomor Induk">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pangkat / Golongan</label>
                        <select id="golongan" class="w-full border px-3 py-2 rounded text-sm bg-white">
                            <option value="-">-</option>
                            <option value="III/a">III/a</option>
                            <option value="III/b">III/b</option>
                            <option value="III/c">III/c</option>
                            <option value="III/d">III/d</option>
                            <option value="IV/a">IV/a</option>
                            <option value="IV/b">IV/b</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jabatan Struktural</label>
                        <input type="text" id="jabatan" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Contoh: Kepala Sekolah">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tugas / Mapel</label>
                        <input type="text" id="mapel" required class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="Contoh: Guru Matematika">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Guru</label>
                        <select id="kategori" class="w-full border px-3 py-2 rounded text-sm bg-white">
                            <option value="Manajerial">Manajerial / Pimpinan</option>
                            <option value="Umum">Guru Mapel Umum</option>
                            <option value="Produktif">Guru Produktif (Kejuruan)</option>
                            <option value="Staff">Staff / TU</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Kepegawaian</label>
                        <select id="status" class="w-full border px-3 py-2 rounded text-sm bg-white">
                            <option value="PNS">PNS / ASN</option>
                            <option value="GTY">Guru Tetap Yayasan</option>
                            <option value="Honorer">Honorer</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" id="kontak" class="w-full border px-3 py-2 rounded text-sm focus:outline-none focus:border-school-blue" placeholder="08xxxxxxxxxx">
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
$extraScripts = '<script src="../assets/js/admin/data-guru.js"></script>';
include 'includes/footer.php';
?>
