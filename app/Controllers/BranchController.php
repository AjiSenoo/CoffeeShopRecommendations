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
    
        $branches = $this->branchModel->findAll();
    
        $recommendations = [];
        $unreachableBranches = [];
    
        foreach ($branches as $branch) {
            $origin = [$longitude, $latitude]; // User's location
            $destination = [$branch['longitude'], $branch['latitude']]; // Branch location
    
            $travelTime = $this->getTravelTime($origin, $destination);
    
            if ($travelTime === null) {
                // Log and collect unreachable branches
                $unreachableBranches[] = $branch['name'];
                continue; // Skip this branch
            }
    
            $score = $travelTime + ($branch['queue_length'] * 0.5); // Weighted score
    
            $recommendations[] = [
                'branch' => $branch['name'],
                'travel_time' => round($travelTime / 60, 1), // Convert seconds to minutes
                'queue_length' => $branch['queue_length'],
                'score' => $score,
            ];
        }
    
        // Sort recommendations by score (ascending)
        usort($recommendations, fn($a, $b) => $a['score'] <=> $b['score']);
    
        // Build response
        $response = [
            'recommendations' => $recommendations,
        ];
    
        if (!empty($unreachableBranches)) {
            $response['unreachable_branches'] = $unreachableBranches;
            $response['message'] = 'The following branches are not reachable by car: ' . implode(', ', $unreachableBranches);
        }
    
        if (empty($recommendations)) {
            return $this->respond([
                'message' => 'No reachable coffee shop branches by car from your location.',
                'unreachable_branches' => $unreachableBranches
            ], 404);
        }
    
        return $this->respond($response);
    }
    

    private function getTravelTime($origin, $destination)
    {
        $apiKey = '5b3ce3597851110001cf62485ac63974d9934adbba924b45cc1eea1b'; // Replace with your API key
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
}
