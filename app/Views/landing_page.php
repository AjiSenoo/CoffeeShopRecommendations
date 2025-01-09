<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Finder</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fefae0;
            color: #606c38;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        button {
            background-color: #f4a261;
            color: white;
            border: none;
            padding: 12px 24px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #e76f51;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome</h1>
        <button onclick="location.href='<?= site_url('admin/login') ?>'">Log in as Admin</button>
        <button onclick="location.href='<?= site_url('customer/login') ?>'">Log in as Customer</button>
    </div>
</body>
</html>
