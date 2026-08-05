document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('[data-toggle="password"]');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const container = this.closest('.relative');
            const input = container.querySelector('input');
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeSlashIcon = this.querySelector('.eye-slash-icon');

            if (input && input.type === 'password') {
                input.type = 'text';
                if (eyeIcon) eyeIcon.classList.add('hidden');
                if (eyeSlashIcon) eyeSlashIcon.classList.remove('hidden');
            } else if (input) {
                input.type = 'password';
                if (eyeIcon) eyeIcon.classList.remove('hidden');
                if (eyeSlashIcon) eyeSlashIcon.classList.add('hidden');
            }
            if (input) input.focus();
        });
    });
});
