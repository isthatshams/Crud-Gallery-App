const form = document.getElementById('form');
const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const email = document.getElementById('email');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirmPassword');
const checkBox = document.getElementById('agree');

const emailHint = document.getElementById('emailHint');
const passwordHint = document.getElementById('passwordHint');
const confirmPasswordHint = document.getElementById('confirmPasswordHint');
const checkBoxHint = document.getElementById('checkBoxHint');

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

confirmPassword.addEventListener('input', () => {
    if (confirmPassword.value === password.value && confirmPassword.value.length !== 0) {
        confirmPasswordHint.innerHTML = "";
        confirmPassword.setCustomValidity("");
    } else {
        confirmPasswordHint.innerHTML = "It should be the same as password";
        confirmPassword.setCustomValidity("This field is invalid!");
    }
});

checkBox.addEventListener('change', () => {
    if (!checkBox.checked) {
        checkBox.setCustomValidity("This field is invalid!");
        checkBoxHint.innerHTML = "It is Required";
    } else {
        checkBox.setCustomValidity("");
        checkBoxHint.innerHTML = "";
    }
});

form.addEventListener('submit', (e) => {

    let isValid = true;

    if (firstName.value.length === 0 || firstName.value.length === '') {
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

    if (confirmPassword.value === password.value && confirmPassword.value.length !== 0) {
        confirmPasswordHint.innerHTML = "";
        confirmPassword.setCustomValidity("");
    } else {
        confirmPasswordHint.innerHTML = "It should be the same as password";
        confirmPassword.setCustomValidity("This field is invalid!");
        isValid = false
    }

    if (!checkBox.checked) {
        checkBox.setCustomValidity("This field is invalid!");
        checkBoxHint.innerHTML = "It is Required";
        isValid = false
    } else {
        checkBox.setCustomValidity("");
        checkBoxHint.innerHTML = "";
    }

    if (!isValid) {
        e.preventDefault();
    }
});