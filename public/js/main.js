// if (document.querySelector('.sign-up-container')) {
//     document.querySelector('.sign-up-container form').addEventListener('submit', function(e) {
//         e.preventDefault();
//
//         const name = document.querySelector('input[type="text"]:nth-of-type(1)').value;
//         const email = document.querySelector('input[type="email"]').value;
//         const password = document.querySelector('input[type="password"]').value;
//         const birthDate = document.querySelector('input[type="date"]').value;
//         const location = document.querySelector('input[type="text"]:nth-of-type(2)').value;
//         const hobby = document.querySelector('input[type="text"]:nth-of-type(3)').value;
//
//         if (name === '' || email === '' || password === '' || birthDate === '' || location === '' || hobby === '') {
//             alert('Ju lutemi plotësoni të gjitha fushat!');
//             return;
//         }
//
//         alert('Keni regjistruar me sukses!');
//         localStorage.setItem('user', JSON.stringify({ name, email, password, birthDate, location, hobby }));
//
//         window.location.href = 'login.html';
//     });
// }
//
// if (document.querySelector('.sign-in-container')) {
//     document.querySelector('.sign-in-container form').addEventListener('submit', function(e) {
//         e.preventDefault();
//
//         const email = document.querySelector('input[type="email"]').value;
//         const password = document.querySelector('input[type="password"]').value;
//
//         const storedUser = JSON.parse(localStorage.getItem('user'));
//
//         if (!storedUser) {
//             alert('Kredencialet e pavlefshme!');
//             return;
//         }
//
//         if (storedUser.email === email && storedUser.password === password) {
//             alert('Logim i suksesshëm!');
//             window.location.href = 'profile.html';
//         } else {
//             alert('Email ose password gabim!');
//         }
//     });
// }

//Failed Login Attempts
// document.getElementById("loginForm").addEventListener("submit", function (event) {
//     event.preventDefault();
//
//     const username = document.getElementById("username").value.trim();
//     const password = document.getElementById("password").value.trim();
//
//     // Simulojmë një thirrje backend për login
//     fakeLoginRequest(username, password)
//         .then(response => {
//             if (response.status === "failed") {
//                 if (response.reason === "invalid_credentials") {
//                     showError("Emri i përdoruesit ose fjalëkalimi është i pasaktë.");
//                 } else if (response.reason === "account_locked") {
//                     showError("Llogaria juaj është bllokuar. Provoni përsëri pas " + response.retry_after + " minutash.");
//                 }
//             } else {
//                 alert("Hyrja ishte e suksesshme!");
//             }
//         })
//         .catch(() => {
//             showError("Një gabim ndodhi. Ju lutem provoni përsëri më vonë.");
//         });
// });

function showError(message) {
    let errorDiv = document.getElementById("dynamic-error");
    if (!errorDiv) {
        errorDiv = document.createElement("div");
        errorDiv.id = "dynamic-error";
        errorDiv.style.color = "red";
        document.getElementById("loginForm").prepend(errorDiv);
    }
    errorDiv.textContent = message;
}
