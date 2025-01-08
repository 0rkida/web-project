document.getElementById("registrationForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    let isValid = true;

    // Get input values
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const dob = document.getElementById("dob").value.trim();

    // Clear previous error messages
    document.querySelectorAll(".error").forEach(el => el.remove());

    // Name validation
    if (name.length < 2) {
        showError("name", "Emri duhet të ketë të paktën 2 karaktere.");
        isValid = false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError("email", "Ju lutem futni një email të vlefshëm.");
        isValid = false;
    }

    // Username validation
    const usernameRegex = /^[a-z0-9_]{3,15}$/;
    if (!usernameRegex.test(username)) {
        showError("username", "Ju lutem futni një emër përdoruesi të vlefshëm. (Vetëm shkronja të vogla, numra, nënvizime, 3-15 karaktere.)");
        isValid = false;
    }

    // Password validation
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(password)) {
        showError("password", "Passwordi duhet të përmbajë të paktën një shkronjë të madhe, një numër dhe një simbol.");
        isValid = false;
    }

    // Date of Birth validation
    const today = new Date().toISOString().split("T")[0];
    if (!dob || dob > today) {
        showError("dob", "Ju lutem zgjidhni një datë të vlefshme lindjeje.");
        isValid = false;
    }

    // Submit if all fields are valid
    if (isValid) {
        alert("Regjistrimi u krye me sukses!");
        document.querySelector("button[type='submit']").disabled = true; // Prevent double submission
        this.submit();
    }
});

function showError(inputId, message) {
    const inputField = document.getElementById(inputId);
    const errorDiv = document.createElement("div");
    errorDiv.className = "error";
    errorDiv.textContent = message;
    inputField.parentNode.insertBefore(errorDiv, inputField.nextSibling);
}

// Field-specific validation on blur
document.getElementById("name").addEventListener("blur", function () {
    const name = this.value.trim();
    if (name.length < 2) {
        showError("name", "Emri duhet të ketë të paktën 2 karaktere.");
    }
});

document.getElementById("email").addEventListener("blur", function () {
    const email = this.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError("email", "Ju lutem futni një email të vlefshëm.");
    }
});

document.getElementById("username").addEventListener("blur", function () {
    const username = this.value.trim();
    const usernameRegex = /^[a-z0-9_]{3,15}$/;
    if (!usernameRegex.test(username)) {
        showError("username", "Ju lutem futni një emër përdoruesi të vlefshëm. (Vetëm shkronja të vogla, numra, nënvizime, 3-15 karaktere.)");
    }
});

document.getElementById("password").addEventListener("blur", function () {
    const password = this.value.trim();
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(password)) {
        showError("password", "Passwordi duhet të përmbajë të paktën një shkronjë të madhe, një numër dhe një simbol.");
    }
});

document.getElementById("dob").addEventListener("blur", function () {
    const dob = this.value.trim();
    const today = new Date().toISOString().split("T")[0];
    if (!dob || dob > today) {
        showError("dob", "Ju lutem zgjidhni një datë të vlefshme lindjeje.");
    }
});
