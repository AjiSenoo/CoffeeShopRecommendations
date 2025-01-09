<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Recommendations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f1e9;
            color: #4a3f35;
            text-align: center;
            padding: 20px;
        }
        button {
            background-color: #8c7b75;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #6d6057;
        }
        #loading {
            display: none;
            color: #8c7b75;
            font-style: italic;
        }
        #recommendations {
            margin-top: 20px;
            text-align: left;
        }
        .recommendation {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #fff;
        }
        #allBranches {
            margin-top: 20px;
            text-align: left;
        }
        .branch {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #fff;
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
                                    <strong>Mean Rating:</strong> ${rec.mean_rating}<br>
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
    <h1>Find Coffee Shop Recommendations</h1>
    <button onclick="getLocationAndRecommend()">Find Recommendations</button>
    <p id="loading" style="display:none;">Processing... Please wait.</p>
    <div id="recommendations"></div>

    <h1>All Branches</h1>
    <button onclick="fetchAllBranches()">View All Branches</button>
    <div id="allBranches"></div>
</body>
</html>
