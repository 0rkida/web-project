<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* General Reset */
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

        body, h1, h3, p, button, input {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background: #f57760; /* Background color */
            color: #322525; /* Dark text color */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        h1 {
            font-size: 36px;
            color: #322525;
            font-weight: 800;
            margin-bottom: 20px;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            color: #322525;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            padding: 15px;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            background-color: #ff334b;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ff4b5c;
        }

        footer {
            background-color: #322525;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

        footer p {
            font-size: 14px;
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="post" action="/reset-password">
        <h1>Reset Password</h1>
        <input type="hidden" name="token" value="<?php echo $_GET['code']; ?>">
        <label>
            <input type="password" name="new_password" placeholder="New Password" required>
        </label>
        <label>
        <input  type="email" name="email" placeholder="email" required>
        </label>
        <button type="submit">Reset</button>
    </form>


</div>
</body>
</html>
