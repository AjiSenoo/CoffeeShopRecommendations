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
    </style>
    <script>
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
    </script>
</head>
<body>
    <h1>Find Coffee Shop Recommendations</h1>
    <button onclick="getLocationAndRecommend()">Find Recommendations</button>
    <p id="loading">Processing... Please wait.</p>
    <div id="recommendations"></div>
</body>
</html>
