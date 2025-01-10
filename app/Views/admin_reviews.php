<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?= esc($branch['name']) ?></title>
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
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .review {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            background-color: #fff;
            width: 100%;
            max-width: 500px;
            text-align: left;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .review strong {
            color: #4a3f35;
        }
        .review small {
            color: #888;
        }
        button {
            padding: 12px 20px;
            margin-top: 20px;
            background-color: #ffa66e; /* Soft orange tone */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ff8c42; /* Slightly darker orange for hover */
        }
        .no-reviews {
            font-size: 18px;
            color: #6d6057;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Reviews for <?= esc($branch['name']) ?></h1>
    <?php if (empty($reviews)): ?>
        <p class="no-reviews">No reviews available for this branch.</p>
    <?php else: ?>
        <div>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <strong>Rating:</strong> <?= esc($review['rating']) ?> / 5<br>
                    <strong>Review:</strong> <?= esc($review['review']) ?><br>
                    <small><em>Posted on <?= esc($review['created_at']) ?></em></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <button onclick="window.history.back()">Back</button>
</body>
</html>
