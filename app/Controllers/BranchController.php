<?php

namespace App\Controllers;

use App\Models\BranchModel;
use CodeIgniter\RESTful\ResourceController;

class BranchController extends ResourceController
{
    protected $branchModel;

    public function __construct()
    {
        $this->branchModel = new BranchModel();
    }

    // Fetch all branches
    public function index()
    {
        $branches = $this->branchModel->findAll();
        return $this->respond($branches);
    }

    // Recommend branch
    public function recommend()
    {
        $latitude = $this->request->getVar('latitude');
        $longitude = $this->request->getVar('longitude');
        
        if (!$latitude || !$longitude) {
            return $this->fail('Latitude and Longitude are required');
        }
    
        $branchModel = new \App\Models\BranchModel();
        $reviewModel = new \App\Models\ReviewModel();
    
        $branches = $branchModel->findAll();
        $recommendations = [];
        $unreachableBranches = [];
        $maxTravelTime = 0;
        $maxQueueLength = 0;
    
        // Calculate max travel time and queue length
        foreach ($branches as $branch) {
            $origin = [$longitude, $latitude];
            $destination = [$branch['longitude'], $branch['latitude']];
    
            $travelTime = $this->getTravelTime($origin, $destination);
    
            if ($travelTime !== null) {
                $maxTravelTime = max($maxTravelTime, $travelTime);
                $maxQueueLength = max($maxQueueLength, $branch['queue_length']);
            }
        }
    
        // Calculate recommendations and mean ratings
        foreach ($branches as $branch) {
            $origin = [$longitude, $latitude];
            $destination = [$branch['longitude'], $branch['latitude']];
            $travelTime = $this->getTravelTime($origin, $destination);
    
            if ($travelTime === null) {
                $unreachableBranches[] = $branch['name'];
                continue; // Skip unreachable branches
            }
    
            // Normalize travel time and queue length
            $normalizedTravelTime = $maxTravelTime > 0 ? $travelTime / $maxTravelTime : 1;
            $normalizedQueueLength = $maxQueueLength > 0 ? $branch['queue_length'] / $maxQueueLength : 1;
    
            // Predefined weights
            $travelWeight = 0.7; 
            $queueWeight = 0.3;
    
            // Calculate final score
            $score = ($normalizedTravelTime * $travelWeight) + ($normalizedQueueLength * $queueWeight);
    
            // Calculate mean rating for the branch
            $meanRating = $reviewModel->where('branch_id', $branch['id'])
                                      ->selectAvg('rating')
                                      ->get()
                                      ->getRow()
                                      ->rating;
    
            $recommendations[] = [
                'branch_id' => $branch['id'],
                'branch' => $branch['name'],
                'travel_time' => round($travelTime / 60, 1), // Convert seconds to minutes
                'queue_length' => $branch['queue_length'],
                'mean_rating' => $meanRating ? round($meanRating, 1) : 'No ratings yet', // Handle no ratings case
                'score' => $score,
            ];
        }
    
        // Sort recommendations by score
        usort($recommendations, fn($a, $b) => $a['score'] <=> $b['score']);
    
        // Limit to top recommendations
        $topRecommendations = array_slice($recommendations, 0, 3);
    
        // Build response
        $response = [
            'recommendations' => $topRecommendations,
        ];
    
        if (!empty($unreachableBranches)) {
            $response['unreachable_branches'] = $unreachableBranches;
            $response['message'] = 'The following branches are not reachable by car: ' . implode(', ', $unreachableBranches);
        }
    
        if (empty($topRecommendations)) {
            return $this->respond([
                'message' => 'No reachable coffee shop branches by car from your location.',
                'unreachable_branches' => $unreachableBranches
            ], 404);
        }
    
        return $this->respond($response);
    }
       
    

    private function getTravelTime($origin, $destination)
    {
        $apiKey = getenv('ORS_API_KEY'); 
        $url = "https://api.openrouteservice.org/v2/matrix/driving-car";

        $locations = [
            $origin,      // Origin [longitude, latitude]
            $destination  // Destination [longitude, latitude]
        ];

        $payload = [
            "locations" => $locations,
            "metrics" => ["duration"] // Fetch travel time
        ];

        $options = [
            "http" => [
                "header" => "Content-Type: application/json\r\nAuthorization: $apiKey\r\n",
                "method" => "POST",
                "content" => json_encode($payload),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return null; // Handle API error
        }

        $data = json_decode($response, true);
        return $data['durations'][0][1] ?? null; // Return travel time in seconds
    }

    public function showReviews($branchId)
{
    $reviewModel = new \App\Models\ReviewModel();
    $branchModel = new \App\Models\BranchModel();

    $branch = $branchModel->find($branchId);

    if (!$branch) {
        return redirect()->to('/customer/view-branches')->with('error', 'Branch not found.');
    }

    $reviews = $reviewModel->where('branch_id', $branchId)->findAll();

    return view('branch_reviews', [
        'branch' => $branch,
        'reviews' => $reviews,
    ]);
}

}
