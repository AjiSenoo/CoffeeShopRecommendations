<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\BranchModel;

class Admin extends BaseController
{
    public function login()
    {
        return view('admin_login');
    }

    public function authenticate()
    {
        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));
    
        $adminModel = new AdminModel();
        $admin = $adminModel->where('username', $username)->first();
    
        if ($admin) {
            error_log("Stored hash: " . $admin['password']);
            if (password_verify($password, $admin['password'])) {
                error_log("Password verification succeeded!");
                session()->set([
                    'admin_id' => $admin['id'],
                    'username' => $admin['username'],
                    'branch_id' => $admin['branch_id'],
                    'isLoggedIn' => true,
                ]);
                return redirect()->to('/admin/dashboard');
            } else {
                error_log("Password verification failed.");
            }
        } else {
            error_log("Admin not found for username: $username");
        }

        return redirect()->to('/admin/login')->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }

    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/admin/login');
        }

        return view('admin_dashboard', [
            'username' => session()->get('username'),
            'branch_id' => session()->get('branch_id'),
        ]);
    }

    public function subtractQueue()
    {
        $input = $this->request->getJSON(true); // Decode JSON input as an array
        $branchId = session()->get('branch_id'); // Get the admin's branch ID
        $num = isset($input['num']) ? $input['num'] : null;

        error_log("Received num for subtraction: " . $num); // Log received value

        if (!is_numeric($num) || $num <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please enter a valid positive number.']);
        }

        $branchModel = new BranchModel();
        $branch = $branchModel->find($branchId);

        if ($branch) {
            // Ensure queue length does not go below 0
            $newQueueLength = max(0, $branch['queue_length'] - $num);
            $branchModel->update($branchId, ['queue_length' => $newQueueLength]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Queue updated successfully!',
                'new_queue_length' => $newQueueLength,
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Branch not found.']);
        }
    }

    public function addQueue()
    {
        $input = $this->request->getJSON(true); // Decode JSON input as an array
        $branchId = session()->get('branch_id'); // Get the admin's branch ID
        $num = isset($input['num']) ? $input['num'] : null;

        error_log("Received num for addition: " . $num); // Log received value

        if (!is_numeric($num) || $num <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please enter a valid positive number.']);
        }

        $branchModel = new BranchModel();
        $branch = $branchModel->find($branchId);

        if ($branch) {
            $newQueueLength = $branch['queue_length'] + $num;
            $branchModel->update($branchId, ['queue_length' => $newQueueLength]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Queue updated successfully!',
                'new_queue_length' => $newQueueLength,
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Branch not found.']);
        }
    }
}
