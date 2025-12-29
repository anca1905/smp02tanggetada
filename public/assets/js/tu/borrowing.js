const modal = document.getElementById('bookingModal');
const form = document.getElementById('bookingForm');
const modalTitle = document.getElementById('modalTitle');
const methodField = document.getElementById('methodField');
const statusContainer = document.getElementById('statusContainer');

function openModal(mode, data = null) {
    modal.classList.remove('hidden');

    if (mode === 'edit') {
        modalTitle.innerText = 'Edit Peminjaman';
        form.action = `/tu/peminjaman/${data.borrowing_id}`;
        methodField.value = 'PUT';
        statusContainer.classList.remove('hidden');

        document.getElementById('namaPeminjam').value = data.full_name;
        document.getElementById('nisPeminjam').value = data.nis || '';
        document.getElementById('kelasPeminjam').value = data.class || '';
        document.getElementById('ruanganSelect').value = data.room_type;
        document.getElementById('tglPeminjaman').value = data.borrow_date;
        document.getElementById('jamMulai').value = data.start_time;
        document.getElementById('jamSelesai').value = data.end_time;
        document.getElementById('descKegiatan').value = data.activity_description;
        document.getElementById('penanggungJawab').value = data.responsible_person;
        document.getElementById('statusSelect').value = data.status;

    } else {
        modalTitle.innerText = 'Ajukan Peminjaman';
        // Ganti dari fungsi {{ route }} jadi hardcode endpoint 
        form.action = '/tu/borrowing';
        methodField.value = 'POST';
        statusContainer.classList.add('hidden');
        form.reset();
    }
}

function closeModal() {
    modal.classList.add('hidden');
}

function confirmDelete(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/tu/borrowing/${id}`;
    deleteForm.classList.remove('hidden');
}

window.onclick = function (event) {
    if (event.target == modal) closeModal();
    if (event.target == document.getElementById('deleteForm')) document.getElementById('deleteForm').classList
        .add('hidden');
}
function refreshTable() {
    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('table-container').innerHTML;

            document.getElementById('table-container').innerHTML = newTable;
        })
        .catch(err => console.log('Gagal update tabel:', err));
}

setInterval(refreshTable, 60000);