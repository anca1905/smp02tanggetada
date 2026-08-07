<?php
$pageTitle = 'Dashboard Admin';
$pageHeader = 'Dashboard Overview';
include 'includes/header.php';
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">Selamat Datang, Admin!</h3>
        <p class="text-sm text-gray-500">Berikut statistik sekolah per Hari Ini (<?= date('l, d M Y') ?>)</p>
    </div>
    <div>
        <select class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option>Tahun Ajaran 2025/2026</option>
            <option>Tahun Ajaran 2024/2025</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-600 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Pendaftar</p>
            <h4 class="text-2xl font-bold text-gray-800" id="count-pendaftar">...</h4>
        </div>
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-lg">
            <i class="fas fa-users"></i>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-yellow-500 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Pendaftar Pending</p>
            <h4 class="text-2xl font-bold text-gray-800" id="count-pending">...</h4>
            <span class="text-xs text-green-600 font-bold">Perlu Verifikasi</span>
        </div>
        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-lg">
            <i class="fas fa-user-clock"></i>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-600 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Guru</p>
            <h4 class="text-2xl font-bold text-gray-800" id="count-guru">...</h4>
        </div>
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-lg">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-purple-600 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Berita</p>
            <h4 class="text-2xl font-bold text-gray-800" id="count-berita">...</h4>
        </div>
        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-lg">
            <i class="fas fa-newspaper"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Pendaftar PPDB Terbaru</h3>
            <a href="data-ppdb.php" class="text-sm text-blue-600 font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto p-0">
             <!-- Note: This could be dynamic via JS too, but keeping static scaffold for now as requested or load via JS -->
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-5 py-3 border-b">No. Reg</th>
                        <th class="px-5 py-3 border-b">Nama Siswa</th>
                        <th class="px-5 py-3 border-b">Jurusan</th>
                        <th class="px-5 py-3 border-b">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700" id="latest-registrants">
                    <tr>
                        <td colspan="4" class="px-5 py-3 text-center text-gray-500">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="data-berita.php" class="flex flex-col items-center justify-center p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition group">
                    <i class="fas fa-pen-nib text-blue-600 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-xs font-bold text-gray-600">Tulis Berita</span>
                </a>
                <a href="data-guru.php" class="flex flex-col items-center justify-center p-3 border rounded-lg hover:bg-green-50 hover:border-green-300 transition group">
                    <i class="fas fa-user-plus text-green-600 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-xs font-bold text-gray-600">Tambah Guru</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-3 border rounded-lg hover:bg-purple-50 hover:border-purple-300 transition group">
                    <i class="fas fa-print text-purple-600 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-xs font-bold text-gray-600">Cetak Laporan</span>
                </a>
                <a href="pengaturan-web.php" class="flex flex-col items-center justify-center p-3 border rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition group">
                    <i class="fas fa-cogs text-yellow-600 mb-2 group-hover:scale-110 transition"></i>
                    <span class="text-xs font-bold text-gray-600">Setting</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Extra Scripts
ob_start();
?>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        // Load Stats
        try {
            const response = await fetch('../api/admin/stats.php');
            const result = await response.json();
            if (result.status === 'success') {
                document.getElementById('count-pendaftar').innerText = result.data.total_pendaftar;
                document.getElementById('count-pending').innerText = result.data.pending_pendaftar;
                document.getElementById('count-guru').innerText = result.data.total_guru;
                document.getElementById('count-berita').innerText = result.data.total_berita;
            }
        } catch (error) { console.error('Error loading stats', error); }
        
        // Load Latest Registrants (Optional: Fetch limit 5)
        try {
            const resp = await fetch('../api/admin/ppdb.php');
            const res = await resp.json();
             if (res.status === 'success') {
                 const data = res.data.slice(0, 5); // Take top 5
                 let html = '';
                 data.forEach(s => {
                     const statusColor = s.status_pendaftaran === 'Pending' ? 'text-yellow-600' : (s.status_pendaftaran === 'Accepted' ? 'text-green-600' : 'text-red-600');
                     html += `
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-5 py-3 font-mono text-xs">${s.no_registrasi}</td>
                            <td class="px-5 py-3 font-semibold">${s.nama_lengkap}</td>
                            <td class="px-5 py-3 text-xs">${s.jurusan_pilihan}</td>
                            <td class="px-5 py-3 text-xs font-bold ${statusColor}">${s.status_pendaftaran}</td>
                        </tr>
                     `;
                 });
                 if(data.length > 0) document.getElementById('latest-registrants').innerHTML = html;
                 else document.getElementById('latest-registrants').innerHTML = '<tr><td colspan="4" class="p-4 text-center">Belum ada pendaftar.</td></tr>';
             }
        } catch(e) {}
    });
</script>
<?php
$extraScripts = ob_get_clean();
include 'includes/footer.php';
?>
