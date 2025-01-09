<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function landingPage()
    {
        return view('landing_page'); // The view name should match the file in `app/Views`
    }
}
