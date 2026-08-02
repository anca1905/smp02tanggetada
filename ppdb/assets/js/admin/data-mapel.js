/**
 * Logic for Data Mapel Page
 */
let allMapel = [];

document.addEventListener('DOMContentLoaded', () => {
    loadMapel();
});

async function loadMapel() {
    const tableBody = document.getElementById('mapelTableBody');
    tableBody.innerHTML = '<tr><td colspan="7" class="p-4 text-center">Memuat data...</td></tr>';

    const result = await fetchData('mapel.php');

    if (result.status === 'success') {
        allMapel = result.data;
        renderTable();
    } else {
        tableBody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-red-500">${result.message}</td></tr>`;
    }
}

function renderTable() {
    const filterKelompok = document.getElementById('filterKelompok').value;
    const filterJurusan = document.getElementById('filterJurusan').value;
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const tableBody = document.getElementById('mapelTableBody');

    const filtered = allMapel.filter(item => {
        const matchKel = filterKelompok ? item.kelompok === filterKelompok : true;
        const matchJur = filterJurusan ? item.jurusan === filterJurusan : true;
        const matchSearch = item.nama_mapel.toLowerCase().includes(searchText) || item.kode_mapel.toLowerCase().includes(searchText);
        return matchKel && matchJur && matchSearch;
    });

    if (filtered.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data ditemukan.</td></tr>';
        document.getElementById('tableInfo').innerText = 'Menampilkan 0 data';
        return;
    }

    let html = '';
    filtered.forEach(mapel => {
        html += `
        <tr class="hover:bg-blue-50 transition border-b border-gray-100">
            <td class="px-6 py-4 font-mono text-xs text-school-blue font-bold">${mapel.kode_mapel}</td>
            <td class="px-6 py-4 font-semibold text-gray-700">${mapel.nama_mapel}</td>
            <td class="px-6 py-4"><span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold">${mapel.kelompok}</span></td>
            <td class="px-6 py-4 text-xs">${mapel.jurusan}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${mapel.nama_guru || '-'}</td>
            <td class="px-6 py-4 text-center font-bold text-gray-700">${mapel.beban_jp} JP</td>
            <td class="px-6 py-4 text-center space-x-2">
                <button onclick="editMapel(${mapel.id})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                <button onclick="deleteMapel(${mapel.id})" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        `;
    });

    tableBody.innerHTML = html;
    document.getElementById('tableInfo').innerText = `Menampilkan ${filtered.length} dari ${allMapel.length} data`;
}

// Modal & CRUD
function openModal() {
    document.getElementById('mapelForm').reset();
    document.getElementById('mapelId').value = '';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-book-open mr-2"></i> Tambah Mata Pelajaran';
    document.getElementById('mapelModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('mapelModal').classList.add('hidden');
}

async function saveMapel(event) {
    event.preventDefault();
    const id = document.getElementById('mapelId').value;
    const data = {
        id: id,
        kode_mapel: document.getElementById('kode').value,
        nama_mapel: document.getElementById('nama').value,
        kelompok: document.getElementById('kelompok').value,
        jurusan: document.getElementById('jurusan').value,
        nama_guru: document.getElementById('guru').value,
        beban_jp: document.getElementById('jp').value
    };

    const result = await sendData('mapel.php', 'POST', data);
    if (result.status === 'success') {
        showToast('success', 'Data mapel berhasil disimpan');
        closeModal();
        loadMapel();
    } else {
        Swal.fire('Error', result.message, 'error');
    }
}

function editMapel(id) {
    const item = allMapel.find(m => m.id == id);
    if (!item) return;

    document.getElementById('mapelId').value = item.id;
    document.getElementById('kode').value = item.kode_mapel;
    document.getElementById('nama').value = item.nama_mapel;
    document.getElementById('kelompok').value = item.kelompok;
    document.getElementById('jurusan').value = item.jurusan;
    document.getElementById('guru').value = item.nama_guru;
    document.getElementById('jp').value = item.beban_jp;

    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit mr-2"></i> Edit Mata Pelajaran';
    document.getElementById('mapelModal').classList.remove('hidden');
}

function deleteMapel(id) {
    confirmDelete('mapel.php', id, loadMapel);
}