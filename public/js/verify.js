document.getElementById("verify-button").addEventListener("click", async function () {
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get("email"); // Merr emailin nga URL-ja
    const code = document.getElementById("verification-code").value;

    const response = await fetch("/verify", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, code }),
    });

    const result = await response.json();

    if (result.success) {
        alert("Emaili u verifikua me sukses!");
        window.location.href = "login.html";
    } else {
        alert("Kodi i verifikimit është i gabuar ose ka skaduar.");
    }
});
