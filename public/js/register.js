document.getElementById("registrationForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    let isValid = true;

    // Get input values
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const username = document.getElementById("username").value.trim()
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

    const usernameRegex = /^[a-z0-9_]{3,15}$/;
    if (!usernameRegex.test(username)) {
        showError("username", "Ju lutem futni një emër përdoruesi të vlefshëm. (Vetëm shkronja të vogla, numra, nënvizime, 3-15 karaktere.)");
        isValid = false;
    }


    // Password validation
    if (password.length < 8) {
        showError("password", "Passwordi duhet të ketë të paktën 8 karaktere.");
        isValid = false;
    }

    // Date of Birth validation
    if (!dob) {
        showError("dob", "Ju lutem zgjidhni një datë lindjeje.");
        isValid = false;
    }

    // Submit if all fields are valid
    if (isValid) {
        alert("Regjistrimi u krye me sukses!");
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