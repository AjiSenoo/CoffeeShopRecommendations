<?php

namespace App\Controllers;

use App\Models\QueueModel;

class CustomerController extends BaseController
{
    public function addToQueue()
    {
        $branchId = $this->request->getVar('branch_id');
        $increment = (int)$this->request->getVar('increment'); // Number to add to the queue

        $queueModel = new QueueModel();
        $queue = $queueModel->find($branchId);

        if ($queue) {
            $queueModel->update($branchId, [
                'queue_length' => $queue['queue_length'] + $increment
            ]);

            return $this->response->setJSON([
                'message' => 'Queue updated successfully',
                'queue_length' => $queue['queue_length'] + $increment
            ]);
        }

        return $this->response->setJSON(['error' => 'Branch not found'], 404);
    }
}
