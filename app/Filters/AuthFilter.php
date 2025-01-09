<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        $isLoggedIn = session()->get('isLoggedIn');

        // Debugging: Log session data
        log_message('debug', 'Session Data: ' . json_encode(session()->get()));

        if (!$isLoggedIn) {
            // Determine redirection based on URL path
            if (strpos($request->uri->getPath(), 'admin') !== false) {
                return redirect()->to('/admin/login')->with('error', 'Please log in to access this page.');
            } elseif (strpos($request->uri->getPath(), 'customer') !== false) {
                return redirect()->to('/customer/login')->with('error', 'Please log in to access this page.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
