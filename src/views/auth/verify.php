<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikimi i Emailit</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="container">
    <h2>Verifikimi i Emailit</h2>
    <p>Një kod verifikimi është dërguar në emailin tuaj. Ju lutem vendoseni atë më poshtë:</p>
   <form action="/verify" method="post">
       <input type="email" name="email" id="email" placeholder="Email adresa" required>
       <input type="text" name="code" id="verification-code" placeholder="Vendosni kodin e verifikimit" value="<?php echo htmlspecialchars($code); ?>" required />
       <button id="verify-button">Verifiko</button>
   </form>

</div>
<script src="js/verify.js"></script>
</body>
</html>
