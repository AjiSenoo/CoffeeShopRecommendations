<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Recommendations</title>
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
            margin-bottom: 20px;
            color: #283618;
        }
        button {
            background-color: #f4a261;
            color: white;
            border: none;
            padding: 12px 24px;
            margin: 10px 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        button:hover {
            background-color: #e76f51;
        }
        #loading {
            display: none;
            color: #e9c46a;
            font-style: italic;
            margin-top: 10px;
        }
        #recommendations, #allBranches {
            margin-top: 20px;
            text-align: left;
        }
        .recommendation, .branch {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .recommendation strong, .branch strong {
            color: #283618;
        }
        .recommendation button, .branch button {
            background-color: #606c38;
            color: white;
            border: none;
            padding: 8px 16px;
            margin-top: 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
        }
        .recommendation button:hover, .branch button:hover {
            background-color: #283618;
        }
    </style>
    <script>
        // Function to fetch all branches
        function fetchAllBranches() {
            const allBranchesDiv = document.getElementById("allBranches");
            allBranchesDiv.innerHTML = ""; // Clear previous branches

            fetch("<?= site_url('/branches') ?>", {
                method: "GET",
            })
            .then((response) => response.json())
            .then((data) => {
                if (data && data.length > 0) {
                    allBranchesDiv.innerHTML = data
                        .map(
                            (branch) => `
                                <div class="branch">
                                    <strong>Branch Name:</strong> ${branch.name}<br>
                                    <strong>Location:</strong> ${branch.latitude}, ${branch.longitude}<br>
                                    <strong>Current Queue Length:</strong> ${branch.queue_length}<br>
                                    <button onclick="giveReview(${branch.id})">Give Review</button>
                                    <button onclick="window.location.href='<?= site_url('/branch/reviews') ?>/${branch.id}'">Show Reviews</button>
                                </div>
                            `
                        )
                        .join("");
                } else {
                    allBranchesDiv.innerHTML = `<p>No branches found.</p>`;
                }
            })
            .catch((error) => {
                allBranchesDiv.innerHTML = `<p>Error fetching branches.</p>`;
                console.error("Error:", error);
            });
        }

        // Function to give a review
        function giveReview(branchId) {
            const rating = prompt("Enter your rating (1-5):");
            if (!rating || isNaN(rating) || rating < 1 || rating > 5) {
                alert("Please enter a valid rating between 1 and 5.");
                return;
            }

            const review = prompt("Enter your review:");
            if (!review || review.trim() === "") {
                alert("Please enter a valid review.");
                return;
            }

            fetch("<?= site_url('/customer/submit-review') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    branch_id: branchId,
                    rating: parseInt(rating),
                    review: review.trim(),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(data.message || "Review submitted successfully!");
                } else {
                    alert(data.message || "Failed to submit review.");
                }
            })
            .catch((error) => {
                alert("Error submitting your review.");
                console.error("Error:", error);
            });
        }

        // Function to fetch location and recommendations
        function getLocationAndRecommend() {
            const loadingText = document.getElementById("loading");
            const recommendationsDiv = document.getElementById("recommendations");
            loadingText.style.display = "block"; // Show loading text
            recommendationsDiv.innerHTML = ""; // Clear previous recommendations

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    fetch("<?= site_url('/recommend') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            latitude: latitude,
                            longitude: longitude,
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        loadingText.style.display = "none"; // Hide loading text

                        if (data.recommendations) {
                            recommendationsDiv.innerHTML = data.recommendations
                                .map(
                                    (rec) => `
                                        <div class="recommendation">
                                            <strong>Branch:</strong> ${rec.branch}<br>
                                            <strong>Travel Time:</strong> ${rec.travel_time} mins<br>
                                            <strong>Queue Length:</strong> ${rec.queue_length}<br>
                                            <strong>Rating:</strong> ${rec.mean_rating}<br>
                                            <button onclick="placeOrder(${rec.branch_id})">Order</button>
                                        </div>
                                    `
                                )
                                .join("");
                        } else {
                            recommendationsDiv.innerHTML = `<p>${data.message || "No recommendations available."}</p>`;
                        }
                    })
                    .catch((error) => {
                        loadingText.style.display = "none"; // Hide loading text
                        recommendationsDiv.innerHTML = `<p>Error fetching recommendations.</p>`;
                        console.error("Error:", error);
                    });
                });
            } else {
                loadingText.style.display = "none"; // Hide loading text
                alert("Geolocation is not supported by your browser.");
            }
        }

        // Function to place an order
        function placeOrder(branchId) {
            const cups = prompt("How many cups would you like to order?");
            if (!cups || isNaN(cups) || cups <= 0) {
                alert("Please enter a valid number of cups.");
                return;
            }

            fetch("<?= site_url('/add-to-queue') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    branch_id: branchId,
                    cups: parseInt(cups),
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(data.message || "Order added successfully!");
                    location.reload(); // Reload to update queue data
                } else {
                    alert(data.message || "Failed to add order.");
                }
            })
            .catch((error) => {
                alert("Error processing your order.");
                console.error("Error:", error);
            });
        }
    </script>
</head>
<body>
    <h2>Welcome, <?= esc($username) ?></h2>
    <h1>Find Coffee Shop Recommendations</h1>
    <button onclick="getLocationAndRecommend()">Find Recommendations</button>
    <p id="loading" style="display:none;">Processing... Please wait.</p>
    <div id="recommendations"></div>

    <h1>All Branches</h1>
    <button onclick="fetchAllBranches()">View All Branches</button>
    <div id="allBranches"></div>

    <button onclick="window.location.href='<?= site_url('/customer/logout') ?>'">Log Out</button>
</body>
</html>
