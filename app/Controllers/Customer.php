<?php

namespace App\Controllers;

use App\Models\UserModel;

class Customer extends BaseController
{
    public function login()
    {
        return view('customer_login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/CafeFinder');
    }

    public function register()
    {
        return view('customer_register');
    }

    public function authenticate()
    {
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));
    
        $customerModel = new \App\Models\UserModel();
        $customer = $customerModel->where('email', $email)->first();
    
        if ($customer && password_verify($password, $customer['password'])) {
            // Set session data
            session()->set([
                'customer_id' => $customer['id'], // Store customer_id in session
                'email' => $customer['email'],
                'username' => $customer['username'],
                'isLoggedIn' => true,
            ]);
    
            return redirect()->to('/customer/dashboard');
        }
    
        return redirect()->back()->with('error', 'Invalid email or password.');
    }    


    public function store()
    {
        $model = new UserModel();

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'customer',
        ];

        $model->save($data);

        return redirect()->to('/customer/login');
    }

    public function dashboard()
    {
        $data = [
            'username' => session()->get('username') // Assuming the username is stored in the session
        ];
    
        return view('find_recommendations', $data);
    }    

    public function submitReview()
    {
    $branchId = $this->request->getJSON(true)['branch_id'];
    $customerId = session()->get('customer_id'); // Assuming customer ID is stored in the session
    $rating = $this->request->getJSON(true)['rating'];
    $review = $this->request->getJSON(true)['review'];

    if (!$branchId || !$rating || !$review || $rating < 1 || $rating > 5) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid input.']);
    }

    $reviewModel = new \App\Models\ReviewModel();
    $reviewModel->insert([
        'branch_id' => $branchId,
        'customer_id' => $customerId,
        'rating' => $rating,
        'review' => $review,
    ]);

    return $this->response->setJSON(['success' => true, 'message' => 'Review submitted successfully!']);
    }


}
