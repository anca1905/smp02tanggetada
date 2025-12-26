const photoInput = document.getElementById('foto-input');
const previewPhoto = document.getElementById('preview-photo');

photoInput.onchange = evt => {
    const [file] = photoInput.files;
    if (file) {
        previewPhoto.src = URL.createObjectURL(file);
    }
}