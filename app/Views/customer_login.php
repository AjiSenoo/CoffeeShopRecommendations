<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fefae0;
            color: #606c38;
            text-align: center;
            padding: 20px;
        }
        form {
            margin: 20px auto;
            display: inline-block;
            text-align: left;
        }
        label {
            font-weight: 700;
        }
        input {
            padding: 8px;
            margin: 5px 0;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-button {
            background-color: #f4a261;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        .login-button:hover {
            background-color: #e76f51;
        }
        .register-link {
            margin-top: 15px;
            display: block;
            font-size: 14px;
            text-decoration: none;
            color: #606c38;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Customer Login</h1>
    <form action="<?= site_url('customer/authenticate') ?>" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit" class="login-button">Log In</button>
    </form>
    <a href="<?= site_url('customer/register') ?>" class="register-link">Register</a>
</body>
</html>
