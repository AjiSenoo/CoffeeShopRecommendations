<?php

namespace App\Controllers;

use App\Models\BranchModel;
use CodeIgniter\RESTful\ResourceController;

class BranchesController extends ResourceController
{
    protected $branchModel;

    public function __construct()
    {
        $this->branchModel = new BranchModel();
    }

    public function index()
    {
        $branches = $this->branchModel->findAll();
        return $this->respond($branches);
    }
}
