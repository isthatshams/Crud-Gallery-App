const form = document.getElementById('form');
const firstName = document.getElementById('first_name');
const lastName = document.getElementById('last_name');
const email = document.getElementById('email');
const password = document.getElementById('password');

const emailHint = document.getElementById('email_hint');
const passwordHint = document.getElementById('password_hint');

firstName.addEventListener('input', () => {
    if (firstName.value.length === 0 || firstName.value === '') {
        firstName.setCustomValidity("This field is invalid!");
    } else {
        firstName.setCustomValidity("");
    }
});

lastName.addEventListener('input', () => {
    if (lastName.value.length === 0) {
        lastName.setCustomValidity("This field is invalid!");
    } else {
        lastName.setCustomValidity("");
    }
});

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
    } else if (!password.value.includes("@")) {
        passwordHint.innerHTML = "It should have @ character";
        password.setCustomValidity("This field is invalid!");
    } else {
        passwordHint.innerHTML = "";
        password.setCustomValidity("");
    }
});


form.addEventListener('submit', (e) => {

    let isValid = true;

    if (firstName.value.length === 0 || firstName.value === '') {
        firstName.setCustomValidity("This field is invalid!");
        isValid = false
    } else {
        firstName.setCustomValidity("");
    }

    if (lastName.value.length === 0) {
        lastName.setCustomValidity("This field is invalid!");
        isValid = false
    } else {
        lastName.setCustomValidity("");
    }

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
    } else if (!password.value.includes("@")) {
        passwordHint.innerHTML = "It should have @ character";
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