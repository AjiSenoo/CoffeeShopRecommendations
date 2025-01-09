<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?= esc($branch['name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fefae0;
            color: #606c38;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 22px;
            color: #283618;
            margin-bottom: 10px;
        }
        p {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .review {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .review strong {
            color: #283618;
        }
        .review small {
            color: #a5a58d;
        }
        button {
            background-color: #f4a261;
            color: white;
            border: none;
            padding: 12px 24px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #e76f51;
        }
    </style>
</head>
<body>
    <h1>Reviews for <?= esc($branch['name']) ?></h1>
    <p><strong>Location:</strong> <?= esc($branch['latitude']) ?>, <?= esc($branch['longitude']) ?></p>

    <?php if (empty($reviews)): ?>
        <p>No reviews for this branch yet.</p>
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
