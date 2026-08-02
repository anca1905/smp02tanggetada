/**
 * Logic for Data Berita Page
 */
let allBerita = [];

document.addEventListener('DOMContentLoaded', () => {
    loadBerita();
});

async function loadBerita() {
    const tableBody = document.getElementById('beritaTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" class="p-4 text-center">Memuat data...</td></tr>';

    const result = await fetchData('berita.php');

    if (result.status === 'success') {
        allBerita = result.data;
        renderTable();
    } else {
        tableBody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-500">${result.message}</td></tr>`;
    }
}

function renderTable() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const tableBody = document.getElementById('beritaTableBody');

    const filtered = allBerita.filter(item => item.judul.toLowerCase().includes(searchText));

    if (filtered.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-gray-500">Tidak ada data ditemukan.</td></tr>';
        return;
    }

    let html = '';
    filtered.forEach(berita => {
        const statusColor = berita.status === 'Published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';

        // Define image source with fallback
        let imgSrc = '../assets/img/news-placeholder.jpg';
        if (berita.gambar && berita.gambar !== '') {
            imgSrc = '../' + berita.gambar;
        }

        html += `
        <tr class="hover:bg-blue-50 transition border-b border-gray-100">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <img src="${imgSrc}" class="w-16 h-10 object-cover rounded mr-3" onerror="this.onerror=null; this.src='../assets/img/news-placeholder.jpg'">
                    <div>
                        <div class="font-bold text-gray-800 line-clamp-1">${berita.judul}</div>
                        <div class="text-xs text-gray-500">${berita.date || '-'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${berita.kategori}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${berita.penulis}</td>
            <td class="px-6 py-4"><span class="${statusColor} text-xs px-2 py-1 rounded font-bold">${berita.status}</span></td>
            <td class="px-6 py-4 text-xs text-gray-500"><i class="fas fa-eye mr-1"></i> 0</td>
            <td class="px-6 py-4 text-center space-x-2">
                <button onclick="editBerita(${berita.id})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></button>
                <button onclick="deleteBerita(${berita.id})" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        `;
    });

    tableBody.innerHTML = html;
}

function previewFile() {
    const preview = document.getElementById('preview_image');
    const icon = document.getElementById('preview_icon');
    const fileInput = document.getElementById('file_gambar');
    const fileName = document.getElementById('file_name');

    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
            fileName.innerText = file.name;
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.classList.add('hidden');
        icon.classList.remove('hidden');
        fileName.innerText = 'Klik untuk upload gambar';
    }
}

function openModal() {
    document.getElementById('beritaForm').reset();
    document.getElementById('beritaId').value = '';
    $('#isi').summernote('code', ''); // Reset Summernote

    // Reset Preview
    const preview = document.getElementById('preview_image');
    if (preview) {
        preview.classList.add('hidden');
        preview.src = '';
    }
    const icon = document.getElementById('preview_icon');
    if (icon) icon.classList.remove('hidden');

    const fileName = document.getElementById('file_name');
    if (fileName) fileName.innerText = 'Klik untuk upload gambar';

    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus mr-2"></i> Tulis Berita Baru';
    if (document.getElementById('beritaModal')) document.getElementById('beritaModal').classList.remove('hidden');
}

function closeModal() {
    if (document.getElementById('beritaModal')) document.getElementById('beritaModal').classList.add('hidden');
}

async function saveBerita(event) {
    event.preventDefault();
    const id = document.getElementById('beritaId').value;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('judul', document.getElementById('judul').value);
    formData.append('kategori', document.getElementById('kategori').value);
    formData.append('status', document.getElementById('status').value);
    formData.append('ringkasan', document.getElementById('ringkasan').value);
    formData.append('isi', $('#isi').summernote('code'));

    const fileInput = document.getElementById('file_gambar');
    if (fileInput.files.length > 0) {
        formData.append('gambar', fileInput.files[0]);
    }

    const result = await sendData('berita.php', 'POST', formData);
    if (result.status === 'success') {
        showToast('success', 'Berita berhasil disimpan');
        closeModal();
        loadBerita();
    } else {
        Swal.fire('Error', result.message, 'error');
    }
}

function editBerita(id) {
    const item = allBerita.find(b => b.id == id);
    if (!item) return;

    document.getElementById('beritaId').value = item.id;
    document.getElementById('judul').value = item.judul;
    document.getElementById('kategori').value = item.kategori;
    document.getElementById('status').value = item.status;
    document.getElementById('ringkasan').value = item.ringkasan;

    // Set Summernote content
    $('#isi').summernote('code', item.isi);

    // Set Image Preview
    const preview = document.getElementById('preview_image');
    const icon = document.getElementById('preview_icon');
    const fileName = document.getElementById('file_name');

    if (item.gambar && item.gambar !== 'assets/img/news-placeholder.jpg') {
        preview.src = '../' + item.gambar;
        preview.classList.remove('hidden');
        icon.classList.add('hidden');
        fileName.innerText = 'Ganti gambar (Biarkan kosong jika tidak berubah)';
    } else {
        preview.classList.add('hidden');
        icon.classList.remove('hidden');
        fileName.innerText = 'Belum ada gambar';
    }

    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit mr-2"></i> Edit Berita';
    document.getElementById('beritaModal').classList.remove('hidden');
}

function deleteBerita(id) {
    confirmDelete('berita.php', id, loadBerita);
}