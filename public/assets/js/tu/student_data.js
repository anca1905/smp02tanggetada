const modal = document.getElementById("studentModal");
const form = document.getElementById("studentForm");
const modalTitle = document.getElementById("modalTitle");
const methodField = document.getElementById("methodField");
const fotoInput = document.getElementById("fotoSiswa");
const photoPreview = document.getElementById("photoPreview");

function openModal(mode, data = null) {
    modal.classList.remove("hidden");
    if (fotoInput) {
        fotoInput.value = "";
    }

    if (mode === "edit") {
        modalTitle.innerText = "Edit Data Siswa";
        form.action = `/tu/student/${data.nis}`;
        methodField.value = "PUT";

        document.getElementById("namaSiswa").value = data.student_name;
        document.getElementById("nisSiswa").value = data.nis;
        document.getElementById("kelasSiswa").value = data.classroom_id ?? "";
        document.getElementById("jkSiswa").value = data.gender;
        document.getElementById("statusSiswa").value = data.student_status;
        document.getElementById("hpSiswa").value = data.phone_number || "";
        document.getElementById("namaOrtu").value = data.parent_name || "";
        document.getElementById("hpOrtu").value = data.parent_phone || "";

        if (photoPreview) {
            photoPreview.src = data.photo_url
                ? `/storage/${data.photo_url}`
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.student_name)}&background=random`;
        }
    } else {
        modalTitle.innerText = "Tambah Siswa Baru";
        form.action = "/tu/student";
        methodField.value = "POST";
        form.reset();

        const photoPreview = document.getElementById('photoPreview');
        if (photoPreview) {
            photoPreview.src = 'https://ui-avatars.com/api/?name=Siswa&background=E5E7EB&color=6B7280';
        }

        if (photoPreview) {
            photoPreview.src = "https://ui-avatars.com/api/?name=Siswa&background=E5E7EB&color=6B7280";
        }
    }
}

if (fotoInput && photoPreview) {
    fotoInput.addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                photoPreview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}

function closeModal() {
    modal.classList.add("hidden");
}

function confirmDelete(id) {
    const deleteForm = document.getElementById("deleteForm");
    deleteForm.action = `/tu/student/${id}`;
    deleteForm.classList.remove("hidden");
}

window.onclick = function (event) {
    if (event.target == modal) closeModal();
    if (event.target == document.getElementById("deleteForm"))
        document.getElementById("deleteForm").classList.add("hidden");
};

// Photo Preview
document.getElementById('fotoSiswa')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

