var nameInputR = document.getElementById("register-username");
var emailInputR = document.getElementById("register-email");
var dateInputR = document.getElementById("register-date");
var passwordInputR = document.getElementById("register-password");
var confPasswordInputR = document.getElementById("register-conf-password");

var emailInputL = document.getElementById("login-email");
var passwordInputL = document.getElementById("login-password");

var registerError = false;
var loginError = false;

function checkRegisterFrontEnd() {
    registerError = false;
    checkUsername();
    checkEmail(1);
    checkPassword(1);
    checkDate();
    checkConfPassword();

    console.log(registerError);
    if (registerError === true) {
        document.getElementById("r-message-inv").style.display="inline";
        return false;
    }
    return true;
}

function checkLoginFrontEnd() {
    loginError = false;
    checkEmail(2);
    checkPassword(2);

    if (loginError === true) {
        document.getElementById("l-message-inv").style.display="inline";
        return false;
    }
    return true;
}

function checkUsername() {
    var maxLength = 20;

    if (nameInputR.value.match(/^[0-9a-zA-Z]+$/) && nameInputR.value.length <= maxLength) {
        nameInputR.classList.remove("is-danger");
        nameInputR.classList.add("is-success");
        document.getElementById("r-username-inv").style.display="none";
    }else {
        nameInputR.classList.remove("is-success");
        nameInputR.classList.add("is-danger");
        document.getElementById("r-username-inv").style.display="inline";
        registerError = true;
    }
}

function checkEmail(mode) {
    var emailPattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    switch (mode) {
        case 1:
            if (emailPattern.test(emailInputR.value)) {
                emailInputR.classList.remove("is-danger");
                emailInputR.classList.add("is-success");
                document.getElementById("r-email-inv").style.display="none";
            }else {
                emailInputR.classList.remove("is-success");
                emailInputR.classList.add("is-danger");
                document.getElementById("r-email-inv").style.display="inline";
                registerError = true;
            }
            break;
        case 2:
            if (emailPattern.test(emailInputL.value)) {
                emailInputL.classList.remove("is-danger");
                emailInputL.classList.add("is-success");
                document.getElementById("l-email-inv").style.display="none";
            }else {
                emailInputL.classList.remove("is-success");
                emailInputL.classList.add("is-danger");
                document.getElementById("l-email-inv").style.display="inline";
                loginError = true;
            }
    }
}

function checkDate() {
    var datePattern = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;

    if (datePattern.test(dateInputR.value)) {
        dateInputR.classList.remove("is-danger");
        dateInputR.classList.add("is-success");
        document.getElementById("r-date-inv").style.display="none";
    }else {
        dateInputR.classList.remove("is-success");
        dateInputR.classList.add("is-danger");
        document.getElementById("r-date-inv").style.display="inline";
        registerError = true;
    }
}

function checkPassword(mode) {
    var lowerCaseLetters = /[a-z]/g;
    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;
    var minLength = 6;
    var maxLength = 12;

    switch (mode) {
        case 1:
            console.log("hey");
            if(passwordInputR.value.match(lowerCaseLetters) && passwordInputR.value.match(upperCaseLetters) && passwordInputR.value.match(numbers)
                && passwordInputR.value.length >= minLength && passwordInputR.value.length <= maxLength) {
                passwordInputR.classList.remove("is-danger");
                passwordInputR.classList.add("is-success");
                document.getElementById("r-password-inv").style.display="none";
            }else {
                passwordInputR.classList.remove("is-success");
                passwordInputR.classList.add("is-danger");
                document.getElementById("r-password-inv").style.display="inline";
                registerError = true;
            }
            break;
        case 2:
            if(passwordInputL.value.match(lowerCaseLetters) && passwordInputL.value.match(upperCaseLetters) && passwordInputL.value.match(numbers)
                && passwordInputL.value.length >= minLength && passwordInputL.value.length <= maxLength) {
                passwordInputL.classList.remove("is-danger");
                passwordInputL.classList.add("is-success");
                document.getElementById("l-password-inv").style.display="none";
            }else {
                passwordInputL.classList.remove("is-success");
                passwordInputL.classList.add("is-danger");
                document.getElementById("l-password-inv").style.display="inline";
                loginError = true;
            }
            break;
    }

}

function checkConfPassword() {
    if (confPasswordInputR.value !== "" && confPasswordInputR.value === passwordInputR.value) {
        confPasswordInputR.classList.remove("is-danger");
        confPasswordInputR.classList.add("is-success");
        document.getElementById("r-confpass-inv").style.display="none";
    }else {
        confPasswordInputR.classList.remove("is-success");
        confPasswordInputR.classList.add("is-danger");
        document.getElementById("r-confpass-inv").style.display="inline";
        registerError = true;
    }
}
