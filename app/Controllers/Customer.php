<?php

namespace App\Controllers;

use App\Models\UserModel;

class Customer extends BaseController
{
    public function login()
    {
        return view('customer_login');
    }

    public function register()
    {
        return view('customer_register');
    }

    public function authenticate()
    {
    $model = new \App\Models\UserModel();

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    // Find user by email
    $user = $model->where('email', $email)->first();

    if ($user && password_verify($password, $user['password'])) {
        // Authentication successful
        if ($user['role'] === 'customer') {
            return redirect()->to('/customer/dashboard');
        } else {
            return redirect()->to('/admin/login');
        }
    } else {
        // Authentication failed
        return redirect()->back()->with('error', 'Invalid login credentials');
    }
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
    return view('find_recommendations');
    }

}
