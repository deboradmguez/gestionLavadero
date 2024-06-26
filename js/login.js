document.getElementById('show_hide_password').addEventListener('click', function () {
    var passwordInput = document.getElementById('contraseña');
    var eyeIcon = document.getElementById('eye_icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('bi-eye');
        eyeIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('bi-eye-slash');
        eyeIcon.classList.add('bi-eye');
    }
});