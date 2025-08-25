const form = document.getElementById('form');
const email = document.getElementById('email');
const password = document.getElementById('password');
const checkBox = document.getElementById('check');

const emailHint = document.getElementById('emailHint');
const passwordHint = document.getElementById('passwordHint');
const checkBoxHint = document.getElementById('checkHint');

email.addEventListener('input', () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailRegex.test(email.value)) {
        email.setCustomValidity("");
        emailHint.innerHTML = "";
    } else {
        email.setCustomValidity("This field is invalid!");
        emailHint.innerHTML = "This field is invalid!";
    }
});

password.addEventListener('input', () => {
    if (password.value.length < 8) {
        passwordHint.innerHTML = "It should be 8 characters at least";
        password.setCustomValidity("This field is invalid!");
    } else {
        passwordHint.innerHTML = "";
        password.setCustomValidity("");
    }
});

form.addEventListener('submit', (e) => {

    let isValid = true;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailRegex.test(email.value)) {
        email.setCustomValidity("");
        emailHint.innerHTML = "";
    } else {
        email.setCustomValidity("This field is invalid!");
        emailHint.innerHTML = "This field is invalid!";
        isValid = false
    }

    if (password.value.length < 8) {
        passwordHint.innerHTML = "It should be 8 characters at least";
        password.setCustomValidity("This field is invalid!");
        isValid = false
    } else {
        passwordHint.innerHTML = "";
        password.setCustomValidity("");
    }

    if (!isValid) {
        e.preventDefault();
    }
});