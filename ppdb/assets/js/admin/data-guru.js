/**
 * Logic for Data Guru Page
 * Uses admin-api.js
 */

let allGuru = [];

document.addEventListener('DOMContentLoaded', () => {
    loadGuru();
});

// 1. Load Data Guru
async function loadGuru() {
    const tableBody = document.getElementById('guruTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" class="p-4 text-center">Memuat data...</td></tr>';

    const result = await fetchData('guru.php');

    if (result.status === 'success') {
        allGuru = result.data;
        renderTable();
    } else {
        tableBody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-500">${result.message}</td></tr>`;
    }
}

// 2. Render Table with Filters & Search
function renderTable() {
    const statusFilter = document.getElementById('filterStatus').value;
    const categoryFilter = document.getElementById('filterCategory').value;
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const tableBody = document.getElementById('guruTableBody');

    // Filter Data
    const filtered = allGuru.filter(guru => {
        const matchStatus = statusFilter ? guru.status_kepegawaian === statusFilter : true;
        const matchCat = categoryFilter ? guru.kategori === categoryFilter : true;
        const matchSearch = guru.nama.toLowerCase().includes(searchText) || (guru.nip && guru.nip.includes(searchText));
        return matchStatus && matchCat && matchSearch;
    });

    // Render HTML
    if (filtered.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-gray-500">Tidak ada data ditemukan.</td></tr>';
        document.getElementById('tableInfo').innerText = 'Menampilkan 0 data';
        return;
    }

    let html = '';
    filtered.forEach(guru => {
        // Badge Colors
        const statusColors = {
            'PNS': 'bg-green-100 text-green-800',
            'GTY': 'bg-blue-100 text-blue-800',
            'Honorer': 'bg-orange-100 text-orange-800'
        };
        const statusClass = statusColors[guru.status_kepegawaian] || 'bg-gray-100';

        html += `
        <tr class="hover:bg-blue-50 transition border-b border-gray-100">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mr-3 overflow-hidden">
                        ${guru.foto ? `<img src="../${guru.foto}" class="w-full h-full object-cover">` : '<i class="fas fa-user"></i>'}
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">${guru.nama}</div>
                        <div class="text-xs text-gray-500">${guru.nip ? 'NIP: ' + guru.nip : '-'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="font-semibold text-gray-700">${guru.jabatan || '-'}</div>
                <div class="text-xs text-gray-500">${guru.mapel || '-'}</div>
            </td>
            <td class="px-6 py-4">
                <span class="${statusClass} text-xs px-2 py-1 rounded font-bold">${guru.status_kepegawaian}</span>
            </td>
            <td class="px-6 py-4 text-gray-600 font-mono text-xs">${guru.golongan || '-'}</td>
            <td class="px-6 py-4 text-sm text-gray-600"><i class="fab fa-whatsapp text-green-500 mr-1"></i> ${guru.kontak || '-'}</td>
            <td class="px-6 py-4 text-center space-x-2">
                <button onclick="editGuru(${guru.id})" class="text-blue-600 hover:text-blue-800" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteGuru(${guru.id})" class="text-red-500 hover:text-red-700" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        `;
    });

    tableBody.innerHTML = html;
    document.getElementById('tableInfo').innerText = `Menampilkan ${filtered.length} dari ${allGuru.length} data`;
}

// 3. Modal Functions
function openModal() {
    document.getElementById('guruForm').reset();
    document.getElementById('guruId').value = '';
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus mr-2"></i> Tambah Guru Baru';
    document.getElementById('guruModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('guruModal').classList.add('hidden');
}

// 4. Save Guru (Add/Edit)
async function saveGuru(event) {
    event.preventDefault();

    const id = document.getElementById('guruId').value;
    const formData = {
        id: id,
        nama: document.getElementById('nama').value,
        nip: document.getElementById('nip').value,
        golongan: document.getElementById('golongan').value,
        jabatan: document.getElementById('jabatan').value,
        mapel: document.getElementById('mapel').value,
        kategori: document.getElementById('kategori').value,
        status_kepegawaian: document.getElementById('status').value,
        kontak: document.getElementById('kontak').value
    };

    // Note: For real photo upload, we'd use FormData object instead of JSON
    // Here using JSON for simplicity as photo upload logic requires multipart/form-data

    const result = await sendData('guru.php', 'POST', formData);

    if (result.status === 'success') {
        showToast('success', id ? 'Data berhasil diperbarui' : 'Guru berhasil ditambahkan');
        closeModal();
        loadGuru(); // Reload table
    } else {
        Swal.fire('Error', result.message, 'error');
    }
}

// 5. Edit and Delete
function editGuru(id) {
    const guru = allGuru.find(g => g.id == id);
    if (!guru) return;

    document.getElementById('guruId').value = guru.id;
    document.getElementById('nama').value = guru.nama;
    document.getElementById('nip').value = guru.nip;
    document.getElementById('golongan').value = guru.golongan;
    document.getElementById('jabatan').value = guru.jabatan;
    document.getElementById('mapel').value = guru.mapel;
    document.getElementById('kategori').value = guru.kategori;
    document.getElementById('status').value = guru.status_kepegawaian;
    document.getElementById('kontak').value = guru.kontak;

    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit mr-2"></i> Edit Data Guru';
    document.getElementById('guruModal').classList.remove('hidden');
}

function deleteGuru(id) {
    confirmDelete('guru.php', id, loadGuru);
}