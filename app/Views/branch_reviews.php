<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?= esc($branch['name']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f1e9;
            color: #4a3f35;
            padding: 20px;
            text-align: center;
        }
        .review {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #fff;
            text-align: left;
        }
        h1 {
            color: #4a3f35;
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
