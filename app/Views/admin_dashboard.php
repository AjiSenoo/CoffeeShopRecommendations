<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #fff7e6; /* Soft cream background */
            color: #4a3f35;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #4a3f35;
        }
        p.mean-rating {
            font-size: 18px;
            color: #6d6057;
            margin-bottom: 20px;
        }
        button {
            background-color: #ffa66e; /* Soft orange tone */
            color: white;
            border: none;
            padding: 12px 20px;
            margin: 10px 0;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ff8c42; /* Slightly darker orange for hover */
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 90%;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <h1>Welcome, admin <?= esc($branch_name) ?></h1>
    <p class="mean-rating"><?= esc($branch_name) ?>'s Rating: <strong><?= esc($mean_rating) ?> / 5</strong></p>
    <div class="button-container">
        <button onclick="window.location.href='<?= site_url('/admin/show-reviews') ?>'">Show Reviews</button>
        <button onclick="updateQueue('add')">Add Queue</button>
        <button onclick="updateQueue('subtract')">Subtract Queue</button>
        <button onclick="window.location.href='<?= site_url('/admin/logout') ?>'">Log Out</button>
    </div>
    <script>
        function updateQueue(action) {
            const num = prompt(`Enter the number of customers to ${action}:`);
            if (!num || isNaN(num) || num <= 0) {
                alert("Please enter a valid positive number.");
                return;
            }

            const url = action === 'add' ? "<?= site_url('/admin/add-queue') ?>" : "<?= site_url('/admin/subtract-queue') ?>";

            fetch(url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ num: parseInt(num) }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`${action.charAt(0).toUpperCase() + action.slice(1)} queue successful! New queue length: ${data.new_queue_length}`);
                } else {
                    alert(data.message || "Failed to update queue.");
                }
            })
            .catch(error => {
                alert("Error processing your request.");
                console.error("Error:", error);
            });
        }
    </script>
</body>
</html>
