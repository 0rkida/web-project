if (document.querySelector('.sign-up-container')) {
    document.querySelector('.sign-up-container form').addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.querySelector('input[type="text"]:nth-of-type(1)').value;
        const email = document.querySelector('input[type="email"]').value;
        const password = document.querySelector('input[type="password"]').value;
        const birthDate = document.querySelector('input[type="date"]').value;
        const location = document.querySelector('input[type="text"]:nth-of-type(2)').value;
        const hobby = document.querySelector('input[type="text"]:nth-of-type(3)').value;

        if (name === '' || email === '' || password === '' || birthDate === '' || location === '' || hobby === '') {
            alert('Ju lutemi plotësoni të gjitha fushat!');
            return;
        }

        alert('Keni regjistruar me sukses!');
        localStorage.setItem('user', JSON.stringify({ name, email, password, birthDate, location, hobby }));

        window.location.href = 'login.html';
    });
}

if (document.querySelector('.sign-in-container')) {
    document.querySelector('.sign-in-container form').addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.querySelector('input[type="email"]').value;
        const password = document.querySelector('input[type="password"]').value;

        const storedUser = JSON.parse(localStorage.getItem('user'));

        if (!storedUser) {
            alert('Përdoruesi nuk ekziston!');
            return;
        }

        if (storedUser.email === email && storedUser.password === password) {
            alert('Logim i suksesshëm!');
            window.location.href = 'profile.html';
        } else {
            alert('Email ose password gabim!');
        }
    });
}
