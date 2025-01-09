<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Finder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f1e9;
            color: #4a3f35;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        button {
            background-color: #8c7b75;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #6d6057;
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
