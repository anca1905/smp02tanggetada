const modal = document.getElementById('studentModal');
const form = document.getElementById('studentForm');
const modalTitle = document.getElementById('modalTitle');
const methodField = document.getElementById('methodField');

function openModal(mode, data = null) {
    modal.classList.remove('hidden');
    if (mode === 'edit') {
        modalTitle.innerText = 'Edit Data Siswa';
        form.action = `/tu/student/${data.nis}`;
        methodField.value = 'PUT';

        document.getElementById('namaSiswa').value = data.student_name;
        document.getElementById('nisSiswa').value = data.nis;
        document.getElementById('kelasSiswa').value = data.class;
        document.getElementById('jkSiswa').value = data.gender;
        document.getElementById('statusSiswa').value = data.student_status;
        document.getElementById('hpSiswa').value = data.phone_number || '';
    } else {
        modalTitle.innerText = 'Tambah Siswa Baru';
        form.action = "{{ route('tu.student.store') }}";
        methodField.value = 'POST';
        form.reset();
    }
}

function closeModal() {
    modal.classList.add('hidden');
}

function confirmDelete(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/tu/student/${id}`;
    deleteForm.classList.remove('hidden');
}

window.onclick = function (event) {
    if (event.target == modal) closeModal();
    if (event.target == document.getElementById('deleteForm')) document.getElementById('deleteForm').classList.add('hidden');
}