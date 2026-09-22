const modal = document.getElementById('teacherModal');
const form = document.getElementById('teacherForm');
const modalTitle = document.getElementById('modalTitle');
const methodField = document.getElementById('methodField');
const passwordInput = document.getElementById('teacherPassword');
const passwordHint = document.getElementById('passwordHint');

function openModal(mode, data = null) {
    modal.classList.remove('hidden');

    form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

    if (mode === 'edit') {
        modalTitle.innerText = 'Edit Data Guru';
        form.action = `/tu/teacher/${data.id}`;
        methodField.value = 'PUT';

        document.getElementById('teacherName').value = data.name;
        document.getElementById('teacherNIP').value = data.employee_id;
        document.getElementById('teacherPhone').value = data.phone;
        document.getElementById('teacherGender').value = data.gender;
        document.getElementById('teacherSubject').value = data.subject;
        document.getElementById('teacherWaliKelas').value = data.homeroom_class || '';
        document.getElementById('teacherPosition').value = data.position || '';
        document.getElementById('teacherStatus').value = data.status;
        document.getElementById('teacherUsername').value = data.username;

        const photoPreview = document.getElementById('photoPreview');
        if (photoPreview) {
            photoPreview.src = data.photo_url ? (data.photo_url.startsWith('img/') ? '/' + data.photo_url : '/storage/' + data.photo_url) : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.name) + '&background=E5E7EB&color=6B7280';
        }

        passwordInput.removeAttribute('required');
        passwordInput.placeholder = "Kosongkan jika tidak diubah";
        passwordHint.innerText = "Isi hanya jika ingin mengubah password.";
    } else {
        modalTitle.innerText = 'Tambah Guru Baru';
        form.action = form.getAttribute('data-action');
        methodField.value = 'POST';
        form.reset();

        passwordInput.setAttribute('required', 'true');
        passwordInput.placeholder = "Minimal 6 karakter";
        passwordHint.innerText = "Wajib diisi untuk guru baru.";

        const photoPreview = document.getElementById('photoPreview');
        if (photoPreview) {
            photoPreview.src = 'https://ui-avatars.com/api/?name=Guru&background=E5E7EB&color=6B7280';
        }
    }
}

function closeModal() {
    modal.classList.add('hidden');
}

function confirmDelete(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/tu/teacher/${id}`;
    deleteForm.classList.remove('hidden');
}

window.onclick = function (event) {
    if (event.target == modal) {
        closeModal();
    }
    if (event.target == document.getElementById('deleteForm')) {
        document.getElementById('deleteForm').classList.add('hidden');
    }
}
// Photo Preview
document.getElementById('fotoGuru')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});


