<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f1e9;
            color: #4a3f35;
        }
        button {
            padding: 10px 20px;
            margin: 10px;
            background-color: #8c7b75;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #6d6057;
        }
    </style>
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
</head>
<body>
    <h1>Welcome, <?= esc($username) ?></h1>
    <h2>Branch ID: <?= esc($branch_id) ?></h2>
    <button onclick="updateQueue('add')">Add Queue</button>
    <button onclick="updateQueue('subtract')">Subtract Queue</button>
</body>
</html>
