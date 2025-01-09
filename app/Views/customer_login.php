<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
</head>
<body>
    <form action="<?= site_url('customer/authenticate') ?>" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Log In</button>
    </form>
    <button onclick="location.href='<?= site_url('customer/register') ?>'">Register</button>
</body>
</html>
