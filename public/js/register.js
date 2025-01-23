document.getElementById("registrationForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevents form submission

    let isValid = true;

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const dob = document.getElementById("dob").value.trim();

    // Clears previous error messages
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

    // Date of birth validation
    const today = new Date().toISOString().split("T")[0];
    if (!dob || dob > today) {
        showError("dob", "Ju lutem zgjidhni një datë të vlefshme lindjeje.");
        isValid = false;
    }

    // If all fields are valid
    if (isValid) {
        alert("Regjistrimi u krye me sukses!");
        document.querySelector("button[type='submit']").disabled = true; // Parandalon dërgime të dyfishta
        this.submit();
    }
});

function showError(inputId, message) {
    const inputField = document.getElementById(inputId);

    // Check and remove existing error for this fieldconst existingError = inputField.parentNode.querySelector(".error");
    if (existingError) {
        existingError.remove();
    }

    // Create and add new error
    const errorDiv = document.createElement("div");
    errorDiv.className = "error";
    errorDiv.textContent = message;
    inputField.parentNode.insertBefore(errorDiv, inputField.nextSibling);
}
