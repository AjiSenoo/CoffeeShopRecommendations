<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function login()
    {
        return view('admin_login');
    }
}
