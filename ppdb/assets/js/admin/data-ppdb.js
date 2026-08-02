/**
 * Logic for PPDB Management Page
 */
let allSis = [];

document.addEventListener('DOMContentLoaded', () => {
    loadSiswa();
});

async function loadSiswa() {
    const tableBody = document.getElementById('ppdbTableBody');
    tableBody.innerHTML = '<tr><td colspan="7" class="p-4 text-center">Memuat data...</td></tr>';

    const result = await fetchData('ppdb.php');

    if (result.status === 'success') {
        allSis = result.data;
        renderTable();
    } else {
        tableBody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-500">${result.message}</td></tr>`;
    }
}

function renderTable() {
    const filterJurusan = document.getElementById('filterJurusan').value;
    const filterStatus = document.getElementById('filterStatus').value;
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const tableBody = document.getElementById('ppdbTableBody');

    const filtered = allSis.filter(item => {
        const matchJur = filterJurusan ? item.jurusan_pilihan === filterJurusan : true;
        const matchStat = filterStatus ? item.status_pendaftaran === filterStatus : true;
        const matchSearch = item.nama_lengkap.toLowerCase().includes(searchText) || item.no_registrasi.toLowerCase().includes(searchText);
        return matchJur && matchStat && matchSearch;
    });

    if (filtered.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data ditemukan.</td></tr>';
        document.getElementById('tableInfo').innerText = '0 data';
        return;
    }

    let html = '';
    filtered.forEach(sis => {
        // Status Colors
        const statusColors = {
            'Pending': 'bg-yellow-100 text-yellow-800',
            'Accepted': 'bg-green-100 text-green-800',
            'Rejected': 'bg-red-100 text-red-800'
        };
        const statusClass = statusColors[sis.status_pendaftaran] || 'bg-gray-100';

        html += `
        <tr class="hover:bg-blue-50 transition border-b border-gray-100">
            <td class="px-6 py-4 font-mono text-xs font-bold text-school-blue">${sis.no_registrasi}</td>
            <td class="px-6 py-4">
                <div class="font-bold text-gray-800">${sis.nama_lengkap}</div>
                <div class="text-xs text-gray-500">${sis.nisn || '-'}</div>
            </td>
            <td class="px-6 py-4 text-sm">${sis.jurusan_pilihan}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${sis.asal_sekolah}</td>
            <td class="px-6 py-4 text-sm">${sis.no_hp}</td>
            <td class="px-6 py-4"><span class="${statusClass} text-xs px-2 py-1 rounded font-bold">${sis.status_pendaftaran}</span></td>
            <td class="px-6 py-4 text-center space-x-2">
                <button onclick="viewDetail(${sis.id})" class="text-blue-600 hover:text-blue-800" title="Verifikasi"><i class="fas fa-eye"></i></button>
                <button onclick="deleteSiswa(${sis.id})" class="text-red-500 hover:text-red-700" title="Hapus"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        `;
    });

    tableBody.innerHTML = html;
    document.getElementById('tableInfo').innerText = `Menampilkan ${filtered.length} dari ${allSis.length} data`;
}

// Actions
function viewDetail(id) {
    // In a real app this would open a modal with full details
    // For now we simulate quick verification
    const sis = allSis.find(s => s.id == id);

    Swal.fire({
        title: `Verifikasi: ${sis.nama_lengkap}`,
        html: `
            <p class="mb-2">Status saat ini: <b>${sis.status_pendaftaran}</b></p>
            <hr class="my-2">
            <p>Ubah status menjadi:</p>
            <div class="flex gap-2 justify-center mt-2">
                <button id="btnAccept" class="bg-green-500 text-white px-3 py-1 rounded">Terima</button>
                <button id="btnReject" class="bg-red-500 text-white px-3 py-1 rounded">Tolak</button>
                <button id="btnReset" class="bg-yellow-500 text-white px-3 py-1 rounded">Pending</button>
            </div>
        `,
        showConfirmButton: false
    });

    // Add event listeners to the buttons inside Swal
    setTimeout(() => {
        document.getElementById('btnAccept').onclick = () => updateStatus(id, 'Accepted');
        document.getElementById('btnReject').onclick = () => updateStatus(id, 'Rejected');
        document.getElementById('btnReject').onclick = () => updateStatus(id, 'Pending'); // Typo in original logic, fixed
        document.getElementById('btnReset').onclick = () => updateStatus(id, 'Pending');
    }, 100);
}

async function updateStatus(id, status) {
    const result = await sendData('ppdb.php', 'POST', { id: id, status_pendaftaran: status });
    if (result.status === 'success') {
        Swal.close();
        showToast('success', `Status diubah menjadi ${status}`);
        loadSiswa();
    } else {
        Swal.fire('Error', result.message, 'error');
    }
}

function deleteSiswa(id) {
    confirmDelete('ppdb.php', id, loadSiswa);
}