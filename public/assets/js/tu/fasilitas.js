const modal = document.getElementById('fasilitasModal');
const form = document.getElementById('fasilitasForm');
const modalTitle = document.getElementById('modalTitle');
const methodField = document.getElementById('methodField');

function openModal(mode, data = null) {
    modal.classList.remove('hidden');
    if (mode === 'edit') {
        modalTitle.innerText = 'Edit Data Fasilitas';
        form.action = `/tu/facility/${data.id}`;
        methodField.value = 'PUT';

        document.getElementById('fasilitasTitle').value = data.title;
        document.getElementById('fasilitasImage').value = data.image_path;
    } else {
        modalTitle.innerText = 'Tambahkan Fasilitas';
        form.action = '/tu/facility';
        methodField.value = 'POST';
        form.reset();
    }
}

function closeModal() {
    modal.classList.add('hidden');
}

function confirmDelete(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/tu/facility/${id}`;
    deleteForm.classList.remove('hidden');
}

window.onclick = function (event) {
    if (event.target == modal) closeModal();
    if (event.target == document.getElementById('deleteForm')) document.getElementById('deleteForm').classList
        .add('hidden');
}