<?php

namespace App\Controllers;

use App\Models\BranchModel;
use CodeIgniter\RESTful\ResourceController;

class QueueController extends ResourceController
{
    protected $branchModel;

    public function __construct()
    {
        $this->branchModel = new BranchModel();
    }

    public function addToQueue()
{
    // Get the request data
    $data = $this->request->getJSON(true); // Parse JSON data
    $branchId = $data['branch_id'] ?? null;
    $cups = $data['cups'] ?? 0;

    // Log received data
    log_message('debug', 'Received data: ' . print_r($data, true));

    // Check if the branch_id and cups are valid
    if (!$branchId || $cups <= 0) {
        log_message('error', 'Invalid branch ID or number of cups.');
        return $this->fail("Invalid branch ID or number of cups.");
    }

    // Find the branch
    $branch = $this->branchModel->find($branchId);
    if (!$branch) {
        log_message('error', 'Branch not found with ID: ' . $branchId);
        return $this->fail("Branch not found.");
    }

    // Log the branch data to confirm it's being found
    log_message('debug', 'Branch data: ' . print_r($branch, true));

    // Calculate the new queue length
    $newQueueLength = $branch['queue_length'] + $cups;

    // Log the new queue length
    log_message('debug', 'New queue length: ' . $newQueueLength);

    // Update the queue length in the database
    $updateSuccess = $this->branchModel->update($branchId, ['queue_length' => $newQueueLength]);

    // Check if the update was successful
    if (!$updateSuccess) {
        log_message('error', 'Failed to update queue length for branch ID: ' . $branchId);
        return $this->fail("Failed to update queue length.");
    }

    // Return success message with the updated queue length
    return $this->respond([
        'success' => true,
        'message' => "Order added to the queue successfully!",
        'new_queue_length' => $newQueueLength,
    ]);
}

    
}
