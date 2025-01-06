<?php

namespace App\Controllers;

use App\Models\QueueModel;

class AdminController extends BaseController
{
    public function manageQueue()
    {
        $branchId = session()->get('branch_id'); // Admin's branch ID
        $action = $this->request->getVar('action'); // Either 'add' or 'subtract'
        $amount = (int)$this->request->getVar('amount'); // Number to add/subtract

        $queueModel = new QueueModel();
        $queue = $queueModel->find($branchId);

        if ($queue) {
            $newQueueLength = $queue['queue_length'];

            if ($action === 'add') {
                $newQueueLength += $amount; // Add to the queue
            } elseif ($action === 'subtract') {
                $newQueueLength -= $amount; // Subtract from the queue
                if ($newQueueLength < 0) {
                    $newQueueLength = 0; // Ensure queue length cannot be negative
                }
            } else {
                return $this->response->setJSON(['error' => 'Invalid action. Use "add" or "subtract".'], 400);
            }

            // Update the queue length
            $queueModel->update($branchId, ['queue_length' => $newQueueLength]);

            return $this->response->setJSON([
                'message' => 'Queue updated successfully',
                'queue_length' => $newQueueLength
            ]);
        }

        return $this->response->setJSON(['error' => 'Branch not found'], 404);
    }
}
