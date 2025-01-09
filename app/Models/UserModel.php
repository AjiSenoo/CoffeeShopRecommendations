<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // Make sure you create a 'users' table
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'role', 'branch_id'];
}
