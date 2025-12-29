const modal = document.getElementById('roomModal');
const form = document.getElementById('roomForm');
const modalTitle = document.getElementById('modalTitle');
const methodField = document.getElementById('methodField');

function openModal(mode, data = null) {
    modal.classList.remove('hidden');
    if (mode === 'edit') {
        modalTitle.innerText = 'Edit Data Ruangan';
        form.action = `/tu/room/${data.room_id}`;
        methodField.value = 'PUT';

        document.getElementById('roomName').value = data.room_name;
        document.getElementById('roomLocation').value = data.location;
        document.getElementById('roomDesc').value = data.description || '';
    } else {
        modalTitle.innerText = 'Tambah Ruangan';
        // Ganti dari fungsi {{ route }} jadi hardcode endpoint
        form.action = '/tu/room';
        methodField.value = 'POST';
        form.reset();
    }
}

function closeModal() {
    modal.classList.add('hidden');
}

function confirmDelete(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/tu/room/${id}`;
    deleteForm.classList.remove('hidden');
}

window.onclick = function (event) {
    if (event.target == modal) closeModal();
    if (event.target == document.getElementById('deleteForm')) document.getElementById('deleteForm').classList
        .add('hidden');
}